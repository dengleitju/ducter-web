<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroupAttrDef */

$this->title = '添加设备组属性';
$this->params['breadcrumbs'][] = ['label' => '设备组属性', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-group-attr-def-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
