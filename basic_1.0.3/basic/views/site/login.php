<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Login';
///$this->params['breadcrumbs'][] = $this->title;
?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-param" content="_csrf">
    <title><?= Html::encode($this->title) ?></title>
    <link href="/ducter/css/log.css" rel="stylesheet"></head>
</head>

<body>
<DIV id=logo><IMG alt=HongCMS src="/ducter/css/logo-login.png"></DIV>
<DIV id=login>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'wrapper',],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
<P id=info>请输入用户名和密码</P>

    <div class="control-group"><SPAN class=icon-user></SPAN>
    <?= $form->field($model, 'username')->label('') ?>
    </div>

    <div class="control-group"><SPAN class=icon-lock></SPAN>
    <?= $form->field($model, 'password')->passwordInput()->label('') ?>
    </div>

   <?= $form->field($model, 'rememberMe', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])->checkbox() ?>

<DIV class=login-btn><INPUT id=login-btn value="登 录" type=submit name=submit></DIV>
    <?php ActiveForm::end(); ?>
<DIV id=login-copyright>2015 Ducter <A href="/" target=_blank>www.ducter.net</A> </DIV>
</body>
</html>
