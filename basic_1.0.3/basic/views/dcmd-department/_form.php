<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdDepartment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-department-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'depart_name')->label('部门')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
