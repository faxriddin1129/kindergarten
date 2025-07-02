<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Room $model */

$this->title = 'Update Room: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Rooms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row p-3">
    <div class="col-md-12">
        <div class="card p-4">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
