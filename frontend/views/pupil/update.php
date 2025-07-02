<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Pupil $model */

$this->title = Yii::t('app', 'Обновить');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pupils'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="row p-3">
    <div class="col-md-6">
        <div class="card p-4">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
