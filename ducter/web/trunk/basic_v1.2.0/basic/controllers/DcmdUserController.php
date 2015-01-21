<?php

namespace app\controllers;

use Yii;
use app\models\DcmdUser;
use app\models\DcmdUserGroup;
use app\models\DcmdDepartment;
use app\models\DcmdUserSearch;
use app\models\DcmdGroupSearch;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdUserController implements the CRUD actions for DcmdUser model.
 */
class DcmdUserController extends Controller
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
     * Lists all DcmdUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        $searchModel = new DcmdUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdUser model.
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
        $query = DcmdUserGroup::find()->andWhere("uid=".$id)->asArray()->all();
        $gids = "gid in (0";///array(0,); 
        foreach($query as $item) $gids = $gids.",".$item['gid'];////array_push($gids ,$item['gid']);
        $gids = $gids.")";
        $searchModel = new DcmdGroupSearch();
        $dataProvider = $searchModel->search(array(), $gids);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        $model = new DcmdUser();
        $ret = DcmdDepartment::find()->asArray()->all();
        $depart = array();
        foreach($ret as $item) {
         $depart[$item['depart_id']] = $item['depart_name'];
        }
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->state = 0;
          $model->passwd = md5("123456"+Yii::$app->request->post()["DcmdUser"]["username"]+$model->ctime);
          $model->opr_uid = Yii::$app->user->getId();
          //Yii::$app->getSession()->setFlash('success', '添加用户成功,初始密码:123456');
          if ($model->load(Yii::$app->request->post())) {
            if($model->admin != 1) $model->sa = 0;
            if( $model->save()) {
             Yii::$app->getSession()->setFlash('success', '添加用户成功,初始密码:123456');
             $this->oprlog(1,"add user:".$model->username);
             return $this->redirect(['view', 'id' => $model->uid, 'depart'=>$depart]);
            }
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        } 
        return $this->render('create', [
             'model' => $model,
             'depart' => $depart,
        ]);
    }

    /**
     * Updates an existing DcmdUser model.
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
        $ret = DcmdDepartment::find()->asArray()->all();
        $depart = array();
        foreach($ret as $item) {
         $depart[$item['depart_id']] = $item['depart_name'];
        }
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post())) {
            if($model->admin != 1) $model->sa = 0;
            if( $model->save()) {
              $this->oprlog(2,"update user:".$model->username);
              Yii::$app->getSession()->setFlash('success', "修改成功");
              return $this->redirect(['view', 'id' => $model->uid]);
            }
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败失败:".$err_str);
        }
        return $this->render('update', [
              'model' => $model,
              'depart' => $depart,
        ]);
    }
 
    public function actionChangePasswd()
    {
       if(Yii::$app->request->post()) {
         $old_passwd = md5(Yii::$app->request->post()['oldpasswd']+Yii::$app->user->getIdentity()->username+Yii::$app->user->getIdentity()->ctime);
         $new_passwd =  md5(Yii::$app->request->post()['newpasswd']+Yii::$app->user->getIdentity()->username+Yii::$app->user->getIdentity()->ctime);
         if(Yii::$app->request->post()['newpasswd'] != Yii::$app->request->post()['repeat_newpasswd']) {
           Yii::$app->getSession()->setFlash('error','两次新密码不同!');
         }else if($old_passwd != Yii::$app->user->getIdentity()->password){
           Yii::$app->getSession()->setFlash('error','旧密码输入错误!');
         }else if($new_passwd == $old_passwd) {
           Yii::$app->getSession()->setFlash('error','新旧密码不可相同!');
         }else{
           $model = DcmdUser::findOne(Yii::$app->user->getId());
           $model->passwd = $new_passwd;
           $this->oprlog(2,"change password user:".$model->username);
           $model->save();
           Yii::$app->getSession()->setFlash('success', '密码修改成功!');
         }
       }
       $model = $this->findModel(Yii::$app->user->getId()); 
       return $this->render('change_passwd', [
          'model' => $model,          
       ]);
    }

    /**
     * Deletes an existing DcmdUser model.
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
        ///delete from dcmd_user_group
        DcmdUserGroup::deleteAll("uid=".$id);
        $model = $this->findModel($id);
        $this->oprlog(3,"delete user:".$model->username);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

   private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_user";          
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }
}
