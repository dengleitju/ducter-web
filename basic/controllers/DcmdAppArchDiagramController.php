<?php

namespace app\controllers;

use Yii;
use app\models\DcmdAppArchDiagram;
use app\models\DcmdAppArchDiagramSearch;
use app\models\DcmdApp;
use app\models\DcmdUserGroup;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdAppArchDiagramController implements the CRUD actions for DcmdAppArchDiagram model.
 */
class DcmdAppArchDiagramController extends Controller
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
     * Lists all DcmdAppArchDiagram models.
     * @return mixed
     */
    private function actionIndex()
    {
        $searchModel = new DcmdAppArchDiagramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single DcmdAppArchDiagram model.
     * @param integer $id
     * @return mixed
     */
    private function actionView($id)
    {
echo dirname(__DIR__);exit;
        $model = $this->findModel($id);
        echo $model->diagram;exit;
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DcmdAppArchDiagram model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id)
    {
        $model = new DcmdAppArchDiagram();
        $app_query = DcmdApp::findOne($app_id);
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$app_query['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/view', 'id'=>$app_id));
        }

        if($model->load(Yii::$app->request->post())) {
          $image_name = Yii::$app->request->post()['DcmdAppArchDiagram']['arch_name'];
          $comment = Yii::$app->request->post()['DcmdAppArchDiagram']['comment'];
          if($image_name == "") {
            Yii::$app->getSession()->setFlash('error', '图片名不可为空!');
            return  $this->render('create', ['model' => $model,'app_name'=>$app_query['app_name'], 'app_id'=>$app_id]);
          }
          $query = DcmdAppArchDiagram::findOne(['app_id'=>$app_id, 'arch_name'=>$image_name]);
          if($query) {
            Yii::$app->getSession()->setFlash('error', '图片名已经存在!');
            return  $this->render('create', ['model' => $model, 'app_name'=>$app_query['app_name'],'app_id'=>$app_id,]);
          }
          $file_name = $_FILES["DcmdAppArchDiagram"]["name"]["arch_name"];
          if(strpos($file_name, ".jpg") <= 0) {
            Yii::$app->getSession()->setFlash('error', '只可上传jpg文件!');
            return  $this->render('create', ['model' => $model,'app_name'=>$app_query['app_name'],'app_id'=>$app_id,]);
          }
          if(!file_exists($_FILES["DcmdAppArchDiagram"]["tmp_name"]['arch_name'])){
            Yii::$app->getSession()->setFlash('error', '未选择架构图!');
            return  $this->render('create', ['model' => $model,'app_name'=>$app_query['app_name'],'app_id'=>$app_id,]); 
          }
          $model->app_id = $app_id;
          $model->arch_name = $image_name;
          $size = filesize($_FILES['DcmdAppArchDiagram']['tmp_name']['arch_name']);
          $file = fopen($_FILES["DcmdAppArchDiagram"]["tmp_name"]['arch_name'], 'rb');
          $model->diagram = addslashes(fread($file, $size));
          fclose($file);
          $model->comment = $comment;
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          if($model->save()) {
            $opr_log = new DcmdOprLog();
            $opr_log->log_table = "dcmd_app_arch_diagram";
            $opr_log->opr_type = 1;
            $opr_log->sql_statement = "app_id:$app_id, arch_name:$image_name";
            $opr_log->ctime = date('Y-m-d H:i:s'); 
            $opr_log->opr_uid = Yii::$app->user->getId();
            $opr_log->save();
            Yii::$app->getSession()->setFlash('success', '添加成功');
            return $this->redirect(['dcmd-app/view', 'id'=>$app_id]);
          } 
          $err_msg = "";
          foreach($model->getErrors() as $item) {
            foreach($item as $k=>$v) $err_msg .=$v." ";
          } echo $err_msg; exit;
          Yii::$app->getSession()->setFlash('error', '添加数据库失败:'.$err_msg);
          return  $this->render('create', ['model' => $model, 'app_id'=>$app_id, 'app_name'=>$app_query['app_name']]);
        }
        else {
            return $this->render('create', [
                'model' => $model, 
                'app_id'=>$app_id,
                'app_name'=>$app_query['app_name'],
            ]);
        }
    }

    /**
     * Updates an existing DcmdAppArchDiagram model.
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
     * Deletes an existing DcmdAppArchDiagram model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $app_query = DcmdApp::findOne($model->app_id); 
        ///判断用户所属的系统组是否和该应用相同
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$app_query['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-app/view', 'id'=>$model->app_id));
        }

        $app_id = $model->app_id;
        $arch_name = $model->arch_name;
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        ///删除文件
        $base_path = dirname(__DIR__)."/web/app_image/app_";
        $img_path = $base_path.$arch_name.'_'.$app_id.'.jpg';
        if(file_exists($img_path)) unlink($img_path);
        $opr_log = new DcmdOprLog();
        $opr_log->log_table = "dcmd_app_arch_diagram";          
        $opr_log->opr_type = 3;
        $opr_log->sql_statement = "delete arch_name:$arch_name";     
        $opr_log->ctime = date('Y-m-d H:i:s');
        $opr_log->opr_uid = Yii::$app->user->getId();
        $opr_log->save();
        return $this->redirect(['dcmd-app/view', 'id'=>$app_id]);
    }

    /**
     * Finds the DcmdAppArchDiagram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdAppArchDiagram the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdAppArchDiagram::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
