<?php

namespace app\controllers;

use Yii;
use app\models\DcmdServiceArchDiagram;
use app\models\DcmdServiceArchDiagramSearch;
use app\models\DcmdService;
use app\models\DcmdApp;
use app\models\DcmdUserGroup;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdServiceArchDiagramController implements the CRUD actions for DcmdServiceArchDiagram model.
 */
class DcmdServiceArchDiagramController extends Controller
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
     * Lists all DcmdServiceArchDiagram models.
     * @return mixed
     */
    private function actionIndex()
    {
        $searchModel = new DcmdServiceArchDiagramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdServiceArchDiagram model.
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
     * Creates a new DcmdServiceArchDiagram model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($svr_id)
    {
        $model = new DcmdServiceArchDiagram();
        $service = DcmdService::findOne($svr_id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($service['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }

        if(Yii::$app->request->post()) {
          $image_name = Yii::$app->request->post()['DcmdServiceArchDiagram']['arch_name'];
          $comment = Yii::$app->request->post()['DcmdServiceArchDiagram']['comment'];
          if($image_name == "") {
            Yii::$app->getSession()->setFlash('error', '图片名不可为空!');
            return  $this->render('create', ['model' => $model, 'service'=>$service]);
          }
          $query = DcmdServiceArchDiagram::findOne(['svr_id'=>$svr_id, 'arch_name'=>$image_name]);
          if($query) {
            Yii::$app->getSession()->setFlash('error', '图片名已经存在!');
            return  $this->render('create', ['model' => $model, 'service'=>$service]);
          }
          $file_name = $_FILES["DcmdServiceArchDiagram"]["name"]["arch_name"];
          if(strpos($file_name, ".jpg") <= 0) {
            Yii::$app->getSession()->setFlash('error', '只可上传jpg文件!');
            return  $this->render('create', ['model' => $model, 'service'=>$service]);
          }
          if(!file_exists($_FILES["DcmdServiceArchDiagram"]["tmp_name"]['arch_name'])){
            Yii::$app->getSession()->setFlash('error', '未选择架构图!');
            return  $this->render('create', ['model' => $model, 'service'=>$service]);
          }
          $model->app_id = $service->app_id;
          $model->svr_id = $svr_id;
          $model->arch_name = $image_name;
          $size = filesize($_FILES['DcmdServiceArchDiagram']['tmp_name']['arch_name']);
          $file = fopen($_FILES["DcmdServiceArchDiagram"]["tmp_name"]['arch_name'], 'rb');
          $model->diagram = addslashes(fread($file, $size));
          fclose($file);
          $model->comment = $comment;
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          if($model->save()) {
            $this->oprlog(1, "insert arch_name:".$model->arch_name);
            Yii::$app->getSession()->setFlash('success', '添加成功');
            return $this->redirect(['dcmd-service/view', 'id'=>$svr_id]);
          }
          $err_msg = "";
          foreach($model->getErrors() as $item) {
            foreach($item as $k=>$v) $err_msg .=$v." ";
          } echo $err_msg; exit;
          Yii::$app->getSession()->setFlash('error', '添加数据库失败:'.$err_msg);
          return  $this->render('create', ['model' => $model, 'service'=>$service]);
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'service'=>$service,
            ]);
        }
    }

    /**
     * Updates an existing DcmdServiceArchDiagram model.
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
     * Deletes an existing DcmdServiceArchDiagram model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model->app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$model->svr_id));
        }
        $app_id = $model->app_id;
        $svr_id = $model->svr_id;
        $arch_name = $model->arch_name;
        $this->oprlog(3, "delete arch:".$arch_name);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        ///删除文件
        $base_path = dirname(__DIR__)."/web/app_image/svr_";
        $img_path = $base_path.$arch_name.'_'.$svr_id.'.jpg';
        if(file_exists($img_path)) unlink($img_path);
        return $this->redirect(['dcmd-service/view', 'id'=>$svr_id]);
    }

    /**
     * Finds the DcmdServiceArchDiagram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdServiceArchDiagram the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdServiceArchDiagram::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    private function oprlog($opr_type, $sql) {
      $opr_log = new DcmdOprLog();
      $opr_log->log_table = "dcmd_service_arch_diagram";          
      $opr_log->opr_type = $opr_type;
      $opr_log->sql_statement = $sql;
      $opr_log->ctime = date('Y-m-d H:i:s');
      $opr_log->opr_uid = Yii::$app->user->getId();
      $opr_log->save();
   }
}
