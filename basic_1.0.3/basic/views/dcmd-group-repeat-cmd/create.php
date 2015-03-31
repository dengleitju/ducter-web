<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdUserGroup */

$this->title = '添加用户组重复操作';
$this->params['breadcrumbs'][] = ['label' => $gname, 'url' => ['dcmd-group/view', 'id'=>$gid]];
$this->params['breadcrumbs'][] = $this->title;
?>


<form id="w0" action="<?php echo Url::to(['add-cmd',]); ?>" method="post">
<div class="dcmd-group-cmd-create">
    <input type="text" name="gid" value="<?php echo $gid; ?>" style="display:none">
    <input type="text" name="gname" value="<?php echo $gname; ?>" style="display:none">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterRowOptions' => array('style'=>'display:none'), 
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'repeat_cmd_name', 'label' => '重复操作名称', 'filter'=>false, 'enableSorting'=>false,),
            array('attribute'=>'opr_cmd', 'label' => '操作名称', 'filter'=>false, 'enableSorting'=>false,),
        ],
    ]); ?>

    <div >
        <?= Html::submitButton('添加',   ['class' => 'btn btn-success' ])?>
    </div>
</div>
</form>
