<?php

namespace app\controllers;

use Yii;
use app\models\DcmdOprCmdArg;
use app\models\DcmdOprCmdArgSearch;
use app\models\DcmdOprCmd;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdOprCmdArgController implements the CRUD actions for DcmdOprCmdArg model.
 */
class DcmdOprCmdArgController extends Controller
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
     * Lists all DcmdOprCmdArg models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdOprCmdArgSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdOprCmdArg model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DcmdOprCmdArg model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($opr_cmd_id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(['dcmd-opr-cmd/view', 'id'=>$opr_cmd_id]);
        }
        $opr = DcmdOprCmd::findOne($opr_cmd_id);
        $model = new DcmdOprCmdArg();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          $model->opr_cmd_id = $opr_cmd_id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '添加成功!');
            return $this->redirect(['dcmd-opr-cmd/view', 'id' => $opr_cmd_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'opr' => $opr,
            ]);
        }
    }

    /**
     * Updates an existing DcmdOprCmdArg model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(['dcmd-opr-cmd/view', 'id'=>$model['opr_cmd_id']]);
        }
        $opr = DcmdOprCmd::findOne($model['opr_cmd_id']);
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['dcmd-opr-cmd/view', 'id' => $model['opr_cmd_id']]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'opr' => $opr,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdOprCmdArg model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        $opr_cmd_id = $model['opr_cmd_id'];
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(['dcmd-opr-cmd/view', 'id'=>$opr_cmd_id]);
        }
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['dcmd-opr-cmd/view', 'id'=>$opr_cmd_id]);
    }

    /**
     * Finds the DcmdOprCmdArg model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdOprCmdArg the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdOprCmdArg::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
