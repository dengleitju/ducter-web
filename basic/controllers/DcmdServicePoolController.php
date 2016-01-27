<?php

namespace app\controllers;

use Yii;
use app\models\DcmdServicePool;
use app\models\DcmdServicePoolSearch;
use app\models\DcmdServicePoolNode;
use app\models\DcmdApp;
use app\models\DcmdUserGroup;
use app\models\DcmdService;
use app\models\DcmdServicePoolNodeSearch;
use app\models\DcmdServicePoolAttr;
use app\models\DcmdServicePoolAttrDef;
use app\models\DcmdOprLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * DcmdServicePoolController implements the CRUD actions for DcmdServicePool model.
 */
class DcmdServicePoolController extends Controller
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
     * Lists all DcmdServicePool models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = array();
        if(array_key_exists('DcmdServicePoolSearch', Yii::$app->request->queryParams)){
          $params['DcmdServicePoolSearch'] = Yii::$app->request->queryParams['DcmdServicePoolSearch'];
          if($params['DcmdServicePoolSearch']['app_id'] == "") {
            $params['DcmdServicePoolSearch']['svr_id'] = "";
          }
        }
        ///应用足用户只可查看所在组的应用
        $app_con = "";
        if(Yii::$app->user->getIdentity()->admin != 1)
        {
          $app_con = "svr_gid in (0";
          $query = DcmdUserGroup::find()->andWhere(['uid' => Yii::$app->user->getId()])->asArray()->all();
          if($query) foreach($query as $item) $app_con .= ",".$item['gid'];
          $app_con .= ")";
        }
        $query = DcmdApp::find()->where($app_con)->orderBy('app_name')->asArray()->all();
        $app = array();
        foreach($query as $item) $app[$item['app_id']] = $item['app_name'];
        if(array_key_exists('DcmdServicePoolSearch',$params)) {
           if(!array_key_exists($params['DcmdServicePoolSearch']['app_id'], $app))
              $params['DcmdServicePoolSearch']['svr_id'] = "";
        }
        $svr = array();
        if(array_key_exists('DcmdServicePoolSearch', $params) &&
           array_key_exists('app_id' ,  $params['DcmdServicePoolSearch'])) {
           $query = DcmdService::find()->andWhere(['app_id'=>$params['DcmdServicePoolSearch']['app_id']])->asArray()->all();
          if($query) {
            foreach($query as $item) $svr[$item['svr_id']] = $item['svr_name'];
          }
        }
        if(array_key_exists('DcmdServicePoolSearch',$params)) {
           if(!array_key_exists($params['DcmdServicePoolSearch']['svr_id'], $svr))
              $params['DcmdServicePoolSearch']['svr_id'] = "";
        }
        $searchModel = new DcmdServicePoolSearch();
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'app' => $app,
            'svr' => $svr,
        ]);
    }

    /**
     * Displays a single DcmdServicePool model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new DcmdServicePoolNodeSearch();
        $con = array();
        $con['DcmdServicePoolNodeSearch'] = array('svr_pool_id'=>$id);
        if(array_key_exists('DcmdServicePoolNodeSearch', Yii::$app->request->queryParams))
           $con = array_merge($con, Yii::$app->request->queryParams);
        $con['DcmdServicePoolNodeSearch']['svr_pool_id'] = $id;
        $dataProvider = $searchModel->search($con, false);
        $show_div = "dcmd-service-pool-node";
        if(array_key_exists("show_div", Yii::$app->request->queryParams))
          $show_div = Yii::$app->request->queryParams['show_div'];
        ///获取属性
        $self_attr = DcmdServicePoolAttr::find()->andWhere(['svr_pool_id'=>$id])->asArray()->all();
        $def_attr = DcmdServicePoolAttrDef::find()->asArray()->all();
        $attr_str = '<div id="w1" class="grid-view">
          <table class="table table-striped table-bordered"><thead>
          <tr><th>属性名</th><th>值</th><th>操作</th></tr>
          </thead><tbody>';
        $attr = array();
        foreach($self_attr as $item) {
          $attr_str .= '<tr><td>'.$item['attr_name'].'</td><td>'.$item['attr_value'].'</td><td><a href="/ducter/index.php?r=dcmd-service-pool-attr/update&id='.$item['id'].'&svr_pool_id='.$id.'">修改</a></td></tr>';
          $attr[$item['attr_name']] = $item['attr_name'];
        }
        foreach($def_attr as $item) {
          if(array_key_exists($item['attr_name'], $attr)) continue;
          $attr_str .= '<tr><td>'.$item['attr_name'].'</td><td>'.$item['def_value'].'</td><td><a href="/ducter/index.php?r=dcmd-service-pool-attr/update&id=0&attr_id='.$item['attr_id'].'&svr_pool_id='.$id.'">修改</a></td></tr>';
        }
        $attr_str .= "</tbody></table></div>";

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'show_div' => $show_div,
            'attr_str' => $attr_str,
        ]);
    }

    /**
     * Creates a new DcmdServicePool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id, $svr_id)
    {
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($app_id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        $model = new DcmdServicePool();
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = $model->utime;
          $model->opr_uid = Yii::$app->user->getId();
          if($model->save()) {
            $this->oprlog(1,"insert service pool:".$model->svr_pool);
            Yii::$app->getSession()->setFlash('success', "添加成功");
            return $this->redirect(['view', 'id' => $model->svr_pool_id]);
          }
          $err_str = "";
          foreach($model->getErrors() as $k=>$v) $err_str.=$k.":".$v[0]."<br>";
          Yii::$app->getSession()->setFlash('error', "添加失败:".$err_str);
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        } 
        $model = new DcmdServicePool();
        $model->app_id = $app_id;
        $model->svr_id = $svr_id;
        return $this->render('create', [
              'model' => $model,
        ]);
        
    }

    /**
     * Updates an existing DcmdServicePool model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service-pool/view', 'id'=>$id, 'show_div'=>'dcmd-service-pool'));
        }
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
          if($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('error',NULL);
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            $this->oprlog(2,"update service pool:".$model->svr_pool);
            return $this->redirect(['view', 'id' => $model->svr_pool_id, 'show_div'=>'dcmd-service-pool']);  
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
     * Deletes an existing DcmdServicePool model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $svr_id=NULL)
    {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('dcmd-service/view', 'id'=>$model['svr_id']));
        }
        $node = DcmdServicePoolNode::find()->where(['svr_pool_id' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '池子设备不为空,不可删除!');
        }else {
          ///删除服务池属性
          DcmdServicePoolAttr::deleteAll(['svr_pool_id'=>$id]);
          $this->oprlog(3,"delete service pool:".$model->svr_pool);
          $model->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }
        if ($svr_id) {
          return $this->redirect(array('dcmd-service/view', 'id'=>$svr_id));
        }
        return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {
      if(!array_key_exists('selection', Yii::$app->request->post())) {
        Yii::$app->getSession()->setFlash('error', '未选择服务池!');
        return $this->redirect(['index']);
      }
      $select = Yii::$app->request->post()['selection'];
      $err_msg = "";
      $suc_msg = "";
      foreach($select as $k=>$id) {
        $model = $this->findModel($id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $temp = DcmdApp::findOne($model['app_id']);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$temp['sa_gid']]);
        if($query==NULL) {
           $err_msg .=$model->svr_pool.":没有权限删除<br>";
           continue;
        }
        $node = DcmdServicePoolNode::find()->where(['svr_pool_id' => $id])->one();
        if($node) {
          $err_msg .= $model->svr_pool.':服务池子设备不为空,不可删除<br>';
          continue;
        }else {
          ///删除服务池属性
          DcmdServicePoolAttr::deleteAll(['svr_pool_id'=>$id]);
          $model->delete();
          $suc_msg .=$model->svr_pool.':删除成功<br>';
        }
      }
      if($suc_msg != "") Yii::$app->getSession()->setFlash('success', $suc_msg);
      if($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
      return $this->redirect(['index']);
    }
    /**
     * Finds the DcmdServicePool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdServicePool the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdServicePool::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

   private function oprlog($opr_type, $sql) {
     $opr_log = new DcmdOprLog();
     $opr_log->log_table = "dcmd_service_pool";          
     $opr_log->opr_type = $opr_type;
     $opr_log->sql_statement = $sql;
     $opr_log->ctime = date('Y-m-d H:i:s');
     $opr_log->opr_uid = Yii::$app->user->getId();
     $opr_log->save();
   }
}
