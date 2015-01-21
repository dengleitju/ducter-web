<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolAttrDef */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-attr-def-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'attr_name')->textInput(['maxlength' => 32, "disabled"=>$model['attr_name']?true:false])->label('属性名') ?>

    <?= $form->field($model, 'optional')->label('是否可选')->dropDownList(array(0=>"否", 1=>"是")) ?>

    <?= $form->field($model, 'attr_type')->label('类型')->dropDownList(array(1=>"int", 2=>"float", 3=>"bool", 4=>"char", 5=>"datetime")) ?>

    <?= $form->field($model, 'def_value')->textInput(['maxlength' => 256])->label('默认值') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
