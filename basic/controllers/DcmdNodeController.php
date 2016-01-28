<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNode;
use app\models\DcmdServicePoolNode;
use app\models\DcmdNodeGroup;
use app\models\DcmdUserGroup;
use app\models\DcmdNodeSearch;
use app\models\DcmdCenter;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * DcmdNodeController implements the CRUD actions for DcmdNode model.
 */
class DcmdNodeController extends Controller
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
     * Lists all DcmdNode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdNodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        ///获取服务池列表
        $query = DcmdNodeGroup::find()->orderBy('ngroup_name')->asArray()->all();
        $dcmd_node_group = array();
        foreach($query as $item) $dcmd_node_group[$item['ngroup_id']] = $item['ngroup_name'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dcmd_node_group' => $dcmd_node_group,
        ]);
    }

    public function actionUnuseNode()
    {

        $searchModel = new DcmdNodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        ///获取服务池列表
        $query = DcmdNodeGroup::find()->orderBy('ngroup_name')->asArray()->all();
        $dcmd_node_group = array();
        foreach($query as $item) $dcmd_node_group[$item['ngroup_id']] = $item['ngroup_name'];
        return $this->render('unuse-node', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dcmd_node_group' => $dcmd_node_group,
        ]);
    }
    /**
     * Displays a single DcmdNode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewIp($ip){
      $query = DcmdNode::findOne(['ip'=>$ip]);
      return $this->render('view', ['model' => $this->findModel($query["nid"]),]);
    }
    public function actionGetRunningTask() {
        $ip = Yii::$app->request->queryParams['ip'];
        $query = DcmdCenter::findOne(['master'=>1]);
        $run_task = array();
        $ret_msg = '<table class="table table-striped table-bordered"><tbody>';
        $ret_msg .="<tr><td>服务</td><td>服务池</td><td>任务名</td><td>subtask_id</td></tr>";
        if ($query) {
          list($host, $port) = split(':', $query["host"]);
          $reply = getRunningTask($host, $port, $ip);
          if ($reply->getState() == 0) {
            $subtaskInfo = $reply->getResult();
            foreach($subtaskInfo as $item) array_push($run_task, $item);
          }else { 
            $ret_msg .="<tr><td colspan=4><font color=red>获取失败:.".$reply->getErr()."</font></td></tr>";  
          }
        }else {
            $ret_msg .="<tr><td colspan=4><font color=red>获取Center失败</font></td></tr>";
        }
       foreach($run_task as $task)
         $ret_msg .="<tr><td>".$task->svr_name."</td><td>".$task->svr_pool."</td><td>".$task->task_cmd."</td><td>".$task->subtask_id."</td><td>".$task->subtask_id."</td></tr>";
       $ret_msg .= "</tbody></table>";
       echo $ret_msg;
       exit;
    }
  
    public function actionGetRunningOpr() {
        $ip = Yii::$app->request->queryParams['ip'];
        $query = DcmdCenter::findOne(['master'=>1]);
        $run_opr = array();
        $ret_msg = '<table class="table table-striped table-bordered"><tbody>';
        $ret_msg .="<tr><td>操作名</td><td>开始时间</td><td>运行时间</td></tr>";
        if ($query) {
          list($host, $port) = split(':', $query["host"]);
          $reply = getRunningOpr($host, $port, $ip);
          if ($reply->getState() == 0) {
            $oprInfo = $reply->getResult();
            foreach($oprInfo as $item) array_push($opr_task, $item);
          }else { 
            $ret_msg .="<tr><td colspan=3><font color=red>获取失败:.".$reply->getErr()."</font></td></tr>";
          }
        }else {
            $ret_msg .="<tr><td colspan=3><font color=red>获取Center失败</font></td></tr>";
        }
       foreach($run_opr as $opr)
         $ret_msg .="<tr><td>".$opr->name."</td><td>".$opr->start_time."</td><td>".$opr->running_second."</td></tr>";
       $ret_msg .= "</tbody></table>";
       echo $ret_msg;
       exit;
    }
    /**
     * Creates a new DcmdNode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($ngroup_id)
    {
        ///判断用户是否和该设备池子属于一个系统组
        $model = DcmdNodeGroup::findOne($ngroup_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->goBack();///redirect(array('index'));
        }
        $model = new DcmdNode();
        $ret = DcmdNodeGroup::findOne($ngroup_id);
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(1,"insert ip:".$model->ip);
            Yii::$app->getSession()->setFlash('success',"添加成功");
           $query = DcmdCenter::findOne(['master'=>1]);
           if ($query) {
             list($host, $port) = split(':', $query["host"]);
             $reply = agentValid($host, $port, $model->ip);
             # Yii::$app->getSession()->setFlash('success', $reply);
           }
            return $this->redirect(['view', 'id' => $model->nid]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str); 
        }
        return $this->render('create', [
            'model' => $model,
           'node_group' => array($ngroup_id=>$ret->ngroup_name),
        ]);
    }
    public function actionCreateIp($ip) {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!!");
          return $this->redirect(array('dcmd-node/index'));
        }
       $model = new DcmdNode();
       if(Yii::$app->request->post()&& $model->load(Yii::$app->request->post())) {
         $model->utime = date('Y-m-d H:i:s');
         $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
         if ($model->save()) {
           $this->oprlog(1,"insert node:".$ip);
           Yii::$app->getSession()->setFlash('success', "添加成功");

           $query = DcmdCenter::findOne(['master'=>1]);
           if ($query) {
             list($host, $port) = split(':', $query["host"]);
             $reply = agentValid($host, $port, $model->ip);
             # Yii::$app->getSession()->setFlash('success', $reply);
           }
           return $this->redirect(['dcmd-node/view', 'id' => $model->nid]);
         }else 
            Yii::$app->getSession()->setFlash('error', '添加失败');
       }
       $model->ip = $ip;
       $query = DcmdNodeGroup::find()->asArray()->all();
       $group = array();
       foreach($query as $item) $group[$item['ngroup_id']] = $item['ngroup_name'];
       return $this->render('add', ['model' => $model, 'node_group'=>$group]);
    }
    /**
     * Updates an existing DcmdNode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        ///判断用户是否和该设备池子属于一个系统组
        $tmp = DcmdNodeGroup::findOne($model['ngroup_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$tmp['gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->goBack();///redirect(array('index'));
        }

        $node_group = $this->getDcmdNodeGroup();
        if(Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->oprlog(2,"update node:".$model->ip);
            Yii::$app->getSession()->setFlash('success', "修改成功");
            return $this->redirect(['view', 'id' => $model->nid]);
          } 
         $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "修改失败:".$err_str);
        }
        return $this->render('update', [
             'model' => $model,
             'node_group' => $node_group,
        ]);
    }

    /**
     * Deletes an existing DcmdNode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $ngroup_id=NULL)
    {
        $model = $this->findModel($id);
        ///判断用户是否和该设备池子属于一个系统组
        $tmp = DcmdNodeGroup::findOne($model['ngroup_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$tmp['gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->goBack();///redirect(array('index'));
        }
        $node = DcmdServicePoolNode::find()->where(['nid' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '有服务池子使用该设备,不可删除!');
        }else {
          $model=$this->findModel($id);
          $this->oprlog(3,"delete node:".$model->ip);
          $model->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }

        if($ngroup_id == NULL) {
         return $this->redirect(['index']);
        }else{
         return $this->redirect(array('dcmd-node-group/view', 'id'=>$ngroup_id)); 
        }
    }
    public function actionDeleteAll()
    {

      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $suc_msg = "";
      $err_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        ///判断用户是否和该设备池子属于一个系统组
        $tmp = DcmdNodeGroup::findOne($model['ngroup_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$tmp['gid']]);
        if($query==NULL) {
          $err_msg .=$model->ip.":没有权限删除<br>";
          continue;
        }
        $node = DcmdServicePoolNode::find()->where(['nid' => $id])->one();
        if($node) {
          $err_msg .=$model->ip.':有服务池子使用该设备,不可删除<br>';
          continue;
        }else {
          $this->oprlog(3, "delete node:".$model->ip);
          $suc_msg .=$model->ip.':删除成功<br>';
        }
        $model->delete();
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);
    }
    public function actionConvert()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      if(count($select) < 1) {
        Yii::$app->getSession()->setFlash('error', '未选择设备!');
        return $this->redirect(['index']);
      } 
      ///设备池
      $query = DcmdNodeGroup::find()->asArray()->all();
      $node_group = array();
      foreach($query as $item) $node_group[$item['ngroup_id']] = $item['ngroup_name'];
      ///获取需要变更的ip
      $ips_info = array();
      $ids="";
      foreach($select as $k=>$id){
        $model = $this->findModel($id);
        array_push($ips_info, array('id'=>$id, 'ip'=>$model->ip));
        $ids.=$id.";";
      } 
      return $this->render('select_group', ['ips_info'=>$ips_info, 'ids'=>$ids, 'node_group'=>$node_group]);
    }
    public function actionChangeNodeGroup()
    {
      $ngroup_id = Yii::$app->request->post()['ngroup_id'];
      $ids = explode(";", Yii::$app->request->post()['ids']);
      if($ngroup_id == "") {
        Yii::$app->getSession()->setFlash('error', "未选择设备池!");
        return $this->redirect(['index']);
      }
      ///判断用户是否和该设备池子属于一个系统组
      $tmp = DcmdNodeGroup::findOne($ngroup_id);
      $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$tmp['gid']]);
      if($query==NULL) {
        Yii::$app->getSession()->setFlash('error', "没有权限切换到该服务池");
        return $this->redirect(['index']); 
      }
      $ret_msg ="";
      foreach($ids as $k=>$id) {
        if($id == "") continue;
        $model = $this->findModel($id);
        ///判断用户是否和该设备池子属于一个系统组
        $tmp = DcmdNodeGroup::findOne($model['ngroup_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$tmp['gid']]);
        if($query==NULL) {
          $ret_msg .="没有权限变更:".$model->ip." ";
          continue;
        }
        $model->ngroup_id = $ngroup_id;
        $model->save();
        $ret_msg .="变更成功:".$model->ip." ";
      }
      Yii::$app->getSession()->setFlash('success', $ret_msg);
      return $this->redirect(['index']);
    }
    ///获取os用户
    public function actionOsUser($ip)
    {
       $query = DcmdCenter::findOne(['master'=>1]);
       $ret_msg = '<table class="table table-striped table-bordered"><tbody>';  
       $ret_msg .="<tr><td>用户名</td></tr>";
       if($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execRepeatOprCmd($host, $port, "get_host_user", array("include_sys_user"=>0), array($ip));
         if ($reply->getState() == 0) {
           foreach($reply->getResult() as $agent) {
             if($agent->getState() == 0) {
               $users = explode("\n", $agent->getResult());
               foreach($users as $user) $ret_msg .= "<tr><td>".$user."</td></tr>";
             }else $ret_msg .= "<tr><td><font color=red>获取失败:".$agent->getErr()."</font></td></tr>";
           }
         }else{
           $ret_msg .= "<tr><td><font color=red>获取失败:".$reply->getErr()."</font></td></tr>";
         }
       }else {
         $ret_msg .="<tr><td><font color=red>无法获取Center!</font></td></tr>";
       }
       echo $ret_msg;
       exit;
    }
    ///获取OS信息
    public function actionOsInfo($ip)
    {
       $query = DcmdCenter::findOne(['master'=>1]);
       $ret_msg = "";
       if($query) {
         list($host, $port) = split(':', $query["host"]);
         $reply = execRepeatOprCmd($host, $port, "os_info", array(), array($ip));
         if ($reply->getState() == 0) {
            foreach($reply->getResult() as $agent) {
               if($agent->getState() == 0) {
                 $result = explode("\n", $agent->getResult());
                 $os = array();
                 foreach($result as $k=>$v) {
                   $pos = strpos($v, ":");
                   if($pos == false) continue;
                   $col = substr($v, 0, $pos);
                   $param = substr($v,$pos+1, strlen($v)-$pos);
                   $pos = strpos($param, "=");
                   $p1 = substr($param, 0, $pos);
                   $p2 = substr($param, $pos+1, strlen($param)-$pos);
                   if(array_key_exists($col, $os) == false) $os[$col] = array();
                   $os[$col][$p1]=$p2;
                 }
                 foreach($os as $k=>$v) {
                   $ret_msg .="<p><strong>".$k."信息:</strong></p>";
                   $ret_msg .= '<table class="table table-striped table-bordered"><tbody>';
                   $ret_msg .="<tr><td width=40%>参数</td><td>值</td></tr>"; 
                   foreach($v as $a=>$b) {
                     $ret_msg .="<tr><td>".$a."</td><td>".$b."</td></tr>"; 
                   }
                   $ret_msg .="</tbody></table>";
                 }
               }else {
                 $ret_msg .="<p><font color=red>获取OS失败:";
                 $ret_msg .=str_replace("\n", "<br/>",$agent->getErr())."</font></p>";
               }
            }
         }else{
           $ret_msg .= "<p><font color=red>获取失败:".str_replace("\n", "<br/>",$reply->getErr())."</font></p>";
         }
       }else{
         $ret_msg .= "<p><font color=red>无法获取Center!</font></p>";
       }
       echo $ret_msg;
       exit;
    }
    /**
     * Finds the DcmdNode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdNode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdNode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     *Get dcmd_node_group list
     */
    protected  function getDcmdNodeGroup() {
      $group = array();
      $ret = DcmdNodeGroup::findBySql("select ngroup_id, ngroup_name from dcmd_node_group")->asArray()->all();
      foreach($ret as $g) {
        $group[$g['ngroup_id']] = $g['ngroup_name'];
      }
      return $group;
    }

    ///get agent hostname
    public function actionGetAgentHostname()
    {
      $agent_ip = Yii::$app->request->post()["ip"];
      $query = DcmdCenter::findOne(['master'=>1]);
      $retcontent = array("hostname"=>"",);
      if ($query) {
          list($ip, $port) = split(':', $query["host"]);
          $reply = getAgentHostName($ip, $port, $agent_ip);
          if ($reply->getState() == 0 && $reply->getIsExist() == true) {
            $retContent["hostname"] = $reply->getHostname();
          }
      }
      echo json_encode($retContent);
      exit;
    }

    private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_node";          
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }
}
