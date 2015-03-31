<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务池列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/ducter/index.php?r=dcmd-service-pool/delete-all" method="post">
<div class="dcmd-service-pool-index">
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
            array('attribute'=>'app_id','label'=>'产品', 'enableSorting'=>false,'filter'=>$app, 'content' => function($model, $key, $index, $column) { return Html::a($model->getAppName($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'svr_id','label'=>'服务','filter'=>$svr, 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model->getServiceName($model['svr_id']), Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));}),
            array('attribute'=>'svr_pool','label'=>'服务池','enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model['svr_pool'], Url::to(['view', 'id'=>$model['svr_pool_id']]));}),
            array('attribute'=>'env_ver','label'=>'环境版本'),
        ],
    ]); ?>
<?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"])?>
</div>
</form>
