<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdOprCmdRepeatExecSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '重复操作';
$this->params['breadcrumbs'][] = $this->title;
?>
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
<div class="dcmd-opr-cmd-repeat-exec-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            array('attribute'=>'repeat_cmd_name', 'label'=>'重复操作名称', 'enableSorting'=>false,'content'=> function($model, $key, $index, $column) { return Html::a($model['repeat_cmd_name'],Url::to(['view', 'id'=>$model['repeat_cmd_id']]));}),
            array('attribute'=>'opr_cmd', 'label'=>'脚本名称', 'enableSorting'=>false),
            array('attribute'=>'arg_mutable', 'label'=>'参数可修改', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { if($model['arg_mutable'] == 1) return "是"; return "否";}),
            array('attribute'=>'ip_mutable', 'label'=>'主机可修改','enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { if($model['ip_mutable'] == 1) return "是"; return "否";}),
            array('attribute'=>'run_user', 'label'=>'运行用户', 'enableSorting'=>false, 'filter'=>false,),

            ['class' => 'yii\grid\ActionColumn',  "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
     <?= Html::a('添加', ['create'], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
