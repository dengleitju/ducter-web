<?php

namespace app\controllers;

use Yii;
use app\models\DcmdApp;
use app\models\DcmdDepartment;
use app\models\DcmdService;
use app\models\DcmdUserGroup;
use app\models\DcmdServiceSearch;
use app\models\DcmdGroup;
use app\models\DcmdAppSearch;
use app\models\DcmdAppArchDiagram;
use app\models\DcmdAppArchDiagramSearch;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdAppController implements the CRUD actions for DcmdApp model.
 */
class DcmdAppController extends Controller
{
    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all DcmdApp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = DcmdGroup::find()->asArray()->all();
        $sys = array();
        $svr = array();
        foreach($query as $item) {
          if($item['gtype'] == 1) $sys[$item['gid']] = $item['gname'];
          else $svr[$item['gid']] = $item['gname'];
        }
        $query = DcmdDepartment::find()->asArray()->all();
        $depart = array();
        foreach($query as $item) $depart[$item['depart_id']] = $item['depart_name'];
        $searchModel = new DcmdAppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sys' => $sys,
            'svr' => $svr,
            'depart' => $depart,
        ]);
    }

    /**
     * Displays a single DcmdApp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $depart = $this->getDepart();
        $searchModel = new DcmdServiceSearch();
        $con = array();
        $con['DcmdServiceSearch'] = array('app_id'=>$id);
        if(array_key_exists('DcmdServiceSearch', Yii::$app->request->queryParams))
          $con = array_merge($con, Yii::$app->request->queryParams);
        $con['DcmdServiceSearch']['app_id'] = $id;
        $dataProvider = $searchModel->search($con);
        $model = $this->findModel($id);
        ///处理图片
        $base_path = dirname(__DIR__)."/web/app_image/";
        $query = DcmdAppArchDiagram::find()->andWhere(['app_id'=>$id])->asArray()->all();
        $app_images = array();
        foreach($query as $item) {
          $img_path = $base_path."app_".$item['arch_name'].'_'.$item['app_id'].'.jpg';
          array_push($app_images, "/dcmd/app_image/app_".$item['arch_name'].'_'.$item['app_id'].'.jpg');
          if(file_exists($img_path)) continue;
          $fp=fopen($img_path,'wb') or die("Open file $img_path fail!");
          fwrite($fp,stripslashes($item['diagram']));
          fclose($fp);
        }
        $imageSearch = new DcmdAppArchDiagramSearch();
        $imageProvider = $imageSearch->search(array('DcmdAppArchDiagramSearch'=>array('app_id'=>$id)));
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'imageProvider' => $imageProvider,
        ]);
    }

    /**
     * Creates a new DcmdApp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        ///仅admin可以创建
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->goBack();///redirect(array('index'));
        }
        $model = new DcmdApp();

        $depart = $this->getDepart();
        $user_group = $this->getUserGroup();
        $sys_user_group = array();
        $sys_tmp =  $user_group["sys"];
        $svr_user_group = $user_group["svr"];
        ///仅仅可以创建所属系统组的应用
        $query = DcmdUserGroup::find()->andWhere(['uid'=>Yii::$app->user->getId()])->asArray()->all();;
        foreach($query as $item) {  
          if(array_key_exists($item['gid'], $sys_tmp)) $sys_user_group[$item['gid']] = $sys_tmp[$item['gid']];
        }
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();

          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(1, "insert app:".$model->app_name);
            Yii::$app->getSession()->setFlash('success', '添加成功!');
            return $this->redirect(['view', 'id' => $model->app_id, 'sys_user_group' => $sys_user_group,
                'svr_user_group' => $svr_user_group, 'depart' => $depart,]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }
        return $this->render('create', [
             'model' => $model,
             'sys_user_group' => $sys_user_group,
             'svr_user_group' => $svr_user_group,
             'depart' => $depart,
        ]);
    }

    /**
     * Updates an existing DcmdApp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/view', 'id'=>$id));
        }
        $depart = $this->getDepart();
        $user_group = $this->getUserGroup();
        $sys_user_group = array();
        $sys_tmp =  $user_group["sys"];
        $svr_user_group = $user_group["svr"];
        ///仅仅可以创建所属系统组的应用
        $query = DcmdUserGroup::find()->andWhere(['uid'=>Yii::$app->user->getId()])->asArray()->all();;
        foreach($query as $item) {
          if(array_key_exists($item['gid'], $sys_tmp)) $sys_user_group[$item['gid']] = $sys_tmp[$item['gid']];
        }
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(2, "update app:".$model->app_name);
            Yii::$app->getSession()->setFlash('success', '修改成功!');        
            return $this->redirect(['view', 'id' => $model->app_id, 'sys_user_group' => $sys_user_group,
                'svr_user_group' => $svr_user_group, 'depart' => $depart,]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str); 
        }
        return $this->render('update', [
             'model' => $model,
             'sys_user_group' => $sys_user_group,
             'svr_user_group' => $svr_user_group,
             'depart' => $depart,
        ]);
    }

    /**
     * Deletes an existing DcmdApp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/index'));
        }
        $node = DcmdService::find()->where(['app_id' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '该产品的服务不为空,不可删除!');
        }else {
          $this->oprlog(3, "delete app:".$model->app_name);
          ///删除架构图
          $this->deleteDiagram($id);
          $model->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }
        return $this->redirect(['index']);
    }
    public function actionDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择产品!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['sa_gid']]);
        if($query==NULL) {
          $err_msg .=$model->app_name.":没有权限删除<br>";
          continue;
        }
        $node = DcmdService::find()->where(['app_id' => $id])->one(); 
        if($node) {
          $err_msg .=$model->app_name.'该产品的服务非空,不可删除<br>';
          continue;
        }else {
          $suc_msg .=$model->app_name.':删除成功<br>';
          $this->oprlog(3, "delete app:".$model->app_name);
        }
        $this->deleteDiagram($id);
        $model->delete();
      }
      if($suc_msg) Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg) Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);
    }
 
    ///删除应用对应的图片
    private function deleteDiagram($id){
      $query = DcmdAppArchDiagram::find()->andWhere(['app_id'=>$id])->asArray()->all();
      foreach($query as $item) {
        ///删除文件
        $base_path = dirname(__DIR__)."/web/app_image/app_";
        $img_path = $base_path.$item['arch_name'].'_'.$id.'.jpg';
        if(file_exists($img_path)) unlink($img_path);
      }
      DcmdAppArchDiagram::deleteAll(['app_id'=>$id]);
    }
    /**
     * Finds the DcmdApp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdApp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdApp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function getDepart() {
      $ret = DcmdDepartment::find()->asArray()->all();
      $depart = array();
      foreach($ret as $item) {
       $depart[$item['depart_id']] = $item['depart_name'];
      }
      return $depart;
   }
   protected function getUserGroup() {
     $ret = DcmdGroup::find()->asArray()->all();
     $user_group = array();
     $user_group['sys'] = array();
     $user_group['svr'] = array();
     foreach($ret as $item) {
      if($item['gtype'] == 1)
       $user_group['sys'][$item['gid']] = $item['gname'];
      else
       $user_group['svr'][$item['gid']] = $item['gname']; 
     }
     return $user_group;
  }
  public function userGroupName($gid) {
    $ret = DcmdUserGroup::findOne($gid);
    if($ret) return $ret['gname'];
    return "";
  }
  public function department($depart_id) {
   $ret = DcmdDepartment::findOne($depart_id);
   if ($ret) return $ret['depart_name'];
   return "";
  }
  private function oprlog($opr_type, $sql) {
    $opr_log = new DcmdOprLog();
    $opr_log->log_table = "dcmd_app";          
    $opr_log->opr_type = $opr_type;
    $opr_log->sql_statement = $sql;
    $opr_log->ctime = date('Y-m-d H:i:s');
    $opr_log->opr_uid = Yii::$app->user->getId();
    $opr_log->save();
  }
}
