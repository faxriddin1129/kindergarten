<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\ExpensesCategory $model */

$this->title = Yii::t('app', 'Создать категорию расходов');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenses Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
