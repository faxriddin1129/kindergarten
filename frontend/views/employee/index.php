<?php

use frontend\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var frontend\models\search\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row p-3">
    <div class="col-md-12">
        <div class="card p-4">
            <p>
                <?= Html::a('Создать сотрудника', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'username',
                    'phone',
                    'first_name',
                    'last_name',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function($model){
                            return \common\models\User::dropDownSatus(true)[$model->status];
                        }
                    ],
                    [
                        'attribute' => 'role',
                        'format' => 'raw',
                        'value' => function($model){
                            return \common\models\User::dropDownRole(true)[$model->role];
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
                        'urlCreator' => function ($action, User $model, $key, $index, $column) {
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
