<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Expenses $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="expenses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'tearcher_id')->dropDownList(\yii\helpers\ArrayHelper::map(\frontend\models\User::find()->andWhere(['<>', 'role', 5])->andWhere(['status' => 10])->all(), 'id', 'username'), ['prompt' => 'Выберите'])?>

    <?= $form->field($model, 'month')->input('month') ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Saqlash'), ['class' => 'btn btn-sm btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
