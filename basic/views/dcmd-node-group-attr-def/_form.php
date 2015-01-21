<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroupAttrDef */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-node-group-attr-def-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'attr_name')->label('属性名')->textInput(['maxlength' => 32, "disabled"=>$model['attr_name']?true:false]) ?>

    <?= $form->field($model, 'optional')->label('是否可选')->dropDownList(array(0=>"否", 1=>"是")) ?>

    <?= $form->field($model, 'attr_type')->label('类型')->dropDownList(array(1=>"int", 2=>"float", 3=>"bool", 4=>"char", 5=>"datetime")) ?>

    <?= $form->field($model, 'def_value')->textInput(['maxlength' => 256])->label('默认值') ?>

    <?= $form->field($model, 'comment')->textArea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
