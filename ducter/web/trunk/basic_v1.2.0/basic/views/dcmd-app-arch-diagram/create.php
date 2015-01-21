<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdAppArchDiagram */

$this->title = '添加架构图';
$this->params['breadcrumbs'][] = ['label' => $app_name, 'url' => ['dcmd-app/view', 'id' => $app_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-app-arch-diagram-create">
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
        'app_id' => $app_id,
    ]) ?>

</div>
