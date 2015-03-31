<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $tdis yii\web\View */
/* @var $model app\models\DcmdAppArchDiagram */

$this->title = '导入机器';

$this->params['breadcrumbs'][] = ['label' => '设备池子', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ngroup_name, 'url' => ['view', 'id' => $model->ngroup_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-group-import-node">
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


<div class="dcmd-import-node-form">

 <form id="import_node" name="import_node" enctype="multipart/form-data"   metdod="post">
 <label class="control-label" >数据文件</label>
 <input id="nfile" name="nfile" type='file' />
 <div class="help-block"></div>
 <input id="asdf" name="asdf" value="sfg" style="display:none">
 <div id="w0" class="grid-view">
<div class="summary"><font color=red>文件字段格式(支持txt格式,文本格式字段使用空格分割):</font></div>
<table style="border:solid 2px #add9c0;">
<tr ><td style="border:solid 2px #add9c0;">服务器IP</td><td style="border:solid 2px #add9c0;">  主机名</td><td style="border:solid 2px #add9c0;">  资产序列号</td><td style="border:solid 2px #add9c0;">  设备序列号</td><td style="border:solid 2px #add9c0;">  操作系统类型</td><td style="border:solid 2px #add9c0;">  操作系统版本</td><td style="border:solid 2px #add9c0;">  带外IP</td><td style="border:solid 2px #add9c0;">  公网IP</td><td style="border:solid 2px #add9c0;">  机房</td><td style="border:solid 2px #add9c0;">机架</td><td style="border:solid 2px #add9c0;">机位</td><td style="border:solid 2px #add9c0;">上线时间</td><td style="border:solid 2px #add9c0;">服务器品牌</td><td style="border:solid 2px #add9c0;">服务器型号</td><td style="border:solid 2px #add9c0;">CPU信息</td><td style="border:solid 2px #add9c0;">内存信息</td><td style="border:solid 2px #add9c0;">硬盘信息</td><td style="border:solid 2px #add9c0;">维护厂家</td><td style="border:solid 2px #add9c0;">说明</td></tr>
 </table> </div>
 <div>
   <input type="button" value="提交" onclick="smit()">
 </div>
 </form>
</div>
</div>
<script>
function smit() {
  alert("ok");
  document.import_node.action='index.php?r=dcmd-node-group/import-node';
  document.import_node.submit();
}
</script>
