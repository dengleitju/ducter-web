<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNodeGroupAttrDef;
use app\models\DcmdNodeGroupAttrDefSearch;
use app\models\DcmdNodeGroupAttr;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdNodeGroupAttrDefController implements the CRUD actions for DcmdNodeGroupAttrDef model.
 */
class DcmdNodeGroupAttrDefController extends Controller
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
     * Lists all DcmdNodeGroupAttrDef models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdNodeGroupAttrDefSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdNodeGroupAttrDef model.
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
     * Creates a new DcmdNodeGroupAttrDef model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->getIdentity()->admin != 1 ){
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        $model = new DcmdNodeGroupAttrDef();

        if(Yii::$app->request->post()) {
          $model->ctime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
        }
 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '添加成功');
            return $this->redirect(['view', 'id' => $model->attr_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DcmdNodeGroupAttrDef model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1 ){
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '更改成功');
            return $this->redirect(['view', 'id' => $model->attr_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdNodeGroupAttrDef model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1 ){
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        ///从设备组中删除
        $model = $this->findModel($id);
        DcmdNodeGroupAttr::deleteAll(['attr_name'=>$model->attr_name]);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', "删除成功");
        return $this->redirect(['index']);
    }
    public function actionDeleteAll()
    {
        if(Yii::$app->user->getIdentity()->admin != 1 ){
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        if(!array_key_exists('selection', Yii::$app->request->post())) {
          Yii::$app->getSession()->setFlash('error', '未选择属性!');
          return $this->redirect(['index']);
        }
        $select = Yii::$app->request->post()['selection'];
        foreach($select as $k=>$v) {
          $model = $this->findModel($v);
          DcmdNodeGroupAttr::deleteAll(['attr_name'=>$model->attr_name]);
          $model->delete();
        }
        Yii::$app->getSession()->setFlash('success', "删除成功");
        return $this->redirect(['index']);
    }
    /**
     * Finds the DcmdNodeGroupAttrDef model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdNodeGroupAttrDef the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdNodeGroupAttrDef::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
