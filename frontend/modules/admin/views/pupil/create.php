<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\modules\admin\models\Pupil $model */

$this->title = Yii::t('app', 'Create Pupil');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pupils'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pupil-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
