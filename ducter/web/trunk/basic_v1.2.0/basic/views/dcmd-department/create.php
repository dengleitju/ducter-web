<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdDepartment */

$this->title = '添加部门';
$this->params['breadcrumbs'][] = ['label' => '部门列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-department-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
