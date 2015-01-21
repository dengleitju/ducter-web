<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdArg */

$this->title = '添加参数';
$this->params['breadcrumbs'][] = ['label' => $opr->ui_name, 'url' => ['dcmd-opr-cmd/view', 'id'=>$opr->opr_cmd_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-arg-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
