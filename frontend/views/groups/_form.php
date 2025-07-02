<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Groups $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="groups-form">

    <?php $form = ActiveForm::begin(); ?>

   <div class="row">
       <div class="col-md-6">
           <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

           <?= $form->field($model, 'price')->textInput() ?>

           <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

       </div>
       <div class="col-md-6">
           <?= $form->field($model, 'teacher_id')->dropDownList(\yii\helpers\ArrayHelper::map(\frontend\models\User::find()->andWhere(['status' => \common\models\User::STATUS_ACTIVE])->andWhere(['role' => \common\models\User::ROLE_TEACHER])->asArray()->all(),'id','first_name'),['prompt' => 'Выбирать']) ?>

           <?= $form->field($model, 'educator_id')->dropDownList(\yii\helpers\ArrayHelper::map(\frontend\models\User::find()->andWhere(['status' => \common\models\User::STATUS_ACTIVE])->andWhere(['role' => \common\models\User::ROLE_TEACHER])->asArray()->all(),'id','first_name'),['prompt' => 'Выбирать']) ?>

           <?= $form->field($model, 'room_id')->dropDownList(\yii\helpers\ArrayHelper::map(\frontend\models\Room::find()->asArray()->all(),'id','title'),['prompt' => 'Выбирать']) ?>

           <div class="row">
               <div class="col-md-6">
                   <?= $form->field($model, 'start')->input('datetime-local') ?>
               </div>
               <div class="col-md-6">
                   <?= $form->field($model, 'end')->input('datetime-local') ?>
               </div>
           </div>

           <div class="form-group">
               <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-success p-2 mt-3']) ?>
           </div>
       </div>
   </div>

    <?php ActiveForm::end(); ?>

</div>
