<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="/ducter/index.php?r=dcmd-service/delete-all" method="post">
<div class="dcmd-service-index">
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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'svr_name', 'label'=>'服务名称','content' => function($model, $key, $index, $column) { return Html::a($model['svr_name'], Url::to(['view', 'id'=>$model['svr_id']]));}),
            array('attribute'=>'svr_alias', 'label'=>'服务别名','content' => function($model, $key, $index, $column) { return Html::a($model['svr_alias'], Url::to(['view', 'id'=>$model['svr_id']]));}),
            array('attribute'=>'app_id', 'label'=>'产品名称', 'enableSorting'=>false, 'filter'=>$app, 'content' => function($model, $key, $index, $column) { return Html::a($model->getAppName($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'app_id', 'label'=>'产品别名', 'filter'=>false, 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model->getAppAlias($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));}),
            ['class' => 'yii\grid\ActionColumn',"visible"=>(Yii::$app->user->getIdentity()->admin == 1 ) ? true : false],
        ],
    ]); ?>
    <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
</div>
</form>
