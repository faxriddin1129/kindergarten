<?php

/* @var $start */
/* @var $end */

use frontend\models\Invoice;
use frontend\models\User;
use yii\helpers\ArrayHelper;

function formatPrice($price)
{
    return Yii::$app->formatter->asDecimal($price,0);
}

$invoices = Invoice::find()
    ->select(['invoice.id', 'invoice.updated_at', 'invoice.amount', 'invoice.status', 'invoice.group_id', 'groups.title', 'groups.teacher_id', 'invoice.period', 'invoice.payment_amount', 'invoice.updated_by', 'invoice.payment_type'])
    ->andWhere(['>=', 'invoice.updated_at', strtotime(($start.' 00:00:00'))])
    ->andWhere(['<=', 'invoice.updated_at', strtotime(($end.' 23:59:59'))])
    ->leftJoin('groups', 'groups.id = invoice.group_id')
    ->asArray()->all();

$groupIds = ArrayHelper::getColumn($invoices, 'group_id');
$users = User::find()->andWhere(['<>', 'role', 5])->asArray()->indexBy('id')->all();

$this->title = 'Report by date';

$key = 'payment_amount';
$totalPayed = 0;
$teacherBy = [];
$groupsBy = [];
$adminBy = [];

foreach ($invoices as $invoice) {

    //TOTAL
    $totalPayed+= $invoice[$key];

    ///GROUP BY
    if (!isset($groupsBy[$invoice['group_id']])){
        $groupsBy[$invoice['group_id']]['amount'] = 0;
        $groupsBy[$invoice['group_id']]['amount_cache'] = 0;
        $groupsBy[$invoice['group_id']]['amount_card'] = 0;
        $groupsBy[$invoice['group_id']]['group'] = $invoice['title'];
    }
    $groupsBy[$invoice['group_id']]['amount'] += $invoice[$key];

    ///TEACHER BY
    if (!isset($teacherBy[$invoice['teacher_id']])){
        $teacherBy[$invoice['teacher_id']]['amount'] = 0;
        $teacherBy[$invoice['teacher_id']]['amount_cache'] = 0;
        $teacherBy[$invoice['teacher_id']]['amount_card'] = 0;
        $teacherBy[$invoice['teacher_id']]['teacher'] = $users[$invoice['teacher_id']]['first_name'].' '.$users[$invoice['teacher_id']]['last_name'];
    }
    $teacherBy[$invoice['teacher_id']]['amount'] += $invoice[$key];

    ///ADMIN BY
    if (!isset($adminBy[$invoice['updated_by']])){
        $adminBy[$invoice['updated_by']]['amount'] = 0;
        $adminBy[$invoice['updated_by']]['amount_cache'] = 0;
        $adminBy[$invoice['updated_by']]['amount_card'] = 0;
        $adminBy[$invoice['updated_by']]['admin'] = ($users[$invoice['updated_by']]['first_name']??'-').' '.($users[$invoice['updated_by']]['last_name']??'');
    }
    $adminBy[$invoice['updated_by']]['amount'] += $invoice[$key];

    if ($invoice['payment_type'] == 1) {
        $teacherBy[$invoice['teacher_id']]['amount_card'] += $invoice[$key];
        $adminBy[$invoice['updated_by']]['amount_card'] += $invoice[$key];
        $groupsBy[$invoice['group_id']]['amount_card'] += $invoice[$key];
    }else{
        $teacherBy[$invoice['teacher_id']]['amount_cache'] += $invoice[$key];
        $adminBy[$invoice['updated_by']]['amount_cache'] += $invoice[$key];
        $groupsBy[$invoice['group_id']]['amount_cache'] += $invoice[$key];
    }
}



$today = date("Y-m-d");
$year = date("Y");
$todayHref = "/finance/check?start=$today&end=$today";

?>

<div class="row px-3 mb-2">
    <div class="col-md-12">
        <div class="d-flex">
            <a href="/finance/index" type="submit" class="btn btn-sm btn-outline-primary">Gruhlar bo'yicha</a>
            <a href="/finance/check" type="submit" class="btn btn-sm btn-primary">Kunlik Hisobot</a>
        </div>
    </div>
</div>

