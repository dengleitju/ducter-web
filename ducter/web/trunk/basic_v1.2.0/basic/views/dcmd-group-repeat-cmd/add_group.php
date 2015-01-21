<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroupCmd */

$this->title = '授权用户组组';
$this->params['breadcrumbs'][] = ['label' => $repeat_cmd->repeat_cmd_name, 'url' => ['dcmd-opr-cmd-repeat-exec/view', 'id'=>$repeat_cmd->repeat_cmd_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-group-cmd-create">
<div class="dcmd-group-cmd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'gid')->checkboxList($group)->label('业务组')?>

    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>
