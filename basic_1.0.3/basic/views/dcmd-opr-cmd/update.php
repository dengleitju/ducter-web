<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmd */

$this->title = '修改操作: ' . ' ' . $model->ui_name;
$this->params['breadcrumbs'][] = ['label' => '操作命令', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ui_name, 'url' => ['view', 'id' => $model->opr_cmd_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="dcmd-opr-cmd-update">


    <?= $this->render('_form2', [
        'model' => $model,
    ]) ?>

</div>
