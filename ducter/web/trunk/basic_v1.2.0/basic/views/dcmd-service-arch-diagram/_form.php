<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model service\models\DcmdServiceArchDiagram */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-arch-diagram-form">

 <form enctype="multipart/form-data"  id="w0" action="/ducter/index.php?r=dcmd-service-arch-diagram/create&svr_id=<?php echo $service->svr_id;?>" method="post">
<input type="hidden" name="_csrf" value="dGJsa2pnYkEnLyonHCFXEhMsXQYNASZxAVIWAVxXVyA5CQk7ExMUKQ==">
    <div class="form-group field-dcmdservicearchdiagram-arch_name required">
<label class="control-label" for="dcmdservicearchdiagram-arch_name">架构图名</label>
<input type="text" id="dcmdservicearchdiagram-arch_name" class="form-control" name="DcmdServiceArchDiagram[arch_name]" maxlength="200" width="30">

<div class="help-block"></div>
</div>

    <div class="form-group field-dcmdservicearchdiagram-arch_name required">
    <label class="control-label" for="dcmdservicearchdiagram-arch_name">架构图</label>
    <input id="dcmdservicearchdiagram-arch_name" name="DcmdServiceArchDiagram[arch_name]" type='file'>
    <div class="help-block"></div>
    </div>


<div class="form-group field-dcmdservicearchdiagram-comment">
<label class="control-label" for="dcmdservicearchdiagram-comment">说明</label>
<textarea id="dcmdservicearchdiagram-comment" class="form-control" name="DcmdServiceArchDiagram[comment]" rows="6"></textarea>

<div class="help-block"></div>
</div> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

</form>
</div>
