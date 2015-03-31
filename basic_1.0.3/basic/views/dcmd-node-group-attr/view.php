<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroupAttr */

$this->title = $model->attr_name;
$this->params['breadcrumbs'][] = ['label' => $group->ngroup_name, 'url' => ['dcmd-node-group/view', 'id' => $group->ngroup_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-group-attr-view">

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
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'attr_name:text:属性名',
            'attr_value:text:属性值',
            'comment:text:说明',
            'utime:text:修改时间',
            'ctime:text:创建时间',
        ],
    ]) ?>

</div>
