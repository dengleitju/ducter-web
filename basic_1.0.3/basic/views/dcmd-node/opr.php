<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdOprCmdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择操作';
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
    alert("请选择操作!");
    return false;
  }
  $("#w0").attr("action", "/ducter/index.php?r=dcmd-opr-cmd/run&opr_cmd_id="+opr+"&ips="+ip);
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
            array('attribute'=>'ui_name', 'label'=>'操作名称', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $colum){return Html::a($model['ui_name'], Url::to(['dcmd-opr-cmd/view', 'id'=>$model['opr_cmd_id']]));}),
            array('attribute'=>'opr_cmd', 'label'=>'指令名称', 'enableSorting'=>false),
            array('attribute'=>'run_user', 'label'=>'运行用户', 'enableSorting'=>false, 'filter'=>false),
        ],
    ]); ?>

</div>
    <p>
        <?= Html::button('选择操作', ['class' =>'btn btn-success', 'onClick'=>"checkOpr()", (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none
"]) ?> 
    </p>
</form>
