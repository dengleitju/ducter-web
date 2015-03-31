<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdRepeatExec */

$this->title = '添加重复操作';
$this->params['breadcrumbs'][] = ['label' => '操作列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-repeat-exec-create">


    <?= $this->render('_form', [
        'model' => $model,
        'opr_cmd' => $opr_cmd,
    ]) ?>

</div>
