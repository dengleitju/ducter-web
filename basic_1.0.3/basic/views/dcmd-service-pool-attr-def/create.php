<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolAttrDef */

$this->title = '添加服务池属性';
$this->params['breadcrumbs'][] = ['label' => '服务池属性', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-attr-def-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
