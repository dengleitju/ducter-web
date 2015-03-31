<?php

namespace app\controllers;

use Yii;
use app\models\DcmdNodeGroupAttr;
use app\models\DcmdNodeGroupAttrSearch;
use app\models\DcmdNodeGroupAttrDef;
use app\models\DcmdNodeGroup;
use app\models\DcmdUserGroup;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DcmdNodeGroupAttrController implements the CRUD actions for DcmdNodeGroupAttr model.
 */
class DcmdNodeGroupAttrController extends Controller
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
     * Lists all DcmdNodeGroupAttr models.
     * @return mixed
     */
    private function actionIndex()
    {
        $searchModel = new DcmdNodeGroupAttrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DcmdNodeGroupAttr model.
     * @param integer $id
     * @return mixed
     */
    private function actionView($id)
    {
        $model = $this->findModel($id);
        $group = DcmdNodeGroup::findOne($model->ngroup_id);
        return $this->render('view', [
            'model' => $model,
            'group' => $group,
        ]);
    }

    /**
     * Creates a new DcmdNodeGroupAttr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($attr_id, $ngroup_id)
    {
        $def_attr = DcmdNodeGroupAttrDef::findOne($attr_id);
        $query = DcmdNodeGroupAttr::findOne(['attr_name'=>$def_attr->attr_name]);
        if($query) return $this->actionUpdate($query->id, $ngroup_id);
        $model = new DcmdNodeGroupAttr();
        $model->ngroup_id = $ngroup_id;
        $model->attr_name = $def_attr->attr_name;
        $model->attr_value = $def_attr->def_value;
        $model->comment = $def_attr->comment;
        $model->utime = date('Y-m-d H:i:s');
        $model->ctime = $model->utime;
        $model->opr_uid = Yii::$app->user->getId(); 
        $model->save();
        return $this->actionUpdate($model->id, $ngroup_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DcmdNodeGroupAttr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $ngroup_id)
    {
        $node_group = DcmdNodeGroup::findOne($ngroup_id);
        ///仅仅用户与该应用在同一个系统组才可以操作
        $query = DcmdUserGroup::findOne(['uid'=>Yii::$app->user->getId(), 'gid'=>$node_group->gid]);
        if($query==NULL) {
          Yii::$app->getSession()->setFlash('success', NULL);
          Yii::$app->getSession()->setFlash('error', "对不起, 你没有权限!");
          return $this->redirect(['/dcmd-node-group/view', 'id'=>$ngroup_id, 'show_div'=>'dcmd-node-group-attr']);
        }
        if($id == 0) { ///需要新建
          $attr_id = Yii::$app->request->queryParams['attr_id'];
          $def_attr = DcmdNodeGroupAttrDef::findOne($attr_id);
          $query = DcmdNodeGroupAttr::findOne(['ngroup_id'=>$ngroup_id, 'attr_name'=>$def_attr->attr_name]);
          if(count($query) > 0) {
            $id = $query['id'];
          }else{
            $model = new DcmdNodeGroupAttr();
            $model->ngroup_id = $ngroup_id;
            $model->attr_name = $def_attr->attr_name;
            $model->attr_value = $def_attr->def_value;
            $model->comment = $def_attr->comment;
            $model->utime = date('Y-m-d H:i:s');
            $model->ctime = $model->utime;
            $model->opr_uid = Yii::$app->user->getId();
            $model->save();
            $id = $model->id;
          }
        }
        $model = $this->findModel($id);
        $group = DcmdNodeGroup::findOne($model->ngroup_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '修改成功!');
            return $this->redirect(['/dcmd-node-group/view', 'id'=>$group->ngroup_id, 'show_div'=>'dcmd-node-group-attr']);; 
        } else {
            return $this->render('update', [
                'model' => $model,
                'group'=>$group,
            ]);
        }
    }

    /**
     * Deletes an existing DcmdNodeGroupAttr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    private function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DcmdNodeGroupAttr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DcmdNodeGroupAttr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DcmdNodeGroupAttr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
