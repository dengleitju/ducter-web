<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskServicePool */

$this->title = '添加任务服务池';
$this->params['breadcrumbs'][] = ['label' => $task->task_name,'url' => ['dcmd-task-async/monitor-task', 'task_id'=>$task->task_id]];
$this->params['breadcrumbs'][] = ['label' => '服务池', 'url' => ['index','task-id'=>$task->task_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/ducter/index.php?r=dcmd-task-service-pool/add" method="post">
<div class="dcmd-task-service-pool-create">

<?= Html::submitButton('添加', ['class' =>'btn btn-success'])?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'app_id','label'=>'产品', 'enableSorting'=>false,'filter'=>$app, 'content' => function($model, $key, $index, $co
lumn) { return Html::a($model->getAppName($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'svr_id','label'=>'服务','filter'=>$svr, 'enableSorting'=>false, 'content' => function($model, $key, $index, $co
lumn) { return Html::a($model->getServiceName($model['svr_id']), Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));}),
            array('attribute'=>'svr_pool','label'=>'服务池','enableSorting'=>false, 'content' => function($model, $key, $index, $column) { retu
rn Html::a($model['svr_pool'], Url::to(['view', 'id'=>$model['svr_pool_id']]));}),
            array('attribute'=>'env_ver','label'=>'环境版本'),
        ],
    ]); ?>

</div>
</form>
