<?php

namespace app\controllers;

use Yii;
use app\models\DcmdGroupRepeatCmd;
use app\models\DcmdGroupRepeatCmdSearch;
use app\models\DcmdGroup;
use app\models\DcmdOprCmdRepeatExec;
use app\models\DcmdOprCmdRepeatExecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdGroupRepeatCmdController implements the CRUD actions for DcmdGroupRepeatCmd model.
 */
class DcmdGroupRepeatCmdController extends Controller
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
     * Lists all DcmdGroupRepeatCmd models.
     * @return mixed
     */
    private function actionIndex()
    {
        $searchModel = new DcmdGroupRepeatCmdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdGroupRepeatCmd model.
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
     * Creates a new DcmdGroupRepeatCmd model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($gid)
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-group/index'));
        }
        $query = DcmdGroupRepeatCmd::find()->andWhere(['gid'=>$gid])->asArray()->all();
        $opr_cmd = " repeat_cmd_id not in(0";
        foreach($query as $item) $opr_cmd .= ",".$item['repeat_cmd_id'];
        $opr_cmd .=")";

        $searchModel = new DcmdOprCmdRepeatExecSearch();
        $dataProvider = $searchModel->search(array(), $opr_cmd);

        return $this->render('create', [
             'gid' => Yii::$app->request->queryParams['gid'],
             'gname' => Yii::$app->request->queryParams['gname'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        /*$model = new DcmdGroupRepeatCmd();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }*/
    }

    /**
     * Updates an existing DcmdGroupRepeatCmd model.
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
     * Deletes an existing DcmdGroupRepeatCmd model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $repeat_cmd_id)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-opr-cmd-repeat-exec/index'));
        }
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', '删除成功!');
        return $this->redirect(['dcmd-opr-cmd-repeat-exec/view', 'id'=>$repeat_cmd_id]);
    }

    /**
     * Finds the DcmdGroupRepeatCmd model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdGroupRepeatCmd the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdGroupRepeatCmd::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAddGroup($repeat_cmd_id=0)
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-opr-cmd-repeat-exec/index'));
        }
        $model = new DcmdGroupRepeatCmd();
        if(Yii::$app->request->post()) {
          ///var_dump(Yii::$app->request->post());exit;
          $date = date('Y-m-d H:i:s');
          if(is_array(Yii::$app->request->post()['DcmdGroupRepeatCmd']['gid'] ))
           foreach(Yii::$app->request->post()['DcmdGroupRepeatCmd']['gid'] as $gid) {
            $dcmd_group_cmd = new DcmdGroupRepeatCmd();
            $dcmd_group_cmd->gid = $gid;
            $dcmd_group_cmd->repeat_cmd_id = $repeat_cmd_id;
            $dcmd_group_cmd->utime = $date;
            $dcmd_group_cmd->ctime = $date;
            $dcmd_group_cmd->opr_uid = Yii::$app->user->getId();
            $dcmd_group_cmd->save();
            Yii::$app->getSession()->setFlash('success', '添加成功!');
          }else Yii::$app->getSession()->setFlash('error', "没有选择用户组!");
          return $this->redirect(['dcmd-opr-cmd-repeat-exec/view', 'id' => $repeat_cmd_id]);
        } else {
            $exist_group = array();
            $query = DcmdGroupRepeatCmd::find()->andWhere(['repeat_cmd_id'=>$repeat_cmd_id])->asArray()->all();
            foreach($query as $item) $exist_group[$item['gid']] = $item['gid'];
            $query = DcmdGroup::find()->andWhere(['gtype'=>2])->asArray()->all();
            $group = array();
            foreach($query as $item)
              if(!array_key_exists($item['gid'], $exist_group))
                $group[$item['gid']] = $item['gname'];
            $repeat_cmd = DcmdOprCmdRepeatExec::findOne($repeat_cmd_id);
            return $this->render('add_group', [
                'model' => $model,
                'repeat_cmd' => $repeat_cmd,
                'group' => $group,
            ]);
        }
    }

    public function actionAddCmd()
    {
        if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起，你没有权限!");
          return $this->redirect(array('dcmd-group/index'));
        }
        $gid = Yii::$app->request->post()["gid"];
        $gname =  Yii::$app->request->post()["gname"];
        $uid_array = array();
        $success_msg = "未选择操作!";
        if (array_key_exists("selection", Yii::$app->request->post())){
           $success_msg = "添加成功:";
           $tm =  date('Y-m-d H:i:s');
           foreach(Yii::$app->request->post()["selection"] as $k=>$v) {
             $dcmd_group_repeat_cmd = new DcmdGroupRepeatCmd();
             $dcmd_group_repeat_cmd->repeat_cmd_id = $v;
             $dcmd_group_repeat_cmd->gid = $gid;
             $dcmd_group_repeat_cmd->utime = $tm;
             $dcmd_group_repeat_cmd->ctime = $tm;
             $dcmd_group_repeat_cmd->opr_uid = Yii::$app->user->getId();
             $dcmd_group_repeat_cmd->save();
           }
        }
        Yii::$app->getSession()->setFlash('success', $success_msg);
        return $this->redirect(array('dcmd-group/view','id'=>$gid, 'show_div'=>'dcmd-group-repeat-cmd'));
    }
    public function actionRemove()
    {
      if(Yii::$app->user->getIdentity()->admin != 1 || Yii::$app->user->getIdentity()->sa != 1) {
        Yii::$app->getSession()->setFlash('success', NULL);
        Yii::$app->getSession()->setFlash('error', "对不起,你没有权限!");
        return $this->redirect(array('dcmd-group/index'));
      }
      $gid = Yii::$app->request->queryParams["gid"];
      $this->findModel(Yii::$app->request->queryParams["id"])->delete();
      Yii::$app->getSession()->setFlash('success', '删除成功!');
      return $this->redirect(['dcmd-group/view', 'id'=>$gid, 'show_div'=>'dcmd-group-repeat-cmd']);
    }
}
