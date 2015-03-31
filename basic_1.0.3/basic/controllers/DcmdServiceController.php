<?php

namespace app\controllers;

use Yii;
use app\models\DcmdService;
use app\models\DcmdServicePool;
use app\models\DcmdServiceSearch;
use app\models\DcmdServicePoolSearch;
use app\models\DcmdApp;
use app\models\DcmdUserGroup;
use app\models\DcmdServiceArchDiagramSearch;
use app\models\DcmdServiceArchDiagram;
use app\models\DcmdServicePoolNode;
use app\models\DcmdOprLog;
use app\models\DcmdTaskTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdServiceController implements the CRUD actions for DcmdService model.
 */
class DcmdServiceController extends Controller
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
     * Lists all DcmdService models.
     * @return mixed
     */
    public function actionIndex()
    {
        ///应用足用户只可查看所在组的应用
        $app_con = "";
        if(Yii::$app->user->getIdentity()->admin != 1)
        {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
        }
        $query = DcmdApp::find()->where($app_con)->orderBy('app_name')->asArray()->all();
        $app = array();
        foreach($query as $item) $app[$item['app_id']] = $item['app_name'];


        $searchModel = new DcmdServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'app' => $app,
        ]);
    }

    /**
     * Displays a single DcmdService model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        ///$query = DcmdServicePool::find()->andWhere(['svr_id'=>$id]);
        $searchModel = new DcmdServicePoolSearch();
        $con = array();
        $con['DcmdServicePoolSearch'] = array('svr_id' => $id);
        if(array_key_exists('DcmdServicePoolSearch', Yii::$app->request->queryParams))
          $con = array_merge($con,Yii::$app->request->queryParams);
        $con['DcmdServicePoolSearch']['svr_id'] = $id;
        $dataProvider = $searchModel->search($con);
        ///处理图片
        $service = DcmdService::findOne($id);
        $base_path = dirname(__DIR__)."/web/app_image/";
        $query = DcmdServiceArchDiagram::find()->andWhere(['svr_id'=>$id, 'app_id'=>$service->app_id])->asArray()->all();
        foreach($query as $item) {
          $img_path = $base_path."svr_".$item['arch_name'].'_'.$item['svr_id'].'.jpg';
          if(file_exists($img_path)) continue;
          $fp=fopen($img_path,'wb') or die("Open file $img_path fail!");
          fwrite($fp,stripslashes($item['diagram']));
          fclose($fp);
        }
        $imageSearch = new DcmdServiceArchDiagramSearch();
        $imageProvider = $imageSearch->search(array('DcmdServiceArchDiagramSearch'=>array('app_id'=>$service->app_id, 'svr_id'=>$id)));
        ///获取任务模版列表
        $tmpt_searchModel = new DcmdTaskTemplateSearch();
        $params["DcmdTaskTemplateSearch"]["svr_id"] = $id;
        $taskTemptDataProvider = $tmpt_searchModel->search($params, 1000);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'imageProvider' => $imageProvider,
            'taskTemptDataProvider' => $taskTemptDataProvider,
        ]);
    }

    /**
     * Creates a new DcmdService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
        }
        $model = new DcmdService();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          $model->owner = $model->opr_uid;
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(1, "insert service:".$model->svr_name);
            Yii::$app->getSession()->setFlash('success', '添加成功!'); 
            return $this->redirect(['view', 'id' => $model->svr_id]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }
        $model->app_id = $app_id;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DcmdService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$id));
        }
        if (Yii::$app->request->post()) {
          ///判断节点多池子
          if(Yii::$app->request->post()['DcmdService']['node_multi_pool'] == 0){ ///节点不可为多池子
            $ret = DcmdServicePoolNode::find()->andWhere(['svr_id'=>$id])->asArray()->all();
            $ips = array();
            foreach($ret as $item) {
              if(array_key_exists($item['ip'], $ips)) {
                Yii::$app->getSession()->setFlash('error', "多个池子存在该IP:".$item['ip']);
                return $this->redirect(['dcmd-service/view', 'id' => $id]); 
             }
             $ips[$item['ip']] = 1;
             }
          }
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(2, "modify service:".$model->svr_name);
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['view', 'id' => $model->svr_id]);
          } 
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        }
        return $this->render('update', [
           'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DcmdService model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $app_id=NULL)
    {
      $model = $this->findModel($id);
      ///仅仅用户与该应用在同一个系统组才可以操作
      $temp = DcmdApp::findOne($model['app_id']);
      $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
      if($query==NULL) {
        Yii::$app->getSession()->setFlash('success', NULL);
        Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
        return $this->redirect(array('dcmd-app/view', 'id'=>$model['app_id']));
      }
      $node = DcmdServicePool::find()->where(['svr_id' => $id])->one();
      if($node) {
        Yii::$app->getSession()->setFlash('error', '服务池子不为空,不可删除!');
      }else {
        $this->oprlog(3, "delete svrvice:".$model->svr_name);
        $this->deleteDiagram($id);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
      }
      if ($app_id) {
        return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
      }
      return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择服务!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
           $err_msg .=$model->svr_name.":没有权限删除<br>";
           continue;
        }
        $node = DcmdServicePool::find()->where(['svr_id' => $id])->one();
        if($node) {
          $err_msg .= $model->svr_name.':服务池子不为空,不可删除<br>';
          continue;
        }else { 
          $this->oprlog(3, "delete svrvice:".$model->svr_name);
          $this->deleteDiagram($id);
          $model->delete();
          $suc_msg .=$model->svr_name.':删除成功<br>';
        }
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);

    }

    ///删除应用对应的图片
    private function deleteDiagram($id){
      $query = DcmdServiceArchDiagram::find()->andWhere(['svr_id'=>$id])->asArray()->all();
      foreach($query as $item) {
        ///删除文件
        $base_path = dirname(__DIR__)."/web/app_image/svr_";
        $img_path = $base_path.$item['arch_name'].'_'.$id.'.jpg';
        if(file_exists($img_path)) unlink($img_path);
      }
      DcmdServiceArchDiagram::deleteAll(['svr_id'=>$id]);
    }

    /**
     * Finds the DcmdService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
   private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_service";          
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }
}
