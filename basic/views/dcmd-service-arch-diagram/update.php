<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServiceArchDiagram */

$this->title = 'Update Dcmd Service Arch Diagram: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Service Arch Diagrams', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-service-arch-diagram-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
