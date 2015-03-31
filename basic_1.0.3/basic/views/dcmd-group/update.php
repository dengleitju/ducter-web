<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroup */

$this->title = '用户组: ' . ' ' . $model->gname;
$this->params['breadcrumbs'][] = ['label' => '用户组', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gname, 'url' => ['view', 'id' => $model->gid]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="dcmd-group-update">

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
        'disabled' => 'disabled',
    ]) ?>

</div>
