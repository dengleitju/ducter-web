<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroupAttr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-node-group-attr-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'attr_name')->label("属性名")->textInput(['maxlength' => 32, 'disabled'=>true]) ?>

    <?= $form->field($model, 'attr_value')->textInput(['maxlength' => 256])->label('属性值') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
