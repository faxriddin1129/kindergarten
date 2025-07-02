<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Groups $model */

$this->title = 'Обновить: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row p-3">
    <div class="col-md-12">
        <div class="card p-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="btn-group mb-3">
                        <a class="btn btn-sm btn-info" href="/groups/view?id=<?=$model->id?>"><i class="mdi mdi-arrow-left"></i> Группа</a>
                    </div>
                </div>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
