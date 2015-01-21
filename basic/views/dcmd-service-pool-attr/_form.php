<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolAttr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-attr-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'attr_name')->textInput(['maxlength' => 32, 'disabled'=>true])->label('属性名') ?>

    <?= $form->field($model, 'attr_value')->textInput(['maxlength' => 256])->label('属性值') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton('修改', ['btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
