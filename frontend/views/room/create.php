<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Room $model */

$this->title = 'Создать комнату';
$this->params['breadcrumbs'][] = ['label' => 'Rooms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row p-3">
    <div class="col-md-12">
        <div class="card p-4">
            <h4><?= Html::encode($this->title) ?></h4>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
