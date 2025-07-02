<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\ExpensesCategory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<div class="">
    <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-sm btn-success p-2']) ?>
</div>

<?php ActiveForm::end(); ?>

