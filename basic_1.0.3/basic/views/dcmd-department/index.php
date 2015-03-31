<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdDepartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '部门管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-department-index">
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

            array('attribute'=>'depart_name', 'label'=>'部门名称', 'content'=> function($model, $key, $index, $column) { return Html::a($model['depart_name'],Url::to(['dcmd-department/view', 'id'=>$model['depart_id']]));} ),
            array('attribute'=>'comment', 'label'=>'分组描述' ,'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'utime', 'label'=>'修改时间','filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'ctime', 'label'=>'创建时间','filter'=>false, 'enableSorting'=>false),

            ['class' => 'yii\grid\ActionColumn', "visible"=>(Yii::$app->user->getIdentity()->admin == 1 && Yii::$app->user->getIdentity()->sa == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 && Yii::$app->user->getIdentity()->sa == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
