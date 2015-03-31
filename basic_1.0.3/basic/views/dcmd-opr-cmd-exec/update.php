<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdExec */

$this->title = 'Update Dcmd Opr Cmd Exec: ' . ' ' . $model->exec_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Opr Cmd Execs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->exec_id, 'url' => ['view', 'id' => $model->exec_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-opr-cmd-exec-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
