<?php

use frontend\models\Pupil;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var frontend\models\search\PupilSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Клиентская база');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row p-3">
    <div class="col-md-12">
        <div class="card p-4">
            <h4><?= Html::encode($this->title) ?></h4>

            <p>
                <?= Html::a(Yii::t('app', 'Создать ученика'), ['create'], ['class' => 'btn btn-sm btn-success']) ?>

                <?php if (Yii::$app->user->identity['role'] != 3): ?>
                <?= Html::a(Yii::t('app', 'Импортный центр'), ['/import'], ['class' => 'btn btn-sm btn-success']) ?>
                <?php endif; ?>
            </p>

            <?php Pjax::begin(); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'first_name',
                    'last_name',
                    [
                        'attribute' => 'user_id',
                        'format' => 'raw',
                        'label' => 'Телефон',
                        'value' => function($model){
                            return $model->user->phone;
                        }
                    ],
                    [
                        'attribute' => 'created_by',
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->createdBy->first_name.' '.$model->createdBy->last_name;
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'raw',
                        'value' => function($model){
                            return date('Y-M-d H:i', $model->created_at);
                        }
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, Pupil $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        },
                        'template' => '{update}'
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
