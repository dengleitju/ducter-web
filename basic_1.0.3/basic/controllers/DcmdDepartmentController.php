<?php

namespace app\controllers;

use Yii;
use app\models\DcmdDepartment;
use app\models\DcmdDepartmentSearch;
use app\models\DcmdUser;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdDepartmentController implements the CRUD actions for DcmdDepartment model.
 */
class DcmdDepartmentController extends Controller
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
     * Lists all DcmdDepartment models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        $searchModel = new DcmdDepartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdDepartment model.
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
     * Creates a new DcmdDepartment model.
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
        $model = new DcmdDepartment();
        if(Yii::$app->request->post()) {
          $model->ctime = date('Y-m-d H:i:s',time());
          $model->utime = date('Y-m-d H:i:s',time());
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $this->oprlog(1, "insert department:".$model->depart_name);
             Yii::$app->getSession()->setFlash('success', '添加成功');
             return $this->redirect(['view', 'id' => $model->depart_id]);
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
     * Updates an existing DcmdDepartment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {          
          Yii::$app->getSession()->setFlash('success', NULL);          
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));         
        }
        $model = $this->findModel($id);
        if(Yii::$app->request->post()){
          $model->utime = date('Y-m-d H:i:s',time());
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(2, "update department:".$model->depart_name);
            Yii::$app->getSession()->setFlash('success', '修改成功');
            return $this->redirect(['view', 'id' => $model->depart_id]);
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
     * Deletes an existing DcmdDepartment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        $query = DcmdUser::find()->andWhere(['depart_id'=>$id])->asArray()->all();
        if(count($query) != 0) {
          Yii::$app->getSession()->setFlash('error', "该部门下用户不为空!");
          return $this->redirect(['index']);
        }
        $model = $this->findModel($id);
        $this->oprlog(3, "delete department:".$model->depart_name);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdDepartment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdDepartment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdDepartment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    private function oprlog($opr_type, $sql) {
      $opr_log = new DcmdOprLog();
      $opr_log->log_table = "dcmd_department";          
      $opr_log->opr_type = $opr_type;
      $opr_log->sql_statement = $sql;
      $opr_log->ctime = date('Y-m-d H:i:s');
      $opr_log->opr_uid = Yii::$app->user->getId();
      $opr_log->save();
    }
}
