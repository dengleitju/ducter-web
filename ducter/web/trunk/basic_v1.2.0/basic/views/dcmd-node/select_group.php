<?php

use yii\helpers\Html;
?>

<form id="w0" action="/ducter/index.php?r=dcmd-node/change-node-group" method="post">
<input type="text" id="ids" name="ids" style="display:none" value="<?php echo $ids; ?>">
<table class="table table-striped table-bordered">
<tbody><tr><td><strong>选择设备池:</strong></td>
<td><select class="form-control" name="ngroup_id">
<option value=""></option>
<?php
  foreach($node_group as $k=>$v)
   echo "<option value='".$k."'>".$v."</option>";
?>
</select></td><td>
<?= Html::submitButton('变更', ['class' =>'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
</td></tr></tbody></table>

<table class="table table-striped table-bordered">
<tbody><tr><td>IP</td></tr>
<?php
 foreach($ips_info as $k=>$v) {
  echo "<tr data-key='6'><td>".$v['ip']."</td></tr>";
 } 
?>
</tbody></table>
</form>

