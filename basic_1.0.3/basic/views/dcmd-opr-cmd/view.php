<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmd */

$this->title = $model->ui_name;
$this->params['breadcrumbs'][] = ['label' => '操作列表', 'url' => ['index']];
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
    d('dcmd-opr').style.display = 'none';    
    d('dcmd-opr-group').style.display='none';    
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }

//-->
</SCRIPT>

<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-opr-l" onclick="showDiv('dcmd-opr');this.className='codeDemomouseOnMenu'">操作信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-opr-group-l" onclick="showDiv('dcmd-opr-group');this.className='codeDemomouseOnMenu'">授权用户组</li>
</ul>

<div class="dcmd-opr-cmd-view" id="dcmd-opr">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'opr_cmd:text:脚本名称',
            'ui_name:text:操作名称',
            'run_user:text:运行用户',
            'script_md5:text:脚本MD5',
            'timeout:text:超时',
            'comment:text:说明',
            'utime:text:创建时间',
            'ctime:text:修改时间',
        ],
    ]) ?>
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->opr_cmd_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
    <p align="center">
        <?= Html::a('执行', ['run', 'opr_cmd_id'=>$model['opr_cmd_id']], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $arg_dataProvider,
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'arg_name', 'label'=>'参数名', 'filter'=>false, 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return Html::a($model['arg_name'], Url::to(['dcmd-opr-cmd-arg/update', 'id'=>$model['id']]));}),
            array('attribute'=>'optional', 'label'=>'是否可选',  'filter'=>false, 'enableSorting'=>false, 'content'=>function($model, $key, $index, $colum) { if($model['optional'] == 0) return "否"; return  "是";}),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}','urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-opr-cmd-arg/delete', 'id'=>$model['id'], 'opr_cmd_id'=>$model['opr_cmd_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
<?= Html::a('添加', ['dcmd-opr-cmd-arg/create', 'opr_cmd_id'=>$model['opr_cmd_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
<p align="center"><input type="submmit" class="btn btn-success" value="加载" onclick="javascript:getOprScriptContent()"/>
</p>
<div style="height: auto; width: 800px; background-color: #000; color: #FFF; padding: 10px 3px 10px 10px">
 操作脚本内容:
 <div id="ShellContent" style="margin: 10px 0px 10px 10px; overflow-y: auto; height: auto; overflow-x: hidden">
  <div style=""></div>
 </div>
</div>
</div> 

<div class="dcmd-group-cmd-index"  id="dcmd-opr-group" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $group_dataProvider,
        'filterModel' => NULL,
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'gid', 'label'=>'用户组', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { return Html::a($model->getGroupName($model['gid']), Url::to(['dcmd-group/view', 'id'=>$model['gid']]));},),
            array('attribute'=>'gid', 'label'=>'组类型', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { return "业务组";}),

            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}', 'urlCreator'=>function($action, $model, $key, $index){return Url::to
(['dcmd-group-cmd/delete','id'=>$model['id'], 'opr_cmd_id'=>$model['opr_cmd_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>
    <p>
        <?= Html::a('添加组', ['dcmd-group-cmd/add-group', 'opr_cmd_id'=>$model['opr_cmd_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>

</div>


<script>
var getOprScriptContent = function () {
         task_cmd="<?php echo $model['opr_cmd']; ?>";
         $.post("?r=dcmd-opr-cmd/load-content", { "opr_cmd":task_cmd }, function (data, status) {
                                status == "success" ? function () {
                                        var dataO = jQuery.parseJSON(data); 
                                        $('#ShellContent').html(dataO.result);
                                        $('#dcmdoprcmd-script_md5').val(dataO.md5);            
                                } () : "";
                        }, "text");
};
</script>
