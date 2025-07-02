<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app','login');
$config = \frontend\models\Config::findOne(1);

?>

<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo">
            <img src="/images/simply.svg" style="width: 100%">
        </div>
        <h4 style="margin-top: 2px;">Войдите, чтобы продолжить.</h4>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
            <div class="form-group">
                <?= $form->field($model, 'username')->textInput(['placeholder' => Yii::t('app','Имя пользователя').'...'])->label(Yii::t('app','Имя пользователя')) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app','Имя пользователя').'...'])->label(Yii::t('app','Имя пользователя')) ?>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn"><?=Yii::t('app','Авторизоваться')?></button>
            </div>
            <div class="my-2 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Запомнить </label>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>