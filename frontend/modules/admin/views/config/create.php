<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\modules\admin\models\Config $model */

$this->title = Yii::t('app', 'Create Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
