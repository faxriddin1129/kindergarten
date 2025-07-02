<?php

use frontend\models\GroupPupil;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var frontend\models\search\GroupPupilSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Group Pupils');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row p-3">
    <div class="col-md-12">
        <div class="card p-4 table-responsive">

            <?php Pjax::begin(); ?>

            <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'group_id',
                'format' => 'raw',
                'value' => function($model){
                    return $model->group->title;
                }
            ],
            [
                'attribute' => 'pupil_id',
                'format' => 'raw',
                'value' => function($model){
                    return $model->pupil->first_name.' '.$model->pupil->last_name;
                }
            ],
            [
                'attribute' => 'date',
                'format' => 'raw',
                'label' => 'Пришел',
                'value' => function($model){
                    return $model->date;
                }
            ], [
                'attribute' => 'leave_date',
                'format' => 'raw',
                'label' => 'Ушел',
                'value' => function($model){
                    return $model->leave_date;
                }
            ],
            [
                'attribute' => 'updated_by',
                'label' => 'Создан в',
                'format' => 'raw',
                'value' => function($model){
                    return $model->updatedBy->first_name.' '.$model->updatedBy->last_name;
                }
            ],
            'comment',
        ],
    ]); ?>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
