<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdOprCmdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '操作';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-index">

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
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'ui_name', 'label'=>'操作名称', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $colum){return Html::a($model['ui_name'], Url::to(['dcmd-opr-cmd/view', 'id'=>$model['opr_cmd_id']]));}),
            array('attribute'=>'opr_cmd', 'label'=>'指令名称', 'enableSorting'=>false),
            array('attribute'=>'run_user', 'label'=>'运行用户', 'enableSorting'=>false, 'filter'=>false),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success' , (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
