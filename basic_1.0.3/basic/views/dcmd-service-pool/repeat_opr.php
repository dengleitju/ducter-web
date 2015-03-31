<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdOprCmdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择重复操作';
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
function checkOpr() {
  var ip = document.getElementById("ips").value;
  var opr="";
  var s = document.getElementsByName("oprs[]");
  for(var i=0; i<s.length; i++) {
    if(s[i].checked) {
      if(opr != "") {
        alert("只可选择一个操作!");
        return false;
      }
      opr = s[i].value;
    }
  }
  if(opr == "") {
    alert("请选择重复操作!");
    return false;
  }
  $("#w0").attr("action", "/ducter/index.php?r=dcmd-opr-cmd-repeat-exec/run&id="+opr+"&ips="+ip);
  $("#w0").submit();
  return true;
}
</script>
<form id="w0"  method="post">
<div class="dcmd-opr-cmd-index">
   <input type=hidden name="ips"  id="ips" value="<?php echo $ips; ?>" />

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterRowOptions' => array('style'=>'display:none'),
        'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn', 'multiple'=>false, 'name'=>'oprs'],
            array('attribute'=>'repeat_cmd_name', 'label'=>'重复操作名称', 'enableSorting'=>false,'content'=> function($model, $key, $index, $column) { return Html::a($model['repeat_cmd_name'],Url::to(['view', 'id'=>$model['repeat_cmd_id']]));}),
            array('attribute'=>'opr_cmd', 'label'=>'脚本名称', 'enableSorting'=>false),
            array('attribute'=>'arg_mutable', 'label'=>'参数可修改', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key,
 $index, $col) { if($model['arg_mutable'] == 1) return "是"; return "否";}),
            array('attribute'=>'ip_mutable', 'label'=>'主机可修改','enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { if($model['ip_mutable'] == 1) return "是"; return "否";}),
            array('attribute'=>'run_user', 'label'=>'运行用户', 'enableSorting'=>false, 'filter'=>false,),

        ],
    ]); ?>

</div>
    <p>
        <?= Html::button('选择操作', ['class' =>'btn btn-success', 'onClick'=>"checkOpr()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none
"]) ?> 
    </p>
</form>
