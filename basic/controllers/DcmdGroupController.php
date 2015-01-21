<?php

namespace app\controllers;

use Yii;
use app\models\DcmdGroup;
use app\models\DcmdGroupCmd;
use app\models\DcmdGroupRepeatCmd;
use app\models\DcmdUserGroup;
use app\models\DcmdGroupSearch;
use app\models\DcmdUserGroupSearch;
use app\models\DcmdUserSearch;
use app\models\DcmdGroupCmdSearch;
use app\models\DcmdGroupRepeatCmdSearch;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdGroupController implements the CRUD actions for DcmdGroup model.
 */
class DcmdGroupController extends Controller
{
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
     * Lists all DcmdGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdGroup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
       /* $query = DcmdUserGroup::find()->andWhere("gid=".$id)->asArray()->all();
        $users = "uid in (0"; 
        foreach($query as $item) $users = $users.",".$item['uid'];
        $users .= ")";
        $searchModel = new DcmdUserSearch();
        $dataProvider = $searchModel->search(array(), $users);
*/
        $group_model = $this->findModel($id);
        $show_div = "dcmd_group";
        if(array_key_exists('show_div', Yii::$app->request->queryParams))
          $show_div = Yii::$app->request->queryParams['show_div'];
        ///显示组用户标签
        $user_searchModel = new DcmdUserGroupSearch();
        $user_dataProvider = $user_searchModel->search(array('gid'=>$id));
        ///组操作
        $cmd_searchModel = new DcmdGroupCmdSearch();
        $params = array("DcmdGroupCmdSearch"=>array("gid"=>$id));
        $cmd_dataProvider = $cmd_searchModel->search($params);
        ///重复操作
        $repeat_cmd_searchModel = new DcmdGroupRepeatCmdSearch();
        $params = array("DcmdGroupRepeatCmdSearch"=>array("gid"=>$id));
        $repeat_cmd_dataProvider = $repeat_cmd_searchModel->search($params);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'user_searchModel' => $user_searchModel,
            'user_dataProvider' => $user_dataProvider,
            'cmd_dataProvider' => $cmd_dataProvider,
            'repeat_cmd_dataProvider' => $repeat_cmd_dataProvider,
            'gid' => $id,
            'show_div' => $show_div,
            'is_sys' => $group_model->gtype==1?"none":"",
        ]);
    }

    /**
     * Creates a new DcmdGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "Sorry, You don't have priority!");
          return $this->redirect(array('index'));
        }
        $model = new DcmdGroup();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $this->oprlog(1, "insert group:".$model->gname);
             Yii::$app->getSession()->setFlash('success', '添加成功!');
             return $this->redirect(['view', 'id' => $model->gid]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
        }
        return $this->render('create', [
           'model' => $model,
        ]);
    }

    /**
     * Updates an existing DcmdGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "Sorry, You don't have priority!");
          return $this->redirect(array('index'));
        }
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(2, "update group:".$model->gname);
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['view', 'id' => $model->gid]);
           }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
         }
         return $this->render('update', [
              'model' => $model,
         ]);
    }

    /**
     * Deletes an existing DcmdGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /*$node = DcmdUserGroup::find()->where(['gid' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '用户组内有用户,不可删除!');
        }else {
          $this->findModel($id)->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }*/
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "Sorry, You don't have priority!");
          return $this->redirect(array('index'));
        }
        DcmdUserGroup::deleteAll("gid=".$id);
        DcmdGroupCmd::deleteAll('gid='.$id);
        DcmdGroupRepeatCmd::deleteAll('gid='.$id);
        $model=$this->findModel($id);
        $this->oprlog(3,"delete group:".$model->gname);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }
  
    private function actionUpdateUser($id)
    {

    }

    /**
     * Finds the DcmdGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function oprlog($opr_type, $sql) {
      $opr_log = new DcmdOprLog();
      $opr_log->log_table = "dcmd_group";          
      $opr_log->opr_type = $opr_type;
      $opr_log->sql_statement = $sql;
      $opr_log->ctime = date('Y-m-d H:i:s');
      $opr_log->opr_uid = Yii::$app->user->getId();
      $opr_log->save();
    }
}
