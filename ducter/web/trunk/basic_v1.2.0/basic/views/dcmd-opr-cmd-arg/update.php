<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdArg */

$this->title = '修改参数: ' . ' ' . $model->arg_name;
$this->params['breadcrumbs'][] = ['label' => $opr->ui_name, 'url' => ['dcmd-opr-cmd/view', 'id'=>$opr->opr_cmd_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="dcmd-opr-cmd-arg-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
