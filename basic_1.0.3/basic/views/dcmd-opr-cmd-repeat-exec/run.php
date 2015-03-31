<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmd */

$this->title = $opr->repeat_cmd_name;
$this->params['breadcrumbs'][] = ['label' => '重复操作列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $opr->repeat_cmd_name, 'url' => ['view', 'id' => $opr->repeat_cmd_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<table class="table table-striped table-bordered detail-view"><tr><th width=20%>重复操作名称</th><td><?php echo $opr->repeat_cmd_name; ?></td></tr>
<tr><th>脚本名称</th><td><?php echo $opr->opr_cmd; ?></td></tr>
<tr><th>运行用户</th><td><?php echo $opr->run_user; ?></td></tr>
<tr><th>IP(多个ip用;分割)</th><td><input name="ips" type="text" id="ips"  <?php if($opr->ip_mutable == 0) echo "disabled=true"; ?> class="form-control" type="text" value="<?php echo $opr->ip; ?>" /></td></tr>
<tr><th>超时时间(s)</th><td><?php echo $opr->timeout; ?></td></tr>
<tr><th>Cache时间(s)</td><td><?php echo $opr->cache_time ;?></td></tr>
<tr><th>是否记录历史数据</td><td><?php if($opr->repeat == 1) echo "不记录历史"; if($opr->repeat==2) echo "记录历史"; ?></td></tr>
</table>
<?php echo $arg; ?>
<div align="center"><input type="submmit" class="btn btn-success" value="执行" onclick="shellRun()"/></div>
<br>
<div id="ShellRunResultDiv" style="height:auto; width:90%; background-color:#000; color:#FFF; padding:10px 3px 10px 10px">
执行结果：
<div id="ShellRunResult" style="margin:10px 0px 10px 10px;overflow-y:auto; height:auto; overflow-x:hidden ">
<div style="300px"></div>

</div>
</div>
<script>
var shellRun = function () {
         if(confirm("确定") == false) return;
         repeat_cmd_name ="<?php echo $opr->repeat_cmd_name; ?>";
         ips = $('#ips').val();
         if(ips.length < 1) {
           alert("IP不可为空!");
           return false;
         }
         args="";
         $("input:text", document.forms[0]).each(function(){
           if(this.name.indexOf('Arg') >= 0) {
             args = args + ";" + this.name.substr(3) + "=" + this.value;
           }
         });
         $.post("?r=dcmd-opr-cmd-repeat-exec/shell-run", { "repeat_cmd_name":repeat_cmd_name , "ips":ips, 'args':args}, function (data, status) {
                                status == "success" ? function () {
                                        var dataO = jQuery.parseJSON(data); 
                                        $('#ShellRunResult').html(dataO.result);
                                } () : "";
                        }, "text");
};
</script>

