<?php

namespace app\controllers;

use Yii;
use app\models\DcmdTaskCmd;
use app\models\DcmdTaskCmdArg;
use app\models\DcmdTaskTemplate;
use app\models\DcmdTaskCmdSearch;
use app\models\DcmdCenter;
use app\models\DcmdTaskCmdArgSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * DcmdTaskCmdController implements the CRUD actions for DcmdTaskCmd model.
 */
class DcmdTaskCmdController extends Controller
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
     * Lists all DcmdTaskCmd models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdTaskCmdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdTaskCmd model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new DcmdTaskCmdArgSearch();
        $params = array("DcmdTaskCmdArgSearch"=>array("task_cmd_id" => $id),);
        $dataProvider = $searchModel->search($params);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdTaskCmd model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-task-cmd/index'));
        }
        $model = new DcmdTaskCmd();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          if($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', "添加成功!");
            return $this->redirect(['view', 'id' => $model->task_cmd_id]);
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
     * Updates an existing DcmdTaskCmd model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-task-cmd/view', 'id'=>$id));
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->task_cmd_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdTaskCmd model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-task-cmd/index'));
        }
        ///确定是否有任务模板使用
        $ret = DcmdTaskTemplate::findOne(['task_cmd_id'=>$id]);
        if($ret) {
          Yii::$app->getSession()->setFlash('error', '有任务模板使用该脚本, 删除失败!');
          return $this->redirect(['index']);
        }
        ///delete from dcmd_task_cmd_arg
        DcmdTaskCmdArg::deleteAll('task_cmd_id = '.$id);
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }

    public function actionLoadContent()
    {
      $task_cmd = Yii::$app->request->post()["task_cmd"];
      $query = DcmdCenter::findOne(['master'=>1]);
      $retcontent = array("md5"=>"",);
      
      if ($query) {
          list($ip, $port) = explode(':', $query["host"]);
          $reply = getTaskScriptInfo($ip, $port, $task_cmd);
          if ($reply->getState() == 0) {
            $this->saveScript($task_cmd, $reply->getScript());
            $retContent["result"] = str_replace("\n", "<br/>",$reply->getScript());
            $retContent["md5"] = $reply->getMd5(); 
          }else{
            $retContent["result"] =  str_replace("\n", "<br/>",$reply->getErr());
          }
      }else {
        $retContent["result"]="Not found master center.";
      }
      echo json_encode($retContent);
      exit; 
    }

    private function saveScript($task_cmd, $content) {
      $base_path = dirname(__DIR__)."/web/app_image/";
      $script_path = $base_path."dcmd_task_".$task_cmd.'.script';
      $fp=fopen($script_path,'wb');
      fwrite($fp,$content);
      fclose($fp);
    }

    public function actionGetTaskList()
    {
      $prefix = Yii::$app->request->post()["prefix"];
      $query = DcmdCenter::findOne(['master'=>1]);
      $retContent = "";
      if ($query) {
          list($ip, $port) = explode(':', $query["host"]);
          $reply = getTaskScriptList($ip, $port, $prefix);
          if ($reply->getState() == 0) {
            foreach($reply->getScripts() as $item)
             $retContent .=$item.";";
          }
      }
      echo $retContent;
      exit;
    }

    /**
     * Finds the DcmdTaskCmd model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdTaskCmd the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdTaskCmd::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
