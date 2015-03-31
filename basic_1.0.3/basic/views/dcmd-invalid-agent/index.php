<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '未注册Agent列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-app-index">
<table class="table table-striped table-bordered">
<tbody><tr><td>连接IP</td><td>报告IP</td><td>版本</td><td>操作</td></tr>
<?php
 foreach($ips as $k=>$v) {
  echo "<tr data-key='6'><td>".$v['conn_ip']."</td><td>".$v['report_ip']."</td><td>".$v['version']."</td><td><a class='btn btn-primary' href='/ducter/index.php?r=dcmd-node/create-ip&ip=".$v["conn_ip"]."' >添加</a></td></tr>";
 } 
?>
</tbody></table>
</div>