<div class="row px-3 mb-5">
    <div class="col-md-12">
        <div class="card card-body">
            <form action="/finance/check" method="get" class="row">
                <label class="col-md-3">
                    <span class="text-danger">Dan</span>
                    <input name="start" class="form-control border border-danger" type="date" value="<?=$start?>">
                </label>
                <label class="col-md-3">
                    <span class="text-danger">Gacha</span>
                    <input name="end" class="form-control border border-danger" type="date" value="<?=$end?>">
                </label>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Tekshirish</button>
                </div>
            </form>
            <div class="d-flex gap-3 mt-2 table-responsive" style="font-size: 12px">
                <a href="<?=$todayHref?>">#Bugun</a>
                <a href="<?="/finance/check?start=$year-01-01&end=$year-01-"?><?=cal_days_in_month(CAL_GREGORIAN, '01', $year)?>">#Январь</a>
                <a href="<?="/finance/check?start=$year-02-01&end=$year-02-"?><?=cal_days_in_month(CAL_GREGORIAN, '02', $year)?>">#Ферваль</a>
                <a href="<?="/finance/check?start=$year-03-01&end=$year-03-"?><?=cal_days_in_month(CAL_GREGORIAN, '03', $year)?>">#Март</a>
<!--                <a href="--><?php //="/finance/check?start=$year-04-01&end=$year-04-"?><!----><?php //=cal_days_in_month(CAL_GREGORIAN, '04', $year)?><!--">#Апрель</a>-->
<!--                <a href="--><?php //="/finance/check?start=$year-05-01&end=$year-05-"?><!----><?php //=cal_days_in_month(CAL_GREGORIAN, '05', $year)?><!--">#Май</a>-->
<!--                <a href="--><?php //="/finance/check?start=$year-06-01&end=$year-06-"?><!----><?php //=cal_days_in_month(CAL_GREGORIAN, '06', $year)?><!--">#Июнь</a>-->
<!--                <a href="--><?php //="/finance/check?start=$year-07-01&end=$year-07-"?><!----><?php //=cal_days_in_month(CAL_GREGORIAN, '07', $year)?><!--">#Июль</a>-->
<!--                <a href="--><?php //="/finance/check?start=$year-08-01&end=$year-08-"?><!----><?php //=cal_days_in_month(CAL_GREGORIAN, '08', $year)?><!--">#Август</a>-->
<!--                <a href="--><?php //="/finance/check?start=$year-09-01&end=$year-09-"?><!----><?php //=cal_days_in_month(CAL_GREGORIAN, '09', $year)?><!--">#Сентябрь</a>-->
<!--                <a href="--><?php //="/finance/check?start=$year-10-01&end=$year-10-"?><!----><?php //=cal_days_in_month(CAL_GREGORIAN, '10', $year)?><!--">#Октябрь</a>-->
<!--                <a href="--><?php //="/finance/check?start=$year-11-01&end=$year-11-"?><!----><?php //=cal_days_in_month(CAL_GREGORIAN, '11', $year)?><!--">#Ноябрь</a>-->
<!--                <a href="--><?php //="/finance/check?start=$year-12-01&end=$year-12-"?><!----><?php //=cal_days_in_month(CAL_GREGORIAN, '12', $year)?><!--">#Декабрь</a>-->
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2">
        <div class="card card-body">
            <h5>Total: <?=formatPrice($totalPayed)?></h5>
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <div class="card card-body table-responsive">
            <h6 class="text-warning">GroupBy</h6>
            <table class="table table-hover table-striped table-sm">
                <thead>
                <tr>
                    <th>Gruh</th>
                    <th>Naqd</th>
                    <th>Karta</th>
                    <th>Umumiy</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($groupsBy as $group => $groupItems): ?>
                    <tr>
                        <td><?=$groupItems['group']?></td>
                        <td><?=formatPrice($groupItems['amount_cache'])?></td>
                        <td><?=formatPrice($groupItems['amount_card'])?></td>
                        <td><?=formatPrice($groupItems['amount'])?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <div class="card card-body table-responsive">
            <h6 class="text-warning">TeacherBy</h6>
            <table class="table table-hover table-striped table-sm">
                <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Naqd</th>
                    <th>Karta</th>
                    <th>Umumiy</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($teacherBy as $teacher => $teacherItems): ?>
                    <tr>
                        <td><?=$teacherItems['teacher']?></td>
                        <td><?=formatPrice($teacherItems['amount_cache'])?></td>
                        <td><?=formatPrice($teacherItems['amount_card'])?></td>
                        <td><?=formatPrice($teacherItems['amount'])?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <div class="card card-body table-responsive">
            <h6 class="text-warning">AdminBy</h6>
            <table class="table table-hover table-striped table-sm">
                <thead>
                <tr>
                    <th>Admin</th>
                    <th>Naqd</th>
                    <th>Karta</th>
                    <th>Umumiy</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($adminBy as $admin => $adminItems): ?>
                    <tr>
                        <td><?=$adminItems['admin']?></td>
                        <td><?=formatPrice($adminItems['amount_cache'])?></td>
                        <td><?=formatPrice($adminItems['amount_card'])?></td>
                        <td><?=formatPrice($adminItems['amount'])?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
