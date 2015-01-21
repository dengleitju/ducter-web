<?php

namespace app\controllers;

use Yii;
use app\models\DcmdServicePoolAttr;
use app\models\DcmdServicePoolAttrSearch;
use app\models\DcmdServicePoolAttrDef;
use app\models\DcmdServicePool;
use app\models\DcmdApp;
use app\models\DcmdUserGroup;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdServicePoolAttrController implements the CRUD actions for DcmdServicePoolAttr model.
 */
class DcmdServicePoolAttrController extends Controller
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
     * Lists all DcmdServicePoolAttr models.
     * @return mixed
     */
    private function actionIndex()
    {
        $searchModel = new DcmdServicePoolAttrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdServicePoolAttr model.
     * @param integer $id
     * @return mixed
     */
    private function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DcmdServicePoolAttr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    private function actionCreate()
    {
        $model = new DcmdServicePoolAttr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DcmdServicePoolAttr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $svr_pool_id)
    {
        $svr_pool = DcmdServicePool::findOne($svr_pool_id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($svr_pool->app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$svr_pool_id, 'show_div'=>'dcmd-service-pool-attr'));
        }
        if($id == 0) { ///需要新建
          $attr_id = Yii::$app->request->queryParams['attr_id'];
          $def_attr = DcmdServicePoolAttrDef::findOne($attr_id);
          $query = DcmdServicePoolAttr::findOne(['svr_pool_id'=>$svr_pool_id, 'attr_name'=>$def_attr->attr_name]);
          if(count($query) > 0) {
            $id = $query['id'];
          }else{
            $model = new DcmdServicePoolAttr();
            $model->app_id = $svr_pool->app_id;
            $model->svr_id = $svr_pool->svr_id;
            $model->svr_pool_id = $svr_pool_id;
            $model->attr_name = $def_attr->attr_name;
            $model->attr_value = $def_attr->def_value;
            $model->comment = $def_attr->comment;
            $model->utime = date('Y-m-d H:i:s');
            $model->ctime = $model->utime;
            $model->opr_uid = Yii::$app->user->getId();
            $model->save();
            $id = $model->id;
          }
        }

        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['/dcmd-service-pool/view', 'id'=>$model->svr_pool_id, 'show_div'=>'dcmd-service-pool-attr']);;
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdServicePoolAttr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdServicePoolAttr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdServicePoolAttr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdServicePoolAttr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
