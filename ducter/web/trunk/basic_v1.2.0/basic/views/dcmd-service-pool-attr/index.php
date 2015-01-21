<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolAttrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Service Pool Attrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-attr-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Service Pool Attr', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'app_id',
            'svr_id',
            'svr_pool_id',
            'attr_name',
            // 'attr_value',
            // 'comment',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
