<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskServicePoolAttr */

$this->title = 'Create Dcmd Task Service Pool Attr';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Task Service Pool Attrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-service-pool-attr-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
