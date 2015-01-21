<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdRepeatExec */

$this->title = '修改重复操作 ' . ' ' . $model->repeat_cmd_name;
$this->params['breadcrumbs'][] = ['label' => '重复操作', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->repeat_cmd_name, 'url' => ['view', 'id' => $model->repeat_cmd_id]];
$this->params['breadcrumbs'][] = '修改重复操作';
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

<div class="dcmd-opr-cmd-repeat-exec-update">
<div class="dcmd-opr-cmd-repeat-exec-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'repeat_cmd_name')->textInput(['maxlength' => 64, "disabled"=>"true"])->label('重复操作名称') ?>

    <?= $form->field($model, 'opr_cmd')->textInput(["disabled"=>"true"])->label('脚本名称') ?>

    <?= $form->field($model, 'timeout')->textInput()->label('超时s') ?>

    <?= $form->field($model, 'cache_time')->textInput()->label('Cache时间s') ?>

    <?= $form->field($model, 'ip')->textarea(['rows' => 3])->label('操作IP(多个使用;分割)') ?>

    <?= $form->field($model, 'repeat')->dropDownList(array('0'=>'不是', '1'=>'是但不记录历史', '2'=>'是并记录每次执行历史'))->label('是否可重复') ?>

    <?= $form->field($model, 'ip_mutable')->dropDownList(array('0'=>'否', '1'=>'是'))->label('IP是否可替换')?>

    <?= $form->field($model, 'arg_mutable')->dropDownList(array('0'=>'否', '1'=>'是'))->label('参数是否可替换')?>

    <div class="form-group field-dcmdoprcmdrepeat-arg">
    <label class="arg" for="dcmdoprcmdrepeat-arg">任务脚本参数</label>
    <div id="taskTypeArgDiv" style="width:100%"></div>
    <?php echo $arg_content; ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton( '更新', ['class' => 'btn btn-primary'])?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
</div>
