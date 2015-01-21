<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroupCmd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-group-cmd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'gid')->dropDownList($group) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
