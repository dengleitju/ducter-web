<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdExec */

$this->title = $model->exec_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Opr Cmd Execs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-exec-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->exec_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->exec_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'exec_id',
            'opr_cmd_id',
            'opr_cmd',
            'run_user',
            'timeout:datetime',
            'ip:ntext',
            'arg:ntext',
            'utime',
            'ctime',
            'opr_uid',
        ],
    ]) ?>

</div>
