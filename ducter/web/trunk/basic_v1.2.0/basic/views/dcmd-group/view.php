<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroup */

$this->title = $model->gname;
$this->params['breadcrumbs'][] = ['label' => '用户组', 'url' => ['index']];
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
    d('dcmd-group').style.display = 'none';    
    d('dcmd-group-user').style.display='none';    
    d('dcmd-group-cmd').style.display='none';
    d('dcmd-group-repeat-cmd').style.display='none';
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }

//-->
</SCRIPT>

<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-group-l" onclick="showDiv('dcmd-group');this.className='codeDemomouseOnMenu'">组信息</li>           
  <li class="codeDemomouseOutMenu" id="dcmd-group-user-l" onclick="showDiv('dcmd-group-user');this.className='codeDemomouseOnMenu'">组成员</li>
  <li class="codeDemomouseOutMenu" id="dcmd-group-cmd-l" style="display:<?php echo $is_sys; ?>" onclick="showDiv('dcmd-group-cmd');this.className='codeDemomouseOnMenu'">组操作</li>
  <li class="codeDemomouseOutMenu" id="dcmd-group-repeat-cmd-l" style="display: <?php echo $is_sys; ?>" onclick="showDiv('dcmd-group-repeat-cmd');this.className='codeDemomouseOnMenu'">组重复操作</li>
</ul>  

<div class="dcmd-group-view"  id="dcmd-group" >

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'gname', 'label'=>'用户组'),
            array('attribute'=>'gtype', 'label'=>'组类型', 'value'=>$model->convertGtype($model['gtype'])),
            array('attribute'=>'comment', 'label'=>'说明'),
        ],
    ]) ?>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->gid], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1 && Yii::$app->user->getIdentity()->sa == 1) ? "" : "style"=>"display:none"]) ?>
    </p>

</div>

<div class="dcmd-group-user-view" style="display:none" id="dcmd-group-user" >
    <?= GridView::widget([
        'dataProvider' => $user_dataProvider,
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'uid', 'label'=>'用户名', 'filter'=>false, 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model->getUsername($model['uid']), Url::to(['dcmd-user/view', 'id'=>$model['uid']]));}),
            array('attribute'=>'uid', 'label' => '部门', 'filter'=>false, 'enableSorting'=>false, 'value' => function($model, $key, $index, $column) { return $model->getDepartment($model['uid']);},),
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}', 'urlCreator'=>function($action, $model, $key, $index){return Url::to(['dcmd-user-group/remove','gid'=>$model['gid'], 'id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 && Yii::$app->user->getIdentity()->sa == 1) ? true : false],
        ],
      ]); ?>
<?= Html::a('添加', ['dcmd-user-group/create', 'gid'=>$gid, 'gname'=>$model->gname], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
</div>

<div class="dcmd-group-cmd" style="display:none" id="dcmd-group-cmd" >
    <?= GridView::widget([
        'dataProvider' => $cmd_dataProvider,
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'opr_cmd_id', 'label'=>'操作', 'filter'=>false, 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model->getOprcmd($model['opr_cmd_id']), Url::to(['dcmd-opr-cmd/view', 'id'=>$model['opr_cmd_id']]));}),
            array('attribute'=>'opr_cmd_id', 'label' => '操作脚本', 'filter'=>false, 'enableSorting'=>false, 'value' => function($model, $key, $index, $column) { return $model->getUiname($model['opr_cmd_id']);},),
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}', 'urlCreator'=>function($action, $model, $key, $index){return Url::to(['dcmd-group-cmd/remove','gid'=>$model['gid'], 'id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 && Yii::$app->user->getIdentity()->sa == 1) ? true : false],
        ],
      ]); ?>
<?= Html::a('添加', ['dcmd-group-cmd/create', 'gid'=>$gid, 'gname'=>$model->gname], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
</div>

<div class="dcmd-group-repeat-cmd" style="display:none" id="dcmd-group-repeat-cmd" >
    <?= GridView::widget([
        'dataProvider' => $repeat_cmd_dataProvider,
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'repeat_cmd_id', 'label'=>'重复操作', 'filter'=>false, 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model->getRepeatcmd($model['repeat_cmd_id']), Url::to(['dcmd-opr-cmd/view', 'id'=>$model['repeat_cmd_id'], 'target'=>'blank']));}),
            array('attribute'=>'repeat_cmd_id', 'label' => '操作', 'filter'=>false, 'enableSorting'=>false, 'value' => function($model, $key,$index, $column) { return $model->getOprcmd($model['repeat_cmd_id']);},),
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}', 'urlCreator'=>function($action, $model, $key, $index){return Url::to(['dcmd-group-repeat-cmd/remove','gid'=>$model['gid'], 'id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1 && Yii::$app->user->getIdentity()->sa == 1) ? true : false],
        ],
      ]); ?>
<?= Html::a('添加', ['dcmd-group-repeat-cmd/create', 'gid'=>$gid, 'gname'=>$model->gname], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
</div>


<script>
  document.getElementById("<?php echo $show_div.'-l'; ?>").click();
</script>
