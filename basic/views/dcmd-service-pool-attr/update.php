<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolAttr */

$this->title = '修改服务池属性: ' . ' ' . $model->attr_name;
$this->params['breadcrumbs'][] = ['label' => '服务池属性', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->attr_name;
?>
<div class="dcmd-service-pool-attr-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
