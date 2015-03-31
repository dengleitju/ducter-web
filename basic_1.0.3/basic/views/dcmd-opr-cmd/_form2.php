<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-opr-cmd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ui_name')->label('命令名称')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'opr_cmd')->label('脚本名称')->textInput(['maxlength' => 64,'readonly'=>true, 'onblur' => "javascript:getOprScriptContent()"]) ?>
    <p>
      <button type="button"  onclick="javascript:getOprScriptContent()" class="btn btn-success">加载</button>
    </p>

    <?= $form->field($model, 'run_user')->label('运行用户')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'script_md5')->label('MD5')->textInput(['maxlength' => 32, 'readonly'=>true]) ?>

    <?= $form->field($model, 'timeout')->label('超时')->textInput() ?>

    <?= $form->field($model, 'comment')->label('说明')->textarea(['maxlength' => 512]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div style="height: auto; width: 800px; background-color: #000; color: #FFF; padding: 10px 3px 10px 10px">
 操作脚本内容:
 <div id="ShellContent" style="margin: 10px 0px 10px 10px; overflow-y: auto; height: auto; overflow-x: hidden">
  <div style=""></div>
 </div>
</div>
<script>
var getOprScriptContent = function () {
         task_cmd=$('#dcmdoprcmd-opr_cmd').val();
         $.post("?r=dcmd-opr-cmd/load-content", { "opr_cmd":task_cmd }, function (data, status) {
                                status == "success" ? function () {
                                        var dataO = jQuery.parseJSON(data); 
                                        $('#ShellContent').html(dataO.result);
                                        $('#dcmdoprcmd-script_md5').val(dataO.md5);            
                                } () : "";
                        }, "text");
};
</script>
