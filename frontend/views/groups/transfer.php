<?php

use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var frontend\models\Groups $model */
/** @var frontend\models\form\TransferForm $form */

$this->title = 'Трансфер: ' . $model->title;
$dataPupil = ArrayHelper::map(\frontend\models\GroupPupil::find()->andWhere(['group_id' => $model->id])->all(),'pupil_id',function($model){ return $model->pupil->first_name.' '.$model->pupil->last_name;});
$dataGroup = ArrayHelper::map(\frontend\models\Groups::find()->andWhere(['status' => \frontend\models\Groups::STATUS_ACTIVE])->andWhere(['<>', 'id', $model->id])->all(),'id',function($model){ return $model->title;});
?>
<div class="row p-3">
    <div class="col-md-6">
        <div class="card p-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="btn-group mb-3">
                        <a class="btn btn-sm btn-info" href="/groups/view?id=<?=$model->id?>"><i class="mdi mdi-arrow-left"></i> Группа</a>
                    </div>
                </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                    <div>
                        <?php $activeForm = \yii\widgets\ActiveForm::begin() ?>
                        <?=$activeForm->field($form,'pupil_id')->dropDownList($dataPupil,['prompt' => 'Выбирать'])->label('Ребенка')?>
                        <?=$activeForm->field($form,'group_id')->dropDownList($dataGroup,['prompt' => 'Выбирать'])->label('Группа')?>
                        <button class="btn btn-sm btn-primary" data-confirm="Вы уверены!" type="submit"><i class="mdi mdi-arrow-collapse-up"></i> Трансфер</button>
                        <?php \yii\widgets\ActiveForm::end() ?>
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>
