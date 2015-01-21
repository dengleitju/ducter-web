<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdDepartment */

$this->title = '修改部门: ' . ' ' . $model->depart_name;
$this->params['breadcrumbs'][] = ['label' => '部门', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->depart_name, 'url' => ['view', 'id' => $model->depart_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="dcmd-department-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
