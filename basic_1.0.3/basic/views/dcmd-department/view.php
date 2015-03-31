<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdDepartment */

$this->title = "部门详细信息";
$this->params['breadcrumbs'][] = ['label' => '部门', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-department-view">
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
    <div >
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
             'attribute' => 'depart_name',
             'format' => 'raw',
             'label' => '部门名称',
            ],
            'comment:text:描述',
            'utime:text:更新时间',
            'ctime:text:创建时间',
        ],
    ]) ?>
    </div>
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->depart_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1 && Yii::$app->user->getIdentity()->sa == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
