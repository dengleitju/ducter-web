<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdExecSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-opr-cmd-exec-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'exec_id') ?>

    <?= $form->field($model, 'opr_cmd_id') ?>

    <?= $form->field($model, 'opr_cmd') ?>

    <?= $form->field($model, 'run_user') ?>

    <?= $form->field($model, 'timeout') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'arg') ?>

    <?php // echo $form->field($model, 'utime') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'opr_uid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
