<?php

namespace app\controllers;

use Yii;
use app\models\DcmdOprCmd;
use app\models\DcmdOprCmdSearch;
use app\models\DcmdOprCmdArgSearch;
use app\models\DcmdCenter;
use app\models\DcmdOprCmdArg;
use app\models\DcmdOprCmdExec;
use app\models\DcmdGroupCmdSearch;
use app\models\DcmdUserGroup;
use app\models\DcmdGroupCmd;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
include_once(dirname(__FILE__)."/../common/interface.php");
include_once( dirname(__FILE__)."/../common/dcmd_util_func.php");
/**
 * DcmdOprCmdController implements the CRUD actions for DcmdOprCmd model.
 */
class DcmdOprCmdController extends Controller
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
     * Lists all DcmdOprCmd models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdOprCmdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdOprCmd model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
      ///判断用户权限
      if(Yii::$app->user->getIdentity()->admin != 1) {
        $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
        $gstr = " opr_cmd_id = ".$id." and gid in (0";
        foreach($query as $item) $gstr .=",".$item['gid'];
        $gstr .=")";
        $query = DcmdGroupCmd::find()->where($gstr)->asArray()->all();
        if(count($query) == 0) {
          Yii::$app->getSession()->setFlash('error', '对不起, 你没有权限!');
          return $this->redirect(['dcmd-opr-cmd/index']);
        }
      } 
        $searchModel = new DcmdOprCmdArgSearch();
        $params = array();
        $params['DcmdOprCmdArgSearch'] = array('opr_cmd_id'=>$id);
        $dataProvider = $searchModel->search($params);
        $group_searchModel = new DcmdGroupCmdSearch();
        $params = array('DcmdGroupCmdSearch'=>array('opr_cmd_id'=>$id));
        $group_dataProvider = $group_searchModel->search($params);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'arg_dataProvider' => $dataProvider,
            'group_searchModel' => $group_searchModel,
            'group_dataProvider' => $group_dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdOprCmd model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-opr-cmd/index'));
        }
        $model = new DcmdOprCmd();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
       
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '添加成功!');
            $opr_log = new DcmdOprLog();
            $opr_log->log_table = "dcmd_opr_cmd";
            $opr_log->opr_type = 1;
            $opr_log->sql_statement = "insert opr_cmd:$model->opr_cmd";
            $opr_log->ctime = date('Y-m-d H:i:s');
            $opr_log->opr_uid = Yii::$app->user->getId();
            $opr_log->save();
            return $this->redirect(['view', 'id' => $model->opr_cmd_id]);
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
     * Updates an existing DcmdOprCmd model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(['view', 'id'=>$id]);
        }
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $opr_log = new DcmdOprLog();
            $opr_log->log_table = "dcmd_opr_cmd";
            $opr_log->opr_type = 2;
            $opr_log->sql_statement = "modify opr_cmd:".$model->opr_cmd;
            $opr_log->ctime = date('Y-m-d H:i:s');
            $opr_log->opr_uid = Yii::$app->user->getId();
            $opr_log->save();
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['view', 'id' => $model->opr_cmd_id]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        } 
            
        return $this->render('update', [
             'model' => $model,
        ]);
        
    }

    /**
     * Deletes an existing DcmdOprCmd model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('index'));
        }
        ///删除dcmd_opr_cmd_arg
        DcmdOprCmdArg::deleteAll('opr_cmd_id='.$id);
        ///删除dcmd_group_cmd
        DcmdGroupCmd::deleteAll('opr_cmd_id='.$id);
        $model = $this->findModel($id); ///->delete();
        $opr_log = new DcmdOprLog();
        $opr_log->log_table = "dcmd_opr_cmd";
        $opr_log->opr_type = 3;
        $opr_log->sql_statement = "delete opr_cmd:".$model->opr_cmd;
        $opr_log->ctime = date('Y-m-d H:i:s');
        $opr_log->opr_uid = Yii::$app->user->getId();
        $opr_log->save();
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }

    public function actionLoadContent()
    {
      $opr_cmd = Yii::$app->request->post()["opr_cmd"];
      $query = DcmdCenter::findOne(['master'=>1]);
      $retcontent = array("md5"=>"",);
      if ($query) {
          list($ip, $port) = explode(':', $query["host"]);
          $reply = getOprScriptInfo($ip, $port, $opr_cmd);
          if ($reply->getState() == 0) {
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
    public function actionShellRun()
    {
      $temp = explode(";",Yii::$app->request->post()['args']);
      $args = array();
      foreach($temp as $ag) {
        $i = explode("=", $ag);
        if(sizeof($i) != 2) continue;
        $args[$i[0]] = $i[1];
      }
      $opr_cmd_id = Yii::$app->request->post()['opr_cmd_id'];
      $opr_cmd = $this->findModel($opr_cmd_id);
      $opr_exec = new DcmdOprCmdExec();
      $opr_exec->opr_cmd_id = $opr_cmd_id;
      $opr_exec->opr_cmd = $opr_cmd->opr_cmd;
      $opr_exec->run_user = $opr_cmd->run_user;
      $opr_exec->timeout = Yii::$app->request->post()['timeout'];
      $opr_exec->ip = Yii::$app->request->post()['ips'];
      $opr_exec->arg = arrToXml($args);
      $opr_exec->utime = date('Y-m-d H:i:s');
      $opr_exec->ctime = $opr_exec->utime;
      $opr_exec->opr_uid = Yii::$app->user->getId();
      $retcontent = array();
      if($opr_exec->save()){ 
        $query = DcmdCenter::findOne(['master'=>1]);
        if ($query) {
          list($ip, $port) = explode(':', $query["host"]);
          $reply = execOprCmd($ip, $port, $opr_exec->exec_id);
          if ($reply->getState() == 0) {
            $ret_msg = "State:success<br>Detail:<br>";
            foreach($reply->getResult() as $agent) {
               $ret_msg .="-------------------------------------------------------<br>";
               $ret_msg .= "Ip:".$agent->getIp()."<br>";
               if($agent->getState() == 0) {
                 $ret_msg .="State:success<br>";
                 $ret_msg .="Status:".$agent->getStatus()."<br>";
                 $ret_msg .="Result:<br>".str_replace("\n", "<br/>",$agent->getResult())."<br>";
               }else {
                 $ret_msg .="State:error<br>";
                 $ret_msg .="Detail:<br>".str_replace("\n", "<br/>",$agent->getErr())."<br>";
               }
               $retContent["result"] = $ret_msg;
            }
          }else{
            $retContent["result"] = "State:error<br>detail:".str_replace("\n", "<br/>",$reply->getErr())."<br>";
          }
        }else {
          $retContent["result"]="无法获取Center.";
        }
      }else $retContent["result"]="数据库操作失败!";
      echo json_encode($retContent);
      exit;
    }
    public function actionRun($opr_cmd_id, $ips="") 
    {
      ///判断用户权限
      if(Yii::$app->user->getIdentity()->admin != 1) {
        $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
        $gstr = " opr_cmd_id = ".$opr_cmd_id." and gid in (0";
        foreach($query as $item) $gstr .=",".$item['gid'];
        $gstr .=")";
        $query = DcmdGroupCmd::find()->where($gstr)->asArray()->all();
        if(count($query)== 0 ) {
          Yii::$app->getSession()->setFlash('error', '对不起, 你没有权限!');
          return $this->redirect(['dcmd-opr-cmd/index']);
        }
      }
       
      $opr = $this->findModel($opr_cmd_id);
      $arg = $this->showTaskArg($opr_cmd_id);
      return $this->render('run', [
              'opr' => $opr,
              'arg' => $arg,
              'ips' => $ips,
      ]); 
    }
    private function showTaskArg($opr_cmd_id)
    {
       $content = "";
       $query = DcmdOprCmdArg::find()->andWhere(['opr_cmd_id' => $opr_cmd_id])->asArray()->all();
       if ($query) { ///获取模板参数
         $content = '<table class="table table-striped table-bordered detail-view">
                    <tr><td>参数名称</td>
                    <td>是否可选</td>
                    <td>值</td></tr>';
         foreach($query as $item) {
           $content .=  "<tr><td>".$item['arg_name'].'</td>';
           $content .=  "<td>"; if($item['optional'] == 0) $content .= "否"; else $content .= "是"; $content .= "</td>";
           $content .= "<td><input name='Arg".$item['arg_name']."' class='form-control' type='text'  value='' >";
           $content .= "</td><tr>";
         }
         $content .= "</table>";
       }
       return $content;
    }

    public function actionGetOprList()
    {
      $prefix = Yii::$app->request->post()["prefix"];
      $query = DcmdCenter::findOne(['master'=>1]);
      $retContent = "";
      if ($query) {
          list($ip, $port) = explode(':', $query["host"]);
          $reply = getOprScriptList($ip, $port, $prefix);
          if ($reply->getState() == 0) {
            foreach($reply->getScripts() as $item)
             $retContent .=$item.";";
          }
      }
      echo $retContent;
      exit;
    }


    /**
     * Finds the DcmdOprCmd model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdOprCmd the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdOprCmd::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
