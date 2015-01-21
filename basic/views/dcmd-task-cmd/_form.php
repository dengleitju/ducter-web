<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-cmd-form">
<script src="/ducter/assets/7d43d3e8/jquery.js"></script>
<style type="text/css">
.search{left: 0;position: relative;}
#auto_div{display: none;width: 300px;border: 1px #74c0f9 solid;background: #FFF;position: absolute;top: 33px;left: 0;color: #323232;}
</style>
<script type="text/javascript">

///获取脚本提示内容
var getTaskScriptList = function ($prefix, auto, search) {
   var task_list = [];
  $.post("?r=dcmd-task-cmd/get-task-list", { "prefix":$prefix, async: false },  function (data, status) {
    status == "success" ? function () {
      task_list =  data.split(";");
      complete(auto, search, task_list);
    } () : "";
  }, "text");
};

//测试用的数据
var test_list = [];
var old_value = "";
var highlightindex = -1; //高亮

//自动完成
function AutoComplete(auto, search, mylist) {
  getTaskScriptList($("#" + search).val(), auto, search);
}
function complete(auto, search, mylist) {
  if ($("#" + search).val() != old_value || old_value == "") {
  var autoNode = $("#" + auto); //缓存对象（弹出框）
  var carlist = new Array();
  var n = 0;
  old_value = $("#" + search).val();

  for (i in mylist) {
    if (mylist[i].indexOf(old_value) >= 0) {
      carlist[n++] = mylist[i];
    }
  }
  if (carlist.length == 0) {
    autoNode.hide();
    return;
  }
  autoNode.empty(); //清空上次的记录
  for (i in carlist) {
    var wordNode = carlist[i]; //弹出框里的每一条内容

    var newDivNode = $("<div>").attr("id", i); //设置每个节点的id值
    newDivNode.attr("style", "font:14px/25px arial;height:25px;padding:0 8px;cursor: pointer;");

    newDivNode.html(wordNode).appendTo(autoNode); //追加到弹出框

    //鼠标移入高亮，移开不高亮
    newDivNode.mouseover(function () {
      if (highlightindex != -1) { //原来高亮的节点要取消高亮（是-1就不需要了）
        autoNode.children("div").eq(highlightindex).css("background-color", "white");
      }
      //记录新的高亮节点索引
      highlightindex = $(this).attr("id");
      $(this).css("background-color", "#ebebeb");
    });
    newDivNode.mouseout(function () {
      $(this).css("background-color", "white");
    });

    //鼠标点击文字上屏
    newDivNode.click(function () {
      //取出高亮节点的文本内容
      var comText = autoNode.hide().children("div").eq(highlightindex).text();
      highlightindex = -1;
      //文本框中的内容变成高亮节点的内容
      $("#" + search).val(comText);
    })
    if (carlist.length > 0) { //如果返回值有内容就显示出来
      autoNode.show();
    } else { //服务器端无内容返回 那么隐藏弹出框
      autoNode.hide();
      //弹出框隐藏的同时，高亮节点索引值也变成-1
      highlightindex = -1;
    }
  }
  }

//点击页面隐藏自动补全提示框
document.onclick = function (e) {
  var e = e ? e : window.event;
  var tar = e.srcElement || e.target;
  if (tar.id != search) {
    if ($("#" + auto).is(":visible")) {
      $("#" + auto).css("display", "none")
    }
  }
} 

}

$(function () {
  old_value = $("#dcmdtaskcmd-task_cmd").val();
  $("#dcmdtaskcmd-task_cmd").focus(function () {
    if ($("#dcmdtaskcmd-task_cmd").val() == "") {
      AutoComplete("auto_div", "dcmdtaskcmd-task_cmd", test_list);
    }
  });

  $("#dcmdtaskcmd-task_cmd").keyup(function () {
    AutoComplete("auto_div", "dcmdtaskcmd-task_cmd", test_list);
  });
});
</script>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ui_name')->textInput(['maxlength' => 64])->label('任务名称') ?>

   <!-- <?= $form->field($model, 'task_cmd')->textInput(['maxlength' => 64, 'onblur' => "javascript:getTaskScriptContent()"])->label('脚本名称') ?> -->
    <div class="form-group field-dcmdtaskcmd-task_cmd required">
    <label class="control-label" for="dcmdtaskcmd-task_cmd">脚本名称</label>
    <div class="search">
      <input type="text" id="dcmdtaskcmd-task_cmd" class="form-control" name="DcmdTaskCmd[task_cmd]" maxlength="64" onblur="javascript:getTaskScriptContent()">
      <div id="auto_div"></div>
    </div>
    <div class="help-block"></div>
    </div> 

    <p>
    <button type="button"  onclick="javascript:getTaskScriptContent()" class="btn btn-success">加载</button>  
    </p> 
    <?= $form->field($model, 'script_md5')->textInput(['maxlength' => 32, 'readonly'=>true])->label('脚本MD5') ?>

    <?= $form->field($model, 'timeout')->textInput()->label('超时时间') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div style="height: auto; width: 800px; background-color: #000; color: #FFF; padding: 10px 3px 10px 10px">
 任务脚本内容:
 <div id="ShellContent" style="margin: 10px 0px 10px 10px; overflow-y: auto; height: auto; overflow-x: hidden">
  <div style=""></div>
 </div>
</div>
<script>
var getTaskScriptContent = function () {
	 task_cmd=$('#dcmdtaskcmd-task_cmd').val();
	 $.post("?r=dcmd-task-cmd/load-content", { "task_cmd":task_cmd }, function (data, status) {
				status == "success" ? function () {
					var dataO = jQuery.parseJSON(data); 
					$('#ShellContent').html(dataO.result);
					$('#dcmdtaskcmd-script_md5').val(dataO.md5);		
				} () : "";
			}, "text");
};
</script>
