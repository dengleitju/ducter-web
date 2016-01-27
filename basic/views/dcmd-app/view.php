<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */

$this->title = $model->app_name;
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-app-view">
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
<div class="dcmd-app-view" id="dcmd-app">
    <p>
        <?= Html::a('更新', ['update', 'id' => $model->app_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'app_name', 'label'=>'产品名称'),
            array('attribute'=>'app_alias', 'label'=>'产品别名'),
            array('attribute'=>'sa_gid','label'=>'系统组', 'value'=>$model->userGroupName($model['sa_gid'])),
            array('attribute'=>'svr_gid', 'label'=>'业务组', 'value'=>$model->userGroupName($model['svr_gid'])),
            array('attribute'=>'depart_id', 'label'=>'部门', 'value'=>$model->department($model['depart_id'])),
            array('attribute'=>'comment', 'label'=>'说明', 'value'=>$model->comment($model['comment']), 'format'=>'html'),
        ],
    ]) ?>
</div>
<div class="dcmd-app-diagram-view" id="dcmd-app-diagram">
     <p> 
       <?= Html::a('添加', ['dcmd-app-arch-diagram/create', 'app_id' => $model->app_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $imageProvider,
        'filterModel' => NULL,
        'layout' => "{items}",
        'columns' => [
            array('attribute'=>'arch_name', 'label'=>'架构图名称', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $col) { return Html::a($model['arch_name'], '/ducter/app_image/app_'.$model['arch_name'].'_'.$model['app_id'].'.jpg', ['target'=>'blank']);}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {return Url::to(['dcmd-app-arch-diagram/delete', 'id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false ],
        ],
    ]); ?>
</div>

    <p> 
       <?= Html::a('添加', ['dcmd-service/create', 'app_id' => $model->app_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'svr_name', 'label'=>'服务名称', 'enableSorting'=>false,'content'=>function($model, $key, $index, $column) { return Html::a($model['svr_name'], Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));}),
            array('attribute'=>'svr_alias', 'label'=>'服务别名', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return Html::a($model['svr_alias'], Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service/delete', 'id'=>$model['svr_id'], 'app_id'=>$model['app_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false ],
        ],
    ]); ?>


</div>
