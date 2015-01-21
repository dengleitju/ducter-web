<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroup */

$this->title = $model->ngroup_name; 
$this->params['breadcrumbs'][] = ['label' => '设备池子', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
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
<SCRIPT LANGUAGE="JavaScript">
<!--
  var d = function(o)  {
    return document.getElementById(o);
  }
 
  function showDiv(parm){
    d('dcmd-node-group').style.display = 'none';    
    d('dcmd-node').style.display='none';    
    d('dcmd-node-group-attr').style.display='none';
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }

//-->
</SCRIPT>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-node-l" onclick="showDiv('dcmd-node');this.className='codeDemomouseOnMenu'">设备池设备</li>
  <li class="codeDemomouseOutMenu" id="dcmd-node-group-l" onclick="showDiv('dcmd-node-group');this.className='codeDemomouseOnMenu'">设备池信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-node-group-attr-l" onclick="showDiv('dcmd-node-group-attr');this.className='codeDemomouseOnMenu'">设备池属性</li>
</ul>


<div class="dcmd-node-view" id="dcmd-node"  >
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            array('attribute'=>'ip', 'label' => '服务器IP', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return Html::a($model['ip'], Url::to(['dcmd-node/view', 'id'=>$model['nid']]));}),
            array('attribute'=>'host', 'label'=>'服务器名', 'filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'rack', 'label'=>'连接状态','filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'did', 'label'=>'设备序列号','filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'sid', 'label'=>'资产序列号', 'filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'online_time', 'label'=>'上线时间', 'filter'=>false, 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn','template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" ==$action) { return Url::to(['dcmd-node/delete', 'id'=>$model['nid'], 'ngroup_id'=>$model['ngroup_id']]);}},  "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加', Url::to(['dcmd-node/create', 'ngroup_id'=>$ngroup_id]), ['class' => 'btn btn-success', "target"=>"_blank", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>

<div class="dcmd-node-group-view" id="dcmd-node-group"  style="display:none" >
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute' => 'ngroup_name', 'label' => '设备池子'),
            array('attribute' => 'ctime', 'label' => '创建时间'),
            array('attribute' => 'utime', 'label' => '修改时间'),
            array('attribute' => 'gid', 'value'=>$model->getGname($model['gid']), 'label' =>
 '系统组' ),
            array('attribute' => 'comment', 'label' => '说明'),
        ],
    ]) ?>
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->ngroup_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->
admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>

</div>

<div class="dcmd-node-group-attr" id="dcmd-node-group-attr" style="display:none" >
<?php echo $attr_str; ?>
</div>

<script>
  document.getElementById("<?php echo $show_div.'-l'; ?>").click();
</script>
