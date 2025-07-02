<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\modules\admin\models\GroupPupil $model */

$this->title = Yii::t('app', 'Create Group Pupil');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Group Pupils'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-pupil-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
