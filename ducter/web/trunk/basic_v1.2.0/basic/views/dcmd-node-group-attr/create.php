<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroupAttr */

$this->title = 'Create Dcmd Node Group Attr';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Node Group Attrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-group-attr-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
