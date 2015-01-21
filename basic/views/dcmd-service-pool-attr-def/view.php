<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolAttrDef */

$this->title = $model->attr_name;
$this->params['breadcrumbs'][] = ['label' => '服务池属性', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-attr-def-view">

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
        <?= Html::a('修改', ['update', 'id' => $model->attr_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'attr_name:text:属性名',
            array('attribute'=>'optional', 'label'=>'是否可选', 'value'=>$model->convertYesNo($model['optional'])),
            array('attribute'=>'attr_type','label'=>'属性类型', 'value'=>$model->convertType($model['attr_type'])),
            'def_value:text:默认值',
            'comment:text:说明',
            'ctime:text:创建时间',
            array('attribute'=>'opr_uid', 'label'=>'修改者', 'value'=>$model->getUserName($model['opr_uid'])),
        ],
    ]) ?>

</div>
