<?php

namespace app\controllers;

use Yii;
use app\models\DcmdTaskTemplate;
use app\models\DcmdApp;
use app\models\DcmdUserGroup;
use app\models\DcmdTaskCmdArg;
use app\models\DcmdTaskCmd;
use app\models\DcmdTaskTemplateServicePool;
use app\models\DcmdTaskTemplateSearch;
use app\models\DcmdTaskTemplateServicePoolSearch;
use app\models\DcmdService;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
include dirname(__FILE__)."/../common/dcmd_util_func.php";


/**
 * DcmdTaskTemplateController implements the CRUD actions for DcmdTaskTemplate model.
 */
class DcmdTaskTemplateController extends Controller
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
     * Lists all DcmdTaskTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = array();
        if(array_key_exists('DcmdTaskTemplateSearch', Yii::$app->request->queryParams)) {
          $params['DcmdTaskTemplateSearch'] = Yii::$app->request->queryParams['DcmdTaskTemplateSearch'];
          if($params['DcmdTaskTemplateSearch']['app_id'] == "")
            $params['DcmdTaskTemplateSearch']['svr_id'] = "";
        }
        ///$searchModel = new DcmdTaskTemplateSearch();
        ///$dataProvider = $searchModel->search($params);
        $task_cmd = array();
        $query = DcmdTaskCmd::find()->asArray()->all();
        if($query) {
          foreach($query as $item) $task_cmd[$item['task_cmd_id']] = $item['task_cmd'];
        }
        $app = array();
        $ap_con = "";
        if(Yii::$app->user->getIdentity()->admin != 1) {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
          $query = DcmdApp::find()->where($app_con)->asArray()->all();
          $ap_con = "app_id in (0";
          foreach($query as $item) $ap_con .=",".$item['app_id'];
          $ap_con .=")";
        }
        $query = DcmdApp::find()->andWhere($ap_con)->asArray()->all();
        if($query) {
          foreach($query as $item) $app[$item['app_id']] = $item['app_name'];
        }
        $service = array();
        if(array_key_exists('DcmdTaskTemplateSearch', $params) && 
           array_key_exists('app_id' ,  $params['DcmdTaskTemplateSearch'])) {
          $query = DcmdService::find()->andWhere(['app_id'=>$params['DcmdTaskTemplateSearch']['app_id']])->asArray()->all();
          if($query) {
            foreach($query as $item) $service[$item['svr_id']] = $item['svr_name'];
          }
          if(!array_key_exists($params['DcmdTaskTemplateSearch']['svr_id'], $service))
             $params['DcmdTaskTemplateSearch']['svr_id'] = "";
        }
       
        $searchModel = new DcmdTaskTemplateSearch();
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_cmd' => $task_cmd,
            'app' => $app,
            'service' => $service,
        ]);
    }

    /**
     * Displays a single DcmdTaskTemplate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $arg_content = $this->actionGetTaskTypeArg($model->task_cmd_id, xmltoarray($model->task_arg), "disabled"); 
        $searchModel = new DcmdTaskTemplateServicePoolSearch();
        $params = array("DcmdTaskTemplateServicePoolSearch"=>array('task_tmpt_id'=>$id));
        $dataProvider = $searchModel->search($params);
        return $this->render('view', [
            'model' => $model,
            'arg_content' => $arg_content,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateBySvr($app_id, $svr_id) {
        ///只有管理员可以
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        ///检查是否为一个系统组
        $dcmd_app = DcmdApp::findOne($app_id);
        $temp = DcmdUserGroup::find()->andWhere(['uid'=>Yii::$app->user->getId(), 'gid'=>$dcmd_app->sa_gid])->asArray()->all();;
        if(count($temp) == 0) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $model = new DcmdTaskTemplate();
        if (Yii::$app->request->post()) { 
          $model->load(Yii::$app->request->post());
          $query = DcmdService::findOne($model->svr_id);
          $model->svr_name = $query['svr_name'];
          $query = DcmdTaskCmd::findOne($model->task_cmd_id); ////Yii::$app->request->post()['task_cmd_id']);
          $model->task_cmd = $query['task_cmd'];
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          $arg = array();
          foreach(Yii::$app->request->post() as $k=>$v) {
            if(substr($k,0,3) == "Arg") $arg[substr($k,3)] = $v;
          }
          $model->task_arg = arrToXml($arg);
          if($model->save()) {
            Yii::$app->getSession()->setFlash('success',"添加成功!");
            $this->oprlog(1, "insert task template:".$model->task_tmpt_name);
            return $this->redirect(['view', 'id' => $model->task_tmpt_id]);
          }else {
            Yii::$app->getSession()->setFlash('error',"添加失败!");
          }
        }
        
        $dcmd_svr = DcmdService::findOne($svr_id);
        ///获取任务脚本
        $query = DcmdTaskCmd::find()->asArray()->all();
        $task_cmd = array(""=>"");
        if($query) {
          foreach($query as $item) $task_cmd[$item['task_cmd_id']] = $item['ui_name'];
        }
        return $this->render('create_by_svr', [
             'model' => $model,
             'app' => array($app_id=>$dcmd_app->app_name),
             'svr' => array($svr_id=>$dcmd_svr->svr_name),
             'task_cmd' => $task_cmd,
        ]);
    }
    /**
     * Creates a new DcmdTaskTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        ///只有管理员可以
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-task-template/index'));
        }
        $model = new DcmdTaskTemplate();
        if (Yii::$app->request->post()) {  ///保存并返回
          $model->load(Yii::$app->request->post());
          $query = DcmdService::findOne($model->svr_id);
          $model->svr_name = $query['svr_name'];
          $query = DcmdTaskCmd::findOne($model->task_cmd_id); ////Yii::$app->request->post()['task_cmd_id']);
          $model->task_cmd = $query['task_cmd'];
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          $arg = array();
          foreach(Yii::$app->request->post() as $k=>$v) {
            if(substr($k,0,3) == "Arg") $arg[substr($k,3)] = $v;
          }
          $model->task_arg = arrToXml($arg); 
          if($model->save()) {
            Yii::$app->getSession()->setFlash('success',"添加成功!");
            $this->oprlog(1, "insert task template:".$model->task_tmpt_name);
            return $this->redirect(['view', 'id' => $model->task_tmpt_id]);   
          }
        }
        ///获取admin用户所在系统组的产品
        $temp = DcmdUserGroup::find()->andWhere(['uid'=>Yii::$app->user->getId()])->asArray()->all();; 
        $sys_ar = array();
        foreach($temp as $item) $sys_ar[$item['gid']] = $item['gid'];
        ///获取产品信息
        $query = DcmdApp::find()->asArray()->all();
        $app = array(""=>"");
        foreach($query as $item) {
          if(array_key_exists($item['sa_gid'], $sys_ar)) $app[$item['app_id']] = $item['app_name'];
        }
        ///获取任务脚本
        $query = DcmdTaskCmd::find()->asArray()->all();
        $task_cmd = array(""=>"");
        if($query) {
          foreach($query as $item) $task_cmd[$item['task_cmd_id']] = $item['ui_name'];
        }
        return $this->render('create', [
             'model' => $model,
             'app' => $app,
             'task_cmd' => $task_cmd,
        ]);
    }

    /**
     * Updates an existing DcmdTaskTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        ///只有管理员可以操作
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-task-template/view', 'id'=>$id));
        }
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
          $model->load(Yii::$app->request->post());
          $query = DcmdService::findOne($model->svr_id);
          $model->svr_name = $query['svr_name'];
          $query = DcmdTaskCmd::findOne($model->task_cmd_id); ////Yii::$app->request->post()['task_cmd_id']);
          $model->task_cmd = $query['task_cmd'];
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          $arg = array();
          foreach(Yii::$app->request->post() as $k=>$v) {
            if(substr($k,0,3) == "Arg") $arg[substr($k,3)] = $v;
          }
          $model->task_arg = arrToXml($arg);
          
          if ($model->save()) {
             Yii::$app->getSession()->setFlash('success', "修改成功");
             $this->oprlog(2, "update task template:".$model->task_tmpt_name);
             return $this->redirect(['view', 'id' => $model->task_tmpt_id]);
          }
        } 
        ///获取产品信息
        $query = DcmdApp::find()->asArray()->all();
        $app = array(""=>"");
        if ($query) {
          foreach($query as $item) $app[$item['app_id']] = $item['app_name'];
        }
        ///获取任务脚本
        $query = DcmdTaskCmd::find()->asArray()->all();
        $task_cmd = array(""=>"");
        if($query) {
          foreach($query as $item) $task_cmd[$item['task_cmd_id']] = $item['task_cmd'];
        }

        $arg_content = $this->actionGetTaskTypeArg($model->task_cmd_id, xmltoarray($model->task_arg));
        return $this->render('update', [
             'model' => $model,
             'app' => $app,
             'task_cmd' => $task_cmd,
             'svr' => array($model->svr_id=>$model->svr_name),
             'arg_content' => $arg_content,
         ]);
        
    }
    
    /**
     * Deletes an existing DcmdTaskTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $svr_id=0)
    {
        ////只有管理员可操作
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-task-template/index'));
        }
        DcmdTaskTemplateServicePool::deleteAll('task_tmpt_id = '.$id);
        $model = $this->findModel($id);
        $this->oprlog(3,"delete task template:".$model->task_tmpt_name);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        if($svr_id == 0) return $this->redirect(['index']);
        else return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
    }

    public function actionGetServices()
    {
      $app_id = Yii::$app->request->post()["app_id"];
      $query = DcmdService::find()->andWhere(['app_id'=>$app_id])->asArray()->all();
      $retcontent = "";
      if ($query) {
         foreach($query as $item) $retcontent .= $item["svr_id"].",".$item['svr_name'].";";
      }
      echo $retcontent;
      exit ;
    }


    public function actionGetServicePools()
    {
      $svr_id = Yii::$app->request->post()["svr_id"];
      $query = DcmdServicePool::find(['svr_id'=>$svr_id])->asArray()->all();
      $retcontent = "";
      if ($query) {
         foreach($query as $item) $retcontent .= $item["svr_pool_id"].",".$item['svr_pool'].";";
      }
      echo $retcontent;
      exit ;
    }

    public function actionGetTaskTypeArg($task_cmd_id, $arg = array(), $disabled="")
    {
      $query = DcmdTaskCmdArg::find()->andWhere(['task_cmd_id'=>$task_cmd_id])->asArray()->all(); 
      $content = "";
      if($query) {
        $content .= '<table class="table table-striped table-bordered detail-view">
             <tr> <td>参数名称</td>
             <td>是否可选</td>
             <td>值</td>
             </tr>';
       foreach($query as $item) {
        $content .=  "<tr><td>".$item['arg_name'].'</td>';
        $content .=  "<td>"; if($item['optional'] == 0) $content .= "否"; else $content .= "是"; $content .= "</td>";
        $content .= "<td style=\"display:none\">".$item['arg_name']."</td>";
        ///$content .= "<td><input name='Arg".$item['arg_name']."' type='text' $disabled ";
        if(is_array($arg) && array_key_exists($item['arg_name'], $arg)) {
          if ($disabled != "") $content .= "<td>".$arg[$item['arg_name']];
          else $content .= "<td><input name='Arg".$item['arg_name']."' type='text'  value='".$arg[$item['arg_name']]."' >";
        } else {
          if ($disabled != "") $content.= "<td>"; 
          else $content .= "<td><input name='Arg".$item['arg_name']."' type='text'  value='' >";
        }
        $content .= "</td><tr>";
       }
       $content .= "</table>";
      }else{
       $content .= "无参数设定";
      }
      return $content;
    }
 

    /**
     * Finds the DcmdTaskTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdTaskTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdTaskTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    private function oprlog($opr_type, $sql) {
      $opr_log = new DcmdOprLog();
      $opr_log->log_table = "dcmd_app";          
      $opr_log->opr_type = $opr_type;
      $opr_log->sql_statement = $sql;
      $opr_log->ctime = date('Y-m-d H:i:s');
      $opr_log->opr_uid = Yii::$app->user->getId();
      $opr_log->save();
    }
}
