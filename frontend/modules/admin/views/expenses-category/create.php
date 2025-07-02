<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\modules\admin\models\ExpensesCategory $model */

$this->title = Yii::t('app', 'Create Expenses Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenses Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenses-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
