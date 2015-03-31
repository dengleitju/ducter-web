<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServiceArchDiagramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Service Arch Diagrams';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-arch-diagram-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Service Arch Diagram', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'app_id',
            'svr_id',
            'arch_name',
            'diagram:ntext',
            // 'comment',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
