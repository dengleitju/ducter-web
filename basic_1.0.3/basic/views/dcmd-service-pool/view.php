<link href="/ducter/css/tabs.css" rel="stylesheet">
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = $model->svr_pool;
$this->params['breadcrumbs'][] = ['label' => '服务池子', 'url' => ['index']];
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
  var d = function(o)  {
    return document.getElementById(o);
  }
 
  function showDiv(parm){
    d('dcmd-service-pool').style.display = 'none';    
    d('dcmd-service-pool-node').style.display='none';    
    d('dcmd-service-pool-attr').style.display='none';
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }
</SCRIPT>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-service-pool-node-l" onclick="showDiv('dcmd-service-pool-node');this.className='codeDemomouseOnMenu'">服务池信息设备</li>
  <li class="codeDemomouseOutMenu" id="dcmd-service-pool-l" onclick="showDiv('dcmd-service-pool');this.className='codeDemomouseOnMenu'">服务池信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-service-pool-attr-l" onclick="showDiv('dcmd-service-pool-attr');this.className='codeDemomouseOnMenu'">服务池属性</li>
</ul>

<div class="dcmd-service-pool-node-view" id="dcmd-service-pool-node">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'ip','label'=>'IP', 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model["ip"], Url::to(['dcmd-node/view-ip', 'ip'=>$model['ip']]));},),
            array('attribute'=>'ip', 'label'=>'主机名', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'ip', 'label'=>'连接状态','enableSorting'=>false, 'filter'=>false,  'content'=>function($model, $key,$index, $column) { return $model->getAgentState($model['ip']);}),
            ['class' => 'yii\grid\ActionColumn','template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service-pool-node/delete','id'=>$model['id'], 'svr_pool_id'=>$model['svr_pool_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false],
        ],
    ]); ?>

    <p>
        <?= Html::a('添加', ['dcmd-service-pool-node/select-node-group', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id'], 'svr_pool_id'=>$model['svr_pool_id']], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 ) ? "" : "style"=>"display:none"]) ?>
        <?= Html::a('操作', ['dcmd-service-pool/opr', 'svr_pool_id'=>$svr_pool_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 ) ? "" : "style"=>"display:none"]) ?>
       <?= Html::a('重复操作', ['dcmd-service-pool/repeat-opr', 'svr_pool_id'=>$svr_pool_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1 ) ? "" : "style"=>"display:none"]) ?> 
 
    </p>
</div>

<div class="dcmd-service-pool-view" id="dcmd-service-pool" style="display:none" >
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           array('attribute'=>'svr_pool', 'label'=>'服务池子'),
           array('attribute'=>'repo', 'label'=>'版本地址'),
           array('attribute'=>'env_ver', 'label'=>'环境版本'),
           array('attribute'=>'comment', 'label'=>'说明'),
        ],
    ]) ?>
    <p>
        <?= Html::a('更新', ['update', 'id' => $model->svr_pool_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>


<div class="dcmd-service-pool-attr" id="dcmd-service-pool-attr" style="display:none" >
<?php echo $attr_str; ?>
</div>

<script>
  <?php 
    if(!empty($show_div)) 
     echo "document.getElementById('". $show_div."-l').click()";
  ?>
</script>


