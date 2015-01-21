<?php

namespace app\controllers;

use Yii;
use app\models\DcmdTaskServicePool;
use app\models\DcmdTaskServicePoolSearch;
use app\models\DcmdTask;
use app\models\DcmdServicePool;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdTaskServicePoolController implements the CRUD actions for DcmdTaskServicePool model.
 */
class DcmdTaskServicePoolController extends Controller
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
     * Lists all DcmdTaskServicePool models.
     * @return mixed
     */
    public function actionIndex($task_id)
    {
        $task = DcmdTask::findOne($task_id);
        $searchModel = new DcmdTaskServicePoolSearch();
        $params = array('DcmdTaskServicePoolSearch'=>array());
        if(array_key_exists("DcmdTaskServicePoolSearch", Yii::$app->request->queryParams)) 
          $params['DcmdTaskServicePoolSearch'] = Yii::$app->request->queryParams['DcmdTaskServicePoolSearch'];
        $params['DcmdTaskServicePoolSearch']['task_id'] = $task_id;
        $dataProvider = $searchModel->search($params);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task' => $task,
        ]);
    }

    /**
     * Displays a single DcmdTaskServicePool model.
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
     * Creates a new DcmdTaskServicePool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_id)
    {
       $task = DcmdTask::findOne($task_id); 
        
       if (Yii::$app->request->post()) {
         $model = new DcmdTaskServicePool();

       }else {
         return $this->render('create', ['task' => $task, 'svr_pool'=>$svr_pool]);
       }

        /*if ($model->load(Yii::$app->request->post()) && $model->save()) {
        } else {
            return $this->render('create', [
                'model' => NULL,
                'task' => $task,
            ]);
        }*/
    }

    /**
     * Updates an existing DcmdTaskServicePool model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    private function actionUpdate($id)
    {
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
     * Deletes an existing DcmdTaskServicePool model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    private function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdTaskServicePool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdTaskServicePool the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdTaskServicePool::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
