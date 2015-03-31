<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroup */

$this->title = 'Add User Group';
$this->params['breadcrumbs'][] = ['label' => '用户组', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-group-create">
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
        'disabled' => '',
    ]) ?>

</div>
