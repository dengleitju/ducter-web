<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServiceArchDiagram */

$this->title = '添加架构图';
$this->params['breadcrumbs'][] = ['label' => $service->svr_name, 'url' => ['dcmd-service/view', "id"=>$service->svr_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-arch-diagram-create">
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
        'service' => $service,
    ]) ?>

</div>
