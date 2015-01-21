<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskServicePoolAttr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-service-pool-attr-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_id')->textInput() ?>

    <?= $form->field($model, 'app_id')->textInput() ?>

    <?= $form->field($model, 'svr_id')->textInput() ?>

    <?= $form->field($model, 'svr_pool_id')->textInput() ?>

    <?= $form->field($model, 'attr_name')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'attr_value')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => 512]) ?>

    <?= $form->field($model, 'utime')->textInput() ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'opr_uid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
