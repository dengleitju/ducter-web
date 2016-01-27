<?php

namespace app\controllers;

use Yii;
use app\models\DcmdGroup;
use app\models\DcmdUserGroup;
use app\models\DcmdNode;
use app\models\DcmdNodeSearch;
use app\models\DcmdNodeGroup;
use app\models\DcmdNodeGroupSearch;
use app\models\DcmdNodeGroupAttr;
use app\models\DcmdNodeGroupAttrDef;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdNodeGroupController implements the CRUD actions for DcmdNodeGroup model.
 */
class DcmdNodeGroupController extends Controller
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
     * Lists all DcmdNodeGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DcmdNodeGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $ret = DcmdGroup::findBySql("select gid,gname from dcmd_group where gtype=1 order by gname")->asArray()->all();
        $groupId = array();
        foreach($ret as $gid) {
         $groupId[$gid['gid']] = $gid['gname']; 
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groupId' => $groupId,
        ]);
    }

    /**
     * Displays a single DcmdNodeGroup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new DcmdNodeSearch();
        $searchModel->ngroup_id = $id;
        $params = Yii::$app->request->queryParams;
        $params["DcmdNodeSearch"]["ngroup_id"] = $id; 
        $params["DcmdNodeSearch"]["rack"] = "";
        $dataProvider = $searchModel->search($params);
        $show_div = "dcmd-node-group";
        if(array_key_exists('show_div', $params)) $show_div = $params['show_div'];
        if(array_key_exists("DcmdNodeSearch", Yii::$app->request->queryParams))
          $show_div = 'dcmd-node';
        ///获取属性
        $self_attr = DcmdNodeGroupAttr::find()->andWhere(['ngroup_id'=>$id])->asArray()->all();
        $def_attr = DcmdNodeGroupAttrDef::find()->asArray()->all();
        $attr_str = '<div id="w1" class="grid-view">
          <table class="table table-striped table-bordered"><thead>
          <tr><th>属性名</th><th>值</th><th>操作</th></tr>
          </thead><tbody>';
        $attr = array();
        foreach($self_attr as $item) {
          $attr_str .= '<tr><td>'.$item['attr_name'].'</td><td>'.$item['attr_value'].'</td><td><a href="/ducter/index.php?r=dcmd-node-group-attr/update&id='.$item['id'].'&ngroup_id='.$id.'">修改</a></td></tr>';
          $attr[$item['attr_name']] = $item['attr_name'];
        }
        foreach($def_attr as $item) {
          if(array_key_exists($item['attr_name'], $attr)) continue;
          $attr_str .= '<tr><td>'.$item['attr_name'].'</td><td>'.$item['def_value'].'</td><td><a href="/ducter/index.php?r=dcmd-node-group-attr/update&id=0&attr_id='.$item['attr_id'].'&ngroup_id='.$id.'">修改</a></td></tr>';
        }
        $attr_str .= "</tbody></table></div>"; 
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'attr_str' => $attr_str,
            'ngroup_id' => $id,
            'show_div' => $show_div,
        ]);
    }

    /**
     * Creates a new DcmdNodeGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->getIdentity()->admin != 1) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!!");
          return $this->redirect(array('index'));
        }
        $model = new DcmdNodeGroup();
        $sys_group = $this->getGroups();
        $query = DcmdUserGroup::find()->andWhere(['uid'=>Yii::$app->user->getId()])->asArray()->all();
        $groups = array();
        foreach($query as $item) {
          $groups[$item['gid']] = $sys_group[$item['gid']];
        }
        
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->ctime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
        } 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ngroup_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'groups' => $groups,
            ]);
        }
    }

    /**
     * Updates an existing DcmdNodeGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        ///判断用户是否和该设备池子属于一个系统组
        $model = $this->findModel($id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['gid']]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->goBack();///redirect(array('index'));
        }
        $groups = $this->getGroups();
        if (Yii::$app->request->post()) {
          $model->utime = date('Y-m-d H:i:s');
          $model->opr_uid = Yii::$app->user->getId();
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ngroup_id, 'show_div'=>'dcmd-node-group']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'groups' => $groups,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdNodeGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        ///判断用户是否和该设备池子属于一个系统组
        $model = $this->findModel($id);
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['gid']]);
        if($query == NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        $node = DcmdNode::find()->where(['ngroup_id' => $id])->one();
        if($node) {
          Yii::$app->getSession()->setFlash('error', '设备池子不为空,不可删除!');
        }else {
          ///删除组属性
          DcmdNodeGroupAttr::deleteAll(['ngroup_id'=>$id]);
          $this->findModel($id)->delete();
          Yii::$app->getSession()->setFlash('success', '删除成功!');
        }
        return $this->redirect(['index']);
    }
    public function actionDeleteAll() 
    {
        if(Yii::$app->user->getIdentity()->admin != 1 ){
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(array('index'));
        }
        if(!array_key_exists('selection', Yii::$app->request->post())) {
          Yii::$app->getSession()->setFlash('error', '未选择设备组!');
          return $this->redirect(['index']);
        }
        $select = Yii::$app->request->post()['selection'];
        $success_msg = "";
        $err_msg = "";
        foreach($select as $k=>$v) {
          $model = $this->findModel($v);
          ///判断用户是否和该设备池子属于一个系统组
          $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$model['gid']]);
          if($query == NULL) {
            $err_msg .=$model['ngroup_name'].":没有权限删除"."<br>";
            continue;
          }
          ///判断设备池是否为空
          $node = DcmdNode::find()->where(['ngroup_id' => $v])->one();
          if($node) {
            $err_msg .=$model['ngroup_name'].':设备池子不为空,不可删除'."<br>";
            continue;
          }
          DcmdNodeGroupAttr::deleteAll(['ngroup_id'=>$model->ngroup_id]);
          $model->delete();
          $success_msg .= $model['ngroup_name'].":删除成功"."<br>";
        }
        if($success_msg != "") Yii::$app->getSession()->setFlash('success', $success_msg);
        if ($err_msg != "") Yii::$app->getSession()->setFlash('error', $err_msg);
        return $this->redirect(['index']);

    }
    /**
     * Finds the DcmdNodeGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdNodeGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdNodeGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     *Get gid-gname
     */
    protected function getGroups() {
        $ret = DcmdGroup::findBySql("select gid, gname from dcmd_group where gtype=1")->asArray()->all();
        $groupId = array();
        foreach($ret as $gid) {
         $groupId[$gid['gid']] = $gid['gname'];
        }
        return $groupId;
    }
}
