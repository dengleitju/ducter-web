<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdUser */
/* @var $form yii\widgets\ActiveForm */
$this->title = '修改密码:' . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->uid]];
$this->params['breadcrumbs'][] = '修改密码';
?>

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

<div class="dcmd-user-form">

   <form id="w0" action="/ducter/index.php?r=dcmd-user/change-passwd" method="post">
   
   <label class="control-label" for="dcmduser-oldpasswd">当前密码</label>

   <input type="password" id="oldpasswd" class="form-control" name="oldpasswd" maxlength="32" >

   <div class="help-block"></div>

   <label class="control-label" for="dcmduser-oldpasswd">新密码</label>

   <input type="password" id="newpasswd" class="form-control" name="newpasswd" maxlength="32" >

   <div class="help-block"></div>
 
   <label class="control-label" for="dcmduser-oldpasswd">再次输入新密码</label>

   <input type="password" id="repeat_newpasswd" class="form-control" name="repeat_newpasswd" maxlength="32" >
 
  <div class="help-block"></div>

  <div class="form-group">
      <?= Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
  </div>
  </form>

</div>
