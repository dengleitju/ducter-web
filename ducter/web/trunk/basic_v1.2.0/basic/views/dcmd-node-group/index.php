<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdNodeGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '设备池';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/ducter/index.php?r=dcmd-node-group/delete-all" method="post">
<div class="dcmd-node-group-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    if( Yii::$app->getSession()->hasFlash('success') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success', //这里是提示框的class
            ],
            'body' => Yii::$app->getSession()->getFlash('success'), //消息体
        ]);
    }
    if( Yii::$app->getSession()->hasFlash('error') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => "<font color=red>".Yii::$app->getSession()->getFlash('error')."</font>",
        ]);
    }
    ?>
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;
        <?= Html::submitButton('删除', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> 
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute' => 'ngroup_name',  'label'=>'设备池', 'content'=>function($model, $key, $index, $column) {return Html::a($model['ngroup_name'], Url::to(['dcmd-node-group/view', 'id'=>$model['ngroup_id']]));}),
            array('attribute' => 'gid', 'content'=>function($model, $key, $index, $column) { return Html::a($model->getGname($model['gid']), Url::to(['dcmd-group/view', 'id'=>$model['gid']]));}, 'label' => '系统组', 'filter'=>$groupId),
	['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>

</div>
</form>
