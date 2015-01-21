<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdOprCmdArgSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Opr Cmd Args';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-arg-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Opr Cmd Arg', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'opr_cmd_id',
            'arg_name',
            'optional',
            'arg_type',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
