<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdGroupCmdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户组操作';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-group-cmd-index">


    <p>
        <?= Html::a('添加组', ['add-group'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'gid',
            'opr_cmd_id',
            'utime',
            'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
