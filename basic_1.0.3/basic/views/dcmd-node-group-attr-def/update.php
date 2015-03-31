<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroupAttrDef */

$this->title = '修改设备池属性: ' . ' ' . $model->attr_name;
$this->params['breadcrumbs'][] = ['label' => '设备池属性', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->attr_name, 'url' => ['view', 'id' => $model->attr_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-node-group-attr-def-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
