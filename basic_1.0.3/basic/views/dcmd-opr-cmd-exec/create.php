<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdExec */

$this->title = 'Create Dcmd Opr Cmd Exec';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Opr Cmd Execs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-exec-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
