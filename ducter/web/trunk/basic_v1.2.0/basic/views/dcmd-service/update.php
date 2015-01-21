<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */

$this->title = '修改服务: ' . ' ' . $model->svr_id;
$this->params['breadcrumbs'][] = ['label' => '服务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->svr_name, 'url' => ['view', 'id' => $model->svr_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-service-update">
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
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
