<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/ducter/index.php?r=dcmd-task/finish-task" method="post">
<div class="dcmd-task-index">
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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'task_name','label'=>"任务名称", 'enableSorting'=>false,'content'=>function($model, $key, $index, $colum){return Html::a($model['task_name'], Url::to(['dcmd-task-async/monitor-task', 'task_id'=>$model['task_id']]), ["target"=>"_blank"]);}),
            array('attribute'=>'task_cmd','label'=>'任务脚本名称', 'enableSorting'=>false,),
            array('attribute'=>'app_name', 'label'=>'产品名称', 'enableSorting'=>false),
            array('attribute'=>'svr_name', 'label'=>'服务名称', 'enableSorting'=>false,),
            array('attribute'=>'freeze', 'label'=>'是否冻结','filter'=>array( 0=>"否", 1=>"是"), 'content'=>function($model, $key, $index, $colum) { if($model['freeze'] == 0) return "否"; else return "是";}),
            array('attribute'=>'state', 'label'=>'状态', 'enableSorting'=>false, 'filter'=>array(0=>"未执行", 1=>"正在做", 2=>"达到失败上限", 4=>"完成但有失败机器"), 'content'=>function($model, $key, $index, $colum) {if($model['state'] ==0)return "未执行"; if($model['state'] == 1) return "正在做"; if($model['state'] == 2) return "达到失败上限"; if($model['state'] == 3) return "完成"; if($model['state']==4) return "完成但有失败机器"; return ""; } ),
            array('attribute'=>'opr_uid', 'label'=>'创建者','filter'=>array(""=>"全部", $uid=>"仅自己"), 'enableSorting'=>false,'content'=>function($model, $key, $index, $colum) { return $model->getUserName($model['opr_uid']);}),
            array('attribute'=>'ctime', 'label'=>'创建时间','enableSorting'=>false, 'filter'=>false,),
        ],
    ]); ?>
    <button  type='submit' class="btn btn-success">完成任务</button>
</div>
</form>
