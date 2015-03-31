<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdOprCmdExecSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Opr Cmd Execs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-exec-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Opr Cmd Exec', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'exec_id',
            'opr_cmd_id',
            'opr_cmd',
            'run_user',
            'timeout:datetime',
            // 'ip:ntext',
            // 'arg:ntext',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
