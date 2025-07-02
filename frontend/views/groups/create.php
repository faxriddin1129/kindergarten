<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Groups $model */

$this->title = 'Создать группу';
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
