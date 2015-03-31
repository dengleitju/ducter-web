<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdUser */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-user-view">


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

    <div style="background:#f1f1f1;padding:10px;margin-top:10px">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'username','label'=>'用户名'),
            array('attribute'=>'admin', 'label'=>'sa', 'value'=>$model->convert($model['admin'])),
            array('attribute'=>'sa', 'label'=>'admin', 'value'=>$model->convert($model['sa'])),
            array('attribute'=>'depart_id','label'=>'部门', 'value'=>$model->getDepartment($model['depart_id'])),
            array('attribute'=>'tel', 'label'=>'Tel'),
            'email:email',
            array('attribute'=>'comment', 'label'=>'说明'),
            array('attribute'=>'utime', 'label'=>'更新时间'),
            array('attribute'=>'ctime', 'label'=>'创建时间'),
            array('attribute'=>'opr_uid', 'label'=>'修改者'),
        ],
    ]) ?>
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->uid], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1 && Yii::$app->user->getIdentity()->sa == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => NULL, ///$searchModel,
        ////'showHeader' => false,
        'columns' => [
            array('attribute'=>'gname', 'label'=>'用户组', 'content'=>function($model, $key, $index, $column) { return  Html::a($model['gname'], Url::to(['dcmd-group/view', 'id'=>$model['gid']]));}, 'filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'gtype', 'label'=>'组类型', 'value'=>function($model, $key, $index, $column) { if($model['gtype'] == 1) return "系统组"; else return "业务组";}, 'filter'=>false, 'enableSorting'=>false),
        ],
    ]); ?>

</div>
