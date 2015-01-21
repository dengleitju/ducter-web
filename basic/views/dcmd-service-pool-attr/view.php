<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolAttr */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Service Pool Attrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-attr-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'app_id',
            'svr_id',
            'svr_pool_id',
            'attr_name',
            'attr_value',
            'comment',
            'utime',
            'ctime',
            'opr_uid',
        ],
    ]) ?>

</div>
