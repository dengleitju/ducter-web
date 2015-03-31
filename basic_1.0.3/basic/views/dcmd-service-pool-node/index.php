<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务池设备';
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
function opr() {
  $("#w0").attr("action", "/ducter/index.php?r=dcmd-service-pool-node/opr");
}
function repeatopr() {
  $("#w0").attr("action", "/ducter/index.php?r=dcmd-service-pool-node/repeat-opr");
}
</script>
<form id="w0" action="/ducter/index.php?r=dcmd-service-pool-node/delete-all" method="post">
<div class="dcmd-service-pool-node-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    if( Yii::$app->getSession()->hasFlash('success') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success', //这里是提示框的class
            ],
            'body' => Yii::$app->getSession()->getFlash('success'), //消息体
        ]);
    }
    if( Yii::$app->getSession()->hasFlash('error') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => "<font color=red>".Yii::$app->getSession()->getFlash('error')."</font>",
        ]);
    }
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'ip','label'=>'IP','enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model['ip'], Url::to(['dcmd-node/view', 'id'=>$model['nid']]));},),
            array('attribute'=>'app_id', 'label'=>'产品别名','filter'=>$app, 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model->getAppAlias($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));},),
            array('attribute'=>'svr_id', 'label'=>'服务别名','enableSorting'=>false, 'filter'=>$svr, 'content' => function($model, $key, $index, $column) { return Html::a($model->getServiceAlias($model['svr_id']), Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));},),
            array('attribute'=>'svr_pool_id', 'label'=>'服务池','enableSorting'=>false, 'filter'=>$svr_pool, 'content' => function($model, $key, $index, $column){
 return Html::a($model->getServicePoolName($model['svr_pool_id']), Url::to(['dcmd-service-pool/view', 'id'=>$model['svr_pool_id']]));},),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"])?>&nbsp;&nbsp;
        <?= Html::submitButton('操作', ['class' =>'btn btn-success', 'onClick'=>"opr()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('重复操作', ['class' =>'btn btn-success', 'onClick'=>"repeatopr()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
</div>
</form>
