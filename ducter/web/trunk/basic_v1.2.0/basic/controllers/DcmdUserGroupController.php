<?php

namespace app\controllers;

use Yii;
use app\models\DcmdUserGroup;
use app\models\DcmdUserSearch;
use app\models\DcmdGroup;
use app\models\DcmdUserGroupSearch;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdUserGroupController implements the CRUD actions for DcmdUserGroup model.
 */
class DcmdUserGroupController extends Controller
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
     * Lists all DcmdUserGroup models.
     * @return mixed
     */
    public function actionIndex($gid)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        $searchModel = new DcmdUserGroupSearch(array('gid'=>$gid));
        $dataProvider = $searchModel->search(array('gid'=>$gid));///Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gid' => $gid,
            'gname' => $this->getGroupName($gid),
        ]);
    }
   
    /**
     * Displays a single DcmdUserGroup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DcmdUserGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($gid)
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-group/index', ['id'=>$gid]));
        }
        $query = DcmdUserGroup::find()->andWhere(["gid"=>$gid,])->asArray()->all();
        $uids = "uid not in (0";
        foreach($query as $item) $uids .=",".$item['uid'];
        $uids .= ")";
        $query = DcmdGroup::findOne($gid);
        if ($query['gtype'] == 1) $uids .= " and admin = 1";
        else $uids .= " and admin = 0";
        
        $searchModel = new DcmdUserSearch();
        $dataProvider = $searchModel->search(array(), $uids);

        return $this->render('create', [
             'gid' => Yii::$app->request->queryParams['gid'],
             'gname' => Yii::$app->request->queryParams['gname'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
 
    }

    public function actionAdd() {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('index'));
        }
        $gid = Yii::$app->request->post()["gid"];
	$gname =  Yii::$app->request->post()["gname"];
        $uid_array = array();
        $success_msg = "未选择用户!";
        if (array_key_exists("selection", Yii::$app->request->post())){ 
           $success_msg = "添加用户成功:";  
           $tm =  date('Y-m-d H:i:s');
           foreach(Yii::$app->request->post()["selection"] as $k=>$v) {
             $dcmd_user_group = new DcmdUserGroup();
             $dcmd_user_group->uid = $v;
             $dcmd_user_group->gid = $gid;
             $dcmd_user_group->comment = "comment";
             $dcmd_user_group->utime = $tm;
             $dcmd_user_group->ctime = $tm;
             $dcmd_user_group->opr_uid = Yii::$app->user->getId();
             $dcmd_user_group->save();
             $success_msg .= $v." ";
             $this->oprlog(1, "add user:$v group:$gid");
           }
        }
        Yii::$app->getSession()->setFlash('success', $success_msg);
        return $this->redirect(array('dcmd-group/view','id'=>$gid, 'show_div'=>'dcmd-group-user')); 
    }
    /**
     * Updates an existing DcmdUserGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('index'));
        }
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdUserGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('index'));
        }
        $model=$this->findModel($id);
        $this->oprlog(3,"delete uid:$model->uid gid:$model->gid");
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }

    public function actionRemove()
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起,你没有权限!");
          return $this->redirect(array('index'));
        }
      $gid = Yii::$app->request->queryParams["gid"];
      $this->findModel(Yii::$app->request->queryParams["id"])->delete();
      Yii::$app->getSession()->setFlash('success', '删除成功!');
      return $this->redirect(['dcmd-group/view', 'id'=>$gid, 'show_div'=>'dcmd-group-user']); ///$this->redirect(['index', 'gid'=>$gid]);
    }
    /**
     * Finds the DcmdUserGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdUserGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdUserGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
 
   protected function getGroupName($gid) 
   {
     $query = DcmdGroup::findOne($gid);
     if($query) return $query['gname'];
     return $gid;
   }
   private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_user_group";          
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }
}
