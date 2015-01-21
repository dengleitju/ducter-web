<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdRepeatExec */

$this->title = $model->repeat_cmd_name;
$this->params['breadcrumbs'][] = ['label' => '可重复操作', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
  var d = function(o)  {
    return document.getElementById(o);
  }
 
  function showDiv(parm){
    d('dcmd-repeat-opr').style.display = 'none';    
    d('dcmd-repeat-opr-group').style.display='none';    
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }

//-->
</SCRIPT>

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

<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-repeat-opr-l" onclick="showDiv('dcmd-repeat-opr');this.className='codeDemomouseOnMenu'">重复操作信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-repeat-opr-group-l" onclick="showDiv('dcmd-repeat-opr-group');this.className='codeDemomouseOnMenu'">授权用户组</li>
</ul>
<div class="dcmd-opr-cmd-repeat-exec-view" id="dcmd-repeat-opr">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'repeat_cmd_name','label'=>'重复操作名称'),
            array('attribute'=>'opr_cmd', 'label'=>'脚本名称'),
            array('attribute'=>'run_user', 'label'=>'运行用户'),
            array('attribute'=>'timeout', 'label'=>'超时'),
            array('attribute'=>'ip', 'label'=>'操作IP'),
            array('attribute'=>'repeat', 'label'=>'是否记录历史', 'value'=>$model->repeatInfo($model['repeat']),),
            array('attribute'=>'cache_time', 'label'=>'Cache时间s'),
            array('attribute'=>'ip_mutable', 'label'=>'IP是否可替换', 'value'=>$model->yesOrNo($model['ip_mutable'])),
            array('attribute'=>'arg_mutable', 'label'=>'参数是否可替换', 'value'=>$model->yesOrNo($model['arg_mutable'])),
        ],
    ]) ?>

    <div class="form-group field-dcmdoprcmdrepeat-arg">
    <label class="arg" for="dcmdoprcmdrepeat-arg">任务脚本参数</label>
    <div id="taskTypeArgDiv" style="width:100%"></div>
    <?php echo $arg_content; ?>
    </div>

    <p>
     <?= Html::a('修改', ['update', 'id' => $model->repeat_cmd_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?> &nbsp;&nbsp;&nbsp;
     <?= Html::a('执行', ['run', 'id' => $model->repeat_cmd_id], ['class' => 'btn btn-primary']) ?>
    </p>
</div>

<div class="dcmd-group-cmd-index"  id="dcmd-repeat-opr-group" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $group_dataProvider,
        'filterModel' => NULL,
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'gid', 'label'=>'用户组', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { return Html::a($model->getGroupName($model['gid']), Url::to(['dcmd-group/view', 'id'=>$model['gid']]));},),
            array('attribute'=>'gid', 'label'=>'组类型', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { return "业务组";}),

            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}', 'urlCreator'=>function($action, $model, $key, $index){return Url::to(['dcmd-group-repeat-cmd/delete','id'=>$model['id'], 'repeat_cmd_id'=>$model['repeat_cmd_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加组', ['dcmd-group-repeat-cmd/add-group', 'repeat_cmd_id'=>$model['repeat_cmd_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
