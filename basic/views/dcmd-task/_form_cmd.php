<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-cmd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_cmd')->textInput(['maxlength' => 64, 'readonly'=>true])->label('任务模板名称') ?>

    <?= $form->field($model, 'app_id')->dropDownList($app, ['onchange'=>'javascript:getService()',])->label('产品名称') ?>
    
    <?= $form->field($model, 'svr_id')->dropDownList([], ['onchange'=>'javascript:getServicePool()',])->label('服务名称') ?>

    <?= $form->field($model, 'task_cmd')->textInput(['maxlength' => 32, 'readonly'=>true])->label('任务类型') ?>

    <div class="form-group field-dcmdtask-svr_id required">
    <label class="control-label" for="dcmdtask-task_cmd_prv">任务名称</label>
    <input type="text" id="task_cmd_prv" class="form-control" name="task_cmd_prv" value="<?php echo $task_cmd_prv; ?>" readonly maxlength="32">
    </div>

    <?= $form->field($model, 'task_name')->textInput(['maxlength' => 32, 'value'=>''])->label('任务后缀名') ?>

    <?= $form->field($model, 'timeout')->textInput()->label('超时时间') ?>

    <?= $form->field($model, 'process')->dropDownList(array(0=>"否", 1=>'是'))->label('输出进度') ?>

    <?= $form->field($model, 'update_env')->dropDownList(array(0=>'否', 1=>'是'))->label('更新环境') ?>

    <?= $form->field($model, 'concurrent_rate')->textInput(['maxlength' => 32])->label('并发数') ?>

    <?= $form->field($model, 'tag')->textInput(['maxlength' => 128])->label('上线版本') ?>

    <?= $form->field($model, 'update_tag')->dropDownList(array(0=>"否", 1=>'是'))->label('更新版本') ?>
 
    <?= $form->field($model, 'auto')->dropDownList(array(0=>"否", 1=>'是'))->label('自动执行') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <?php echo $args; ?>

    <div id="w1" class="grid-view"><table class="table table-striped table-bordered"><thead>
    <tr><th><input type="checkbox" class="select-on-check-all" name="selection_all" value="1"></th><th>服务池子</th><th>池子配置版本</th></tr>
    </thead>
    <tbody id="servicePool">
    </tbody></table>
    </div>


    <div class="form-group" align="center">
        <?= Html::submitButton('下一步' , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
var getService = function () {
 var service = document.getElementById("dcmdtask-svr_id");
 service.options.length = 0;
 var app = document.getElementById("dcmdtask-app_id").value;
 if(app == "") return 0;
 $.post("?r=dcmd-task-template/get-services", {"app_id":app}, function(data, status) {
 if (data != "") {
  var dataO = data.split(";");
  service.options.add(new Option("",""));
  for(i=0;i<dataO.length;i++) {
   if (dataO[i] == "")
    continue;
    var d = dataO[i].split(",")
   service.options.add(new Option(d[1], d[0]));
 }
}
}, "text"); 
}

var getServicePool = function() {
  $('#servicePool').html("");
  var svr_id = document.getElementById('dcmdtask-svr_id').value;
  if(svr_id == "") return 0;
  $('#servicePool').load("?r=dcmd-task/get-service-pool&svr_id="+svr_id); 
  return 0;
}


var taskTypeSelect = function(){
  tasktype=$('#dcmdtasktemplate-task_cmd_id').val();
  $('#taskTypeArgDiv').load("?r=dcmd-task-template/get-task-type-arg&task_cmd_id="+tasktype);
}

function checkAll() 
{ 
  var code_Values = document.getElementsByTagName("input"); 
  for(i = 0;i < code_Values.length;i++){ 
    if(code_Values[i].type == "checkbox") 
    { 
     code_Values[i].checked = true; 
    } 
  }   
}
checkAll(); 
</script>
