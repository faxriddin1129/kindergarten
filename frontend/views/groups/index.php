<?php

use frontend\models\Groups;
use frontend\models\Pupil;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var frontend\models\search\GroupsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $data */
/** @var \frontend\controllers\GroupsController $activeCount */
/** @var \frontend\controllers\GroupsController $debtCount */

$this->title = 'Группы';
$this->params['breadcrumbs'][] = $this->title;

$errorPhone = Pupil::find()
    ->select(['public.pupil.id', 'public.user.id', 'public.pupil.first_name', 'public.pupil.last_name', 'public.user.phone'])
    ->leftJoin('public.user', 'public.user.id = public.pupil.user_id')
    ->andWhere(['or',
        ['public.user.phone' => null],
        ['public.user.phone' => '']
    ])
    ->asArray()->count();

$teachers = \common\models\User::find()->andWhere(['in', 'role', [3,1]])->asArray()->all();
$current_teacher = $_REQUEST['teacher']??null;
?>
<div class="row p-3">
    <?php if (Yii::$app->user->identity['role'] != 3): ?>
    <p>
        <?= Html::a('Guruh yaratish', ['create'], ['class' => 'btn btn-sm btn-success']) ?>

        <?php if ($debtCount > 0): ?>
        <a href="/groups/debts" class="btn btn-sm btn-danger">Qarzdorlar ro'yxati <?=$debtCount?> <i class="mdi mdi-human-greeting menu-icon"></i></a>
        <?php endif; ?>

        <?php if ($errorPhone > 0): ?>
        <a href="/pupil/pupil-error" class="btn btn-sm btn-danger   ">Telefon raqam kiritilmagan <?=$errorPhone?> !</a>
        <?php endif; ?>
    </p>
        <form action="/groups" class="row">
            <label class="col-md-3 mb-2">
                <select class="form-control-sm form-control" name="teacher" id="teacher">
                    <option value="">All Teacher</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option <?php if ($current_teacher == $teacher['id']) echo 'selected'; ?> value="<?=$teacher['id']?>"><?=$teacher['first_name']?> <?=$teacher['last_name']?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div class="col-md-1">
                <button class="btn btn-sm btn-primary w-100">Ok</button>
            </div>
        </form>
    <?php endif; ?>

    <?php foreach ($data as $datum): ?>
    <div class="col-md-6 border mb-3">
        <div class="card">
            <div class="card-body p-4">
                <a class="text-decoration-none text-secondary" href="/groups/view?id=<?=$datum['id']?>">
                    <div class="text-center mb-2 d-flex gap-2 justify-content-center">
                        <?php
                        $start = null;
                        $end = null;
                        $now = null;
                        if ($datum['start']){
                            $start = date('H:i', strtotime($datum['start']));
                            $end = date('H:i', strtotime($datum['end']));
                            $now = date('H:i');
                        }
                        ?>
                        <div>
                            <b><?=$datum['title']?> <?=$start?> - <?=$end?> </b>
                        </div>
                        <?php
                            $todayN = date('N');
                            $checkKey = 'week_'.$todayN;
                            $check = $datum["$checkKey"];
                        ?>
                        <?php if ( $start < $now && $end > $now && $check): ?>
                            <div class="d-flex gap-2">
                                <div>Live </div>
                                <div class="spinner-grow text-warning" role="status">
                                    <span class="sr-only"></span>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                    <table class="">
                        <tr>
                            <td class="p-2"><b>Лидер группы: </b></td>
                            <td class="p-2">
                                <span><span><?=$datum->teacher->first_name?> <?=$datum->teacher->last_name?></span></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2"><b>Педагог: </b></td>
                            <td class="p-2"><span><?=$datum->educator->first_name?> <?=$datum->educator->last_name?></span></td>
                        </tr>
                        <tr>
                            <td class="p-2">
                                <b>Количество детей: &nbsp;&nbsp;</b>
                            </td>
                            <td class="p-2">
                                <span><b> ( <?=count($datum->groupPupilsActive)?>  <i class="mdi mdi-human-greeting menu-icon text-info"></i> )</b> </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2"><b>Цена: </b></td>
                            <td class="p-2"><span><?=Yii::$app->formatter->asDecimal($datum->price,0)?> UZS</span></td>
                        </tr>
                        <tr>
                            <th class="p-2">Комната: </th>
                            <td class="p-2">
                                <span><?=$datum->room->title?></span>
                            </td>
                        </tr>
                    </table>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
    select{
        background-color: white!important;
    }
    .spinner-grow {
        margin-top: 5px;
        display: inline-block;
        width: 10px;
        height: 10px;
    }
</style>