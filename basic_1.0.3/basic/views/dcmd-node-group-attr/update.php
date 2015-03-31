<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroupAttr */

$this->title = '修改设备组属性: ' . ' ' . $model->attr_name;
$this->params['breadcrumbs'][] = ['label' => $group->ngroup_name, 'url' => ['dcmd-node-group/view', 'id' => $group->ngroup_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="dcmd-node-group-attr-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
