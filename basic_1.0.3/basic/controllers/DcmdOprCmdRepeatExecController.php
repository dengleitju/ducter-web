<?php

namespace app\controllers;

use Yii;
use app\models\DcmdOprCmdRepeatExec;
use app\models\DcmdOprCmdRepeatExecSearch;
use app\models\DcmdOprCmd;
use app\models\DcmdOprCmdArg;
use app\models\DcmdCenter;
use app\models\DcmdUserGroup;
use app\models\DcmdGroupRepeatCmd;
use app\models\DcmdGroupRepeatCmdSearch;
use app\models\DcmdTaskCmdArg;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
include dirname(__FILE__)."/../common/dcmd_util_func.php";
include dirname(__FILE__)."/../common/interface.php";
/**
 * DcmdOprCmdRepeatExecController implements the CRUD actions for DcmdOprCmdRepeatExec model.
 */
class DcmdOprCmdRepeatExecController extends Controller
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
     * Lists all DcmdOprCmdRepeatExec models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdOprCmdRepeatExecSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdOprCmdRepeatExec model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
      ///判断用户权限
      if(Yii::$app->user->getIdentity()->admin != 1) {
        $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
        $gstr = " repeat_cmd_id = ".$id." and gid in (0";
        foreach($query as $item) $gstr .=",".$item['gid'];
        $gstr .=")";
        $query = DcmdGroupRepeatCmd::find()->where($gstr)->asArray()->all();
        if(count($query) == 0) {
          Yii::$app->getSession()->setFlash('error', '对不起, 你没有权限!');
          return $this->redirect(['dcmd-opr-cmd-repeat-exec/index']);
        }
      }
        $model = $this->findModel($id);
        $arg_content =  $this->getArg($model['arg']);
        $group_searchModel = new DcmdGroupRepeatCmdSearch();
        $params = array();
        $params['DcmdGroupRepeatCmdSearch'] = array('repeat_cmd_id' => $id);
        $group_dataProvider = $group_searchModel->search($params);
        return $this->render('view', [
            'model' => $model,
            'arg_content' => $arg_content,
            'group_searchModel' => $group_searchModel,
            'group_dataProvider' => $group_dataProvider,
        ]);
    }

    /**
     * Creates a new DcmdOprCmdRepeatExec model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-opr-cmd-repeat-exec/index'));
        }
        if (Yii::$app->request->post()) {
          $params = Yii::$app->request->post()['DcmdOprCmdRepeatExec'];
          $query = DcmdOprCmd::findOne(['opr_cmd' => $params['opr_cmd']]);
          $opr_cmd_repeat = new DcmdOprCmdRepeatExec();
          $opr_cmd_repeat->repeat_cmd_name = $params['repeat_cmd_name'];
          $opr_cmd_repeat->opr_cmd = $params['opr_cmd'];
          $opr_cmd_repeat->run_user = $query['run_user'];
          $opr_cmd_repeat->timeout = $params['timeout'];
          $opr_cmd_repeat->cache_time = $params['cache_time'];
          $opr_cmd_repeat->ip = $params['ip'];
          $opr_cmd_repeat->repeat = $params['repeat'];
          $opr_cmd_repeat->ip_mutable = $params['ip_mutable'];
          $opr_cmd_repeat->arg_mutable = $params['arg_mutable'];
          $opr_cmd_repeat->utime = date('Y-m-d H:i:s');
          $opr_cmd_repeat->ctime = $opr_cmd_repeat->utime; 
          $opr_cmd_repeat->opr_uid = Yii::$app->user->getId();
          $arg = array();
          foreach(Yii::$app->request->post() as $k=>$v) {
            if(substr($k,0,3) == "Arg") $arg[substr($k,3)] = $v;
          }
          $opr_cmd_repeat->arg = arrToXml($arg);
          if($opr_cmd_repeat->save()) {
            $this->oprlog(1, "insert repeat exec cmd:".$opr_cmd_repeat->repeat_cmd_name);
            Yii::$app->getSession()->setFlash('success', "添加成功");
            return $this->redirect(['view', 'id' => $opr_cmd_repeat->repeat_cmd_id]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str); 
        }
        $model = new DcmdOprCmdRepeatExec(); 
        $query = DcmdOprCmd::find()->asArray()->all();
        $opr_cmd = array(""=>"");
        foreach($query as $item) $opr_cmd[$item['opr_cmd']] = $item['opr_cmd'];
        return $this->render('create', [
              'model' => $model,
              'opr_cmd' => $opr_cmd,
          ]);
        
    }

    /**
     * Updates an existing DcmdOprCmdRepeatExec model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-opr-cmd-repeat-exec/index'));
        }
        $model = $this->findModel($id);
        if(Yii::$app->request->post()) {
          $params = Yii::$app->request->post()['DcmdOprCmdRepeatExec'];
          $model->timeout = $params['timeout'];
          $model->cache_time = $params['cache_time'];
          $model->ip = $params['ip'];
          $model->repeat = $params['repeat'];
          $model->ip_mutable = $params['ip_mutable'];
          $model->arg_mutable = $params['arg_mutable'];
          $arg = array();
          foreach(Yii::$app->request->post() as $k=>$v) {
            if(substr($k,0,3) == "Arg") $arg[substr($k,3)] = $v;
          }
          $model->arg = arrToXml($arg);
          if($model->save()) {
            $this->oprlog(2, "update repeat exec cmd:".$model->repeat_cmd_name);
            Yii::$app->getSession()->setFlash('success', "修改成功");
            return $this->redirect(['view', 'id' => $id]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        } 
        $arg_content =  $this->getArg($model['arg'], true, $model->opr_cmd);
        return $this->render('update', [
              'model' => $model,
              'arg_content' => $arg_content,
          ]);
    }

    /**
     * Deletes an existing DcmdOprCmdRepeatExec model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-opr-cmd-repeat-exec/index'));
        }
        ///删除dcmd_group_repeat_cmd
        DcmdGroupRepeatCmd::deleteAll('repeat_cmd_id='.$id);
        $model=$this->findModel($id);
        $this->oprlog(3, "delete repeat exec cmd:".$model->repeat_cmd_name);
        $model->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['index']);
    }

    public function actionGetOprCmdArg($opr_cmd)
    {
      $query = DcmdOprCmd::findOne(['opr_cmd'=>$opr_cmd]);
      $opr_cmd_id = $query['opr_cmd_id'];
      $query = DcmdOprCmdArg::find()->andWhere(['opr_cmd_id'=>$opr_cmd_id])->asArray()->all();
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
        $content .= "<td><input name='Arg".$item['arg_name']."' type='text'  value='' >";
        $content .= "</td><tr>";
       }
       $content .= "</table>";
      }else{
       $content .= "无参数设定";
      }
      return $content;
    }
    /**
     * Finds the DcmdOprCmdRepeatExec model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdOprCmdRepeatExec the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdOprCmdRepeatExec::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getArg($arg, $change=false, $opr_cmd=NULL)
    {
      $query = xml_to_array($arg);
      if(!is_array($query)) return "";
      if(!array_key_exists("env", $query)) return "";
      if(!is_array($query['env'])) return "";
      $content = "";
      $q = $query['env'];
      $content .= '<table class="table table-striped table-bordered detail-view">
             <tr> <td>参数名称</td>
             <td>值</td>
             </tr>';
      foreach($q as $k=>$v) {
        if($change) $content .= "<tr><td>".$k."</td><td><input name='Arg".$k."' class='form-control' type='text'  value='".$v."' ></td></tr>";
        else $content .=  "<tr><td>".$k.'</td><td>'.$v."</td></tr>";
       }
      ///检查是否有新参数
      if($opr_cmd) {
        $query = DcmdOprCmd::findOne(['opr_cmd'=>$opr_cmd]);
        if($query) {
          $query = DcmdOprCmdArg::find()->andWhere(['opr_cmd_id'=>$query['opr_cmd_id']])->asArray()->all();
          foreach($query as $item) {
            if(!array_key_exists($item['arg_name'], $q)) {
              if($change) $content .= "<tr><td>".$item['arg_name']."</td><td><input name='Arg".$item['arg_name']."' class='form-control' type='text'  value='' ></td></tr>"; 
              else $content .=  "<tr><td>".$item['arg_name']."</td><td></td></tr>";
            }
          }
        }
      }  
      $content .= "</table>";
      return $content;
    }
    public function actionRun($id, $ips="")
    {
      ///判断用户权限
      if(Yii::$app->user->getIdentity()->admin != 1) {
        $gstr = " gid in (0";
        $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
        foreach($query as $item) $gstr .=",".$item['gid'];
        $gstr .=")";
        $query = DcmdGroupRepeatCmd::find()->where($gstr)->asArray()->all();
        if(count($query) == 0) {
          Yii::$app->getSession()->setFlash('error', '对不起, 你没有权限!');
          return $this->redirect(['dcmd-opr-cmd-repeat-exec/index']);
        }
      }
      $opr = $this->findModel($id);
      $change = true;
      if($opr->arg_mutable == "0") $change = false;
      $arg = $this->getArg($opr->arg, $change, $opr->opr_cmd);
      if($opr->ip_mutable == "1" && $ips != "") $opr->ip=$ips;
      return $this->render('run', [
              'opr' => $opr,
              'arg' => $arg,
      ]);
    }
    public function actionShellRun()
   {
      $repeat_cmd_name = Yii::$app->request->post()['repeat_cmd_name'];
      $repeat_cmd = DcmdOprCmdRepeatExec::findOne(['repeat_cmd_name' => $repeat_cmd_name]);
      $ips = array();
      if($repeat_cmd->ip_mutable) {
        $t = Yii::$app->request->post()['ips'];
        $ips = explode(";", $t);
      }
      $args = array();
      if($repeat_cmd->arg_mutable) {
        $temp = explode(";",Yii::$app->request->post()['args']);
        foreach($temp as $ag) {
          $i = explode("=", $ag);
          if(sizeof($i) != 2) continue;
          $args[$i[0]] = $i[1];
        }
      }
      $retcontent = array();
      $query = DcmdCenter::findOne(['master'=>1]);
      if ($query) {
         list($ip, $port) = explode(':', $query["host"]);
         $reply = execRepeatOprCmd($ip, $port, $repeat_cmd_name, $args, $ips);
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
      echo json_encode($retContent);
      exit;
   }
   private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_opr_cmd_repeat_exec";
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }
}
