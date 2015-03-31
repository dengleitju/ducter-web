<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '设备列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
function convert() {
  $("#w0").attr("action", "/ducter/index.php?r=dcmd-node/convert");
}
function opr() {
  $("#w0").attr("action", "/ducter/index.php?r=dcmd-node/opr");
}
function repeatopr() {
  $("#w0").attr("action", "/ducter/index.php?r=dcmd-node/repeat-opr");
}
</script>
<form id="w0" action="/ducter/index.php?r=dcmd-node/delete-all" method="post">
<div class="dcmd-node-index">

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
            array('attribute'=>'ip', 'label' => '服务器IP', 'content'=> function($model, $key, $index, $column) { return Html::a($model['ip'], Url::to(['dcmd-node/view', 'id'=>$model['nid']]));}),
            array('attribute'=>'ngroup_id', 'label'=>'设备池','enableSorting'=>false, 'filter'=>$dcmd_node_group, 'content' => function($model, $key, $index, $column) { return Html::a($model->getNodeGname($model['ngroup_id']), Url::to(['dcmd-node-group/view', 'id'=>$model['ngroup_id']]));}),
            array('attribute'=>'host', 'label'=>'服务器名', 'enableSorting'=>false),
            array('attribute'=>'ip', 'label'=>'连接状态','filter'=>false, 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return $model->getAgentState($model['ip']);}),
            array('attribute'=>'did', 'label'=>'设备序列号', 'enableSorting'=>false),
            array('attribute'=>'sid', 'label'=>'资产序列号', 'enableSorting'=>false),
            array('attribute'=>'online_time', 'label'=>'上线时间', 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('变更设备池', ['class' =>'btn btn-success', 'onClick'=>"convert()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('操作', ['class' =>'btn btn-success', 'onClick'=>"opr()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('重复操作', ['class' =>'btn btn-success', 'onClick'=>"repeatopr()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>

</div>
</form>
