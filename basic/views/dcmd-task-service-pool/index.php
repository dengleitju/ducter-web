<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskServicePoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务服务池';
$this->params['breadcrumbs'][] = ['label' => $task->task_name, 'url' => ['dcmd-task-async/monitor-task', 'task_id'=>$task->task_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-service-pool-index">
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
    <p>
        <?= Html::a('添加服务池', ['create', 'task_id'=>$task->task_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            array('attribute'=>'task_cmd', 'label'=>'任务脚本', 'enableSorting'=>false,),
            array('attribute'=>'svr_pool', 'label'=>'服务池', 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model['svr_pool'], Url::to(['dcmd-service-pool/view', 'id'=>$model['svr_pool_id']]));}),
             ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>

</div>
