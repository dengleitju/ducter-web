<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdRepeatExec */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-opr-cmd-repeat-exec-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'repeat_cmd_name')->textInput(['maxlength' => 64])->label('重复操作名称') ?>

    <?= $form->field($model, 'opr_cmd')->dropDownList($opr_cmd, ["onchange"=>"javascript:oprCmdArg()"])->label('脚本名称') ?>

    <?= $form->field($model, 'timeout')->textInput()->label('超时s') ?>

    <?= $form->field($model, 'cache_time')->textInput()->label('Cache时间s') ?>

    <?= $form->field($model, 'ip')->textarea(['rows' => 3])->label('操作IP(多个使用;分割)') ?>

    <?= $form->field($model, 'repeat')->dropDownList(array( '1'=>'不记录历史', '2'=>'记录每次执行历史'))->label('是否记录历史') ?>

   <div class="form-group field-dcmdoprcmdrepeatexec-ip_mutable required">
   <input type="hidden" name="DcmdOprCmdRepeatExec[ip_mutable]" value="0"><label><input type="checkbox" id="dcmdoprcmdrepeatexec-ip_mutable" name="DcmdOprCmdRepeatExec[ip_mutable]" value="1">是否IP可替换</label> &nbsp;&nbsp;&nbsp;
   <input type="hidden" name="DcmdOprCmdRepeatExec[arg_mutable]" value="0"><label><input type="checkbox" id="dcmdoprcmdrepeatexec-arg_mutable" name="DcmdOprCmdRepeatExec[arg_mutable]" value="1">是否参数可替换</label>
   
   <div class="help-block"></div>
   </div>

    <div class="form-group field-dcmdoprcmdrepeatexec-arg">
    <label class="task_arg" for="dcmdoprcmdrepeatexec-arg">任务脚本参数</label>
    <div id="oprArgDiv" style="width:100%"></div>
    </div>
    <div class="form-group">
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>

var oprCmdArg = function(){
  opr_cmd=$('#dcmdoprcmdrepeatexec-opr_cmd').val();
  $('#oprArgDiv').load("?r=dcmd-opr-cmd-repeat-exec/get-opr-cmd-arg&opr_cmd="+opr_cmd);
}

</script>
