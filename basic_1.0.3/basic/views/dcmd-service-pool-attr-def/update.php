<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolAttrDef */

$this->title = '修改服务池属性: ' . ' ' . $model->attr_name;
$this->params['breadcrumbs'][] = ['label' => '服务池属性', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->attr_name, 'url' => ['view', 'id' => $model->attr_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="dcmd-service-pool-attr-def-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
