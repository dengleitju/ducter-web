<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskServicePoolAttr */

$this->title = 'Update Dcmd Task Service Pool Attr: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Task Service Pool Attrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-task-service-pool-attr-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
