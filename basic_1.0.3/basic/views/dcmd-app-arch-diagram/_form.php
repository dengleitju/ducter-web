<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdAppArchDiagram */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-app-arch-diagram-form">

 <form enctype="multipart/form-data"  id="w0" action="/ducter/index.php?r=dcmd-app-arch-diagram/create&app_id=<?php echo $app_id;?>" method="post">
<input type="hidden" name="_csrf" value="dGJsa2pnYkEnLyonHCFXEhMsXQYNASZxAVIWAVxXVyA5CQk7ExMUKQ==">
    <div class="form-group field-dcmdapparchdiagram-arch_name required">
<label class="control-label" for="dcmdapparchdiagram-arch_name">架构图名</label>
<input type="text" id="dcmdapparchdiagram-arch_name" class="form-control" name="DcmdAppArchDiagram[arch_name]" maxlength="200" width="30">

<div class="help-block"></div>
</div>

    <div class="form-group field-dcmdapparchdiagram-arch_name required">
    <label class="control-label" for="dcmdapparchdiagram-arch_name">架构图</label>
    <input id="dcmdapparchdiagram-arch_name" name="DcmdAppArchDiagram[arch_name]" type='file'>
    <div class="help-block"></div>
    </div>


<div class="form-group field-dcmdapparchdiagram-comment">
<label class="control-label" for="dcmdapparchdiagram-comment">说明</label>
<textarea id="dcmdapparchdiagram-comment" class="form-control" name="DcmdAppArchDiagram[comment]" rows="6"></textarea>

<div class="help-block"></div>
</div> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

</form>
</div>
