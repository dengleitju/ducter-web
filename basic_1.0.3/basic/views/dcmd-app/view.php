<link href="/ducter/css/tabs.css" rel="stylesheet">
<script src="/ducter/d3/d3.min.js"></script>
<style>

.node {
  cursor: pointer;
}

.node circle {
  fill: #fff;
  stroke: steelblue;
  stroke-width: 1.5px;
}

.node text {
  font: 15px sans-serif;
}

.link {
  fill: none;
  stroke: #ccc;
  stroke-width: 1.5px;
}

</style>
<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */

$this->title = $model->app_name;
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
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
    d('dcmd-app-info').style.display = 'none';    
    d('dcmd-app-img').style.display='none'; 
    d(parm).style.display = '';    
    
    for(var i in d('ulMenu').getElementsByTagName('LI')){        
     d('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';    
    }
  }
//-->
</SCRIPT>
<ul class="codeDemoUL" id="ulMenu">
  <li class="codeDemomouseOnMenu" id="dcmd-app-info-l" onclick="showDiv('dcmd-app-info');this.className='codeDemomouseOnMenu'">产品信息</li>
  <li class="codeDemomouseOutMenu" id="dcmd-app-img-l" onclick="showDiv('dcmd-app-img');this.className='codeDemomouseOnMenu'">产品服务图</li>
</ul>

<div class="dcmd-app-view" id="dcmd-app-info">
<div class="dcmd-app-view" id="dcmd-app" style="background:#f1f1f1;padding:10px;margin-top:10px">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'app_name', 'label'=>'产品名称'),
            array('attribute'=>'app_alias', 'label'=>'产品别名'),
            array('attribute'=>'sa_gid','label'=>'系统组', 'value'=>$model->userGroupName($model['sa_gid'])),
            array('attribute'=>'svr_gid', 'label'=>'业务组', 'value'=>$model->userGroupName($model['svr_gid'])),
            array('attribute'=>'depart_id', 'label'=>'部门', 'value'=>$model->department($model['depart_id'])),
            array('attribute'=>'comment', 'label'=>'说明', 'value'=>$model->comment($model['comment']), 'format'=>'html'),
        ],
    ]) ?>
    <p>
    <?= Html::a('更新', ['update', 'id' => $model->app_id], ['class' => 'btn btn-primary', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
<div class="dcmd-app-diagram-view" id="dcmd-app-diagram" style="background:#f1f1f1; padding:10px;margin-top:10px">
    <?= GridView::widget([
        'dataProvider' => $imageProvider,
        'filterModel' => NULL,
        'layout' => "{items}",
        'columns' => [
            array('attribute'=>'arch_name', 'label'=>'架构图名称', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $col) { return Html::a($model['arch_name'], '/ducter/app_image/app_'.$model['arch_name'].'_'.$model['app_id'].'.jpg', ['target'=>'blank']);}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {return Url::to(['dcmd-app-arch-diagram/delete', 'id'=>$model['id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false ],
        ],
    ]); ?>
     <p>
       <?= Html::a('添加', ['dcmd-app-arch-diagram/create', 'app_id' => $model->app_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
<div class="dcmd-service-view" id="dcmd-service" >
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'svr_name', 'label'=>'服务名称', 'enableSorting'=>false,'content'=>function($model, $key, $index, $column) { return Html::a($model['svr_name'], Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));}),
            array('attribute'=>'svr_alias', 'label'=>'服务别名', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return Html::a($model['svr_alias'], Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service/delete', 'id'=>$model['svr_id'], 'app_id'=>$model['app_id']]);}, "visible"=>(Yii::$app->user->getIdentity()->admin == 1) ? true : false ],
        ],
    ]); ?>
    <p>
       <?= Html::a('添加', ['dcmd-service/create', 'app_id' => $model->app_id], ['class' => 'btn btn-success', (Yii::$app->user->getIdentity()->admin == 1) ? "" : "style"=>"display:none"]) ?>
    </p>
</div>
</div>
<div style="display:none; background:#f1f1f1;" id="dcmd-app-img"></div>
<script src="/ducter/d3/draw.js"></script>
<script>
var margin = {top: 5, right: 100, bottom: 10, left: 100},
    width = 960 - margin.right - margin.left,
    height = 800 - margin.top - margin.bottom;

var i = 0,
    duration = 750,
    root;

var tree = d3.layout.tree()
    .size([height, width]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var svg = d3.select("#dcmd-app-img").append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

d3.json("index.php?r=dcmd-app/get-app-svr&id=<?php echo $model->app_id ?>", function(error, flare) {
  root = flare;
  root.x0 = height / 2;
  root.y0 = 0;

  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }

  root.children.forEach(collapse);
  update(root);
});

d3.select(self.frameElement).style("height", "800px");

</script>
