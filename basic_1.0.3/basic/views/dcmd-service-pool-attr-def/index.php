<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolAttrDefSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务池属性';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/ducter/index.php?r=dcmd-service-pool-attr-def/delete-all" method="post">
<div class="dcmd-service-pool-attr-def-index">
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
            array('attribute'=>'attr_name', 'label'=>'属性名', 'enableSorting'=>false, 'content'=> function($model, $key, $index, $column) { return Html::a($model['attr_name'],Url::to(['dcmd-service-pool-attr-def/view', 'id'=>$model['attr_id']]));}),
            array('attribute'=>'optional', 'label'=>'是否可选', 'filter'=>array(0=>"否", 1=>"是"), 'enableSorting'=>false,  'content'=> function($model, $key, $index, $column) { if($model['optional'] ==0) return "否"; return "是"; }),
            array('attribute'=>'attr_type', 'label'=>'属性类型', 'filter'=>array(1=>"int", 2=>"float", 3=>"bool", 4=>"char", 5=>"datetime"), 'enableSorting'=>false, 'content'=> function($model, $key, $index, $column) { if($model['attr_type'] == 1) return "int"; if($model['attr_type'] == 2) return "float"; if($model['attr_type'] == 3) return "bool"; if($model['attr_type'] == 4) return "char"; if($model['attr_type'] == 5) return "datetime"; return "";}),
            array('attribute'=>'def_value', 'label'=>'值', 'filter'=>false, 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false
],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
        &nbsp;&nbsp;
   <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
</form>
