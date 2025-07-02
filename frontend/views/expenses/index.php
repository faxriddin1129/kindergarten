<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var $data */
/** @var $month */

$this->title = Yii::t('app', 'O`qituvchi hisobi');
$this->params['breadcrumbs'][] = $this->title;

$userIds = \yii\helpers\ArrayHelper::getColumn($data,'tearcher_id');
$users = \frontend\models\User::find()->andWhere(['in', 'id', $userIds])->asArray()->indexBy('id')->all();

$readyData = [];
$payEs = [];

foreach ($data as $datum){
    if (!$datum['period']){
        $key = $datum['id'];
        $readyData[$key]['id'] = $key;
        $readyData[$key]['tearcher_id'] = $datum['tearcher_id'];
        $readyData[$key]['month'] = $datum['month'];
        $readyData[$key]['amount'] = $datum['amount'];
        $readyData[$key]['pay'] = null;
        $readyData[$key]['debt'] = null;
    }else{
        if (!isset($payEs[$datum['period']])){
            $payEs[$datum['period']] = 0;
        }
        $payEs[$datum['period']] += $datum['amount'];
    }
}

$padData = [];
foreach ($readyData as $as){
    $payed = $payEs[$as['id']]??0;
    $r = $as;
    $r['pay'] = $payed;
    $r['debt'] = $as['amount']-$payed;
    $r['pay_color'] = 'table-success';
    $r['debt_color'] = '';


    if ($r['debt'] > 0){
        $r['debt_color'] = 'table-danger';
    }




    $padData[] = $r;
}

$readyData = $padData;



$totalDebt = 0;
$totalPay = 0;
$totalAmount = 0;
?>
<div class="row p-3">
    <div class="col-md-12">
        <div class="card p-4 table-responsive">

            <div class="d-flex gap-2">
                <?= Html::a(Yii::t('app', 'Reja kiritish'), ['create'], ['class' => 'btn  btn-success']) ?>
                <form action="/expenses/index">
                    <label>
                        <input required name="month" class="form-control form-control-sm" type="month" value="<?= $month ?>">
                    </label>
                    <button class="btn btn-primary">Tekshirish</button>
                </form>
            </div>

            <div class="mt-3">
                <table class="table datatables">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Muddat</th>
                        <th>O'qituvchi</th>
                        <th>Rejada</th>
                        <th>Berildi</th>
                        <th>Qarzdorlik</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($readyData as $datum): ?>
                    <?php $totalAmount += $datum['amount']; ?>
                    <?php $totalPay += $datum['pay']; ?>
                    <?php $totalDebt += $datum['debt']; ?>
                        <tr>
                            <td><a href="/expenses/view?id=<?=$datum['id']?>">#<?=$datum['id']?></a></td>
                            <td><?=$datum['month']?></td>
                            <td><?=$users[$datum['tearcher_id']]['username']?></td>
                            <td><?=Yii::$app->formatter->asDecimal($datum['amount'],0)?></td>
                            <td class="<?=$datum['pay_color']?>"><?=Yii::$app->formatter->asDecimal($datum['pay'],0)?></td>
                            <td class="<?=$datum['debt_color']?>"><?=Yii::$app->formatter->asDecimal($datum['debt'],0)?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr class="table-warning">
                        <th></th>
                        <th>Total</th>
                        <th></th>
                        <th class="text-info"><?=Yii::$app->formatter->asDecimal($totalAmount,0)?></th>
                        <th class="text-success"><?=Yii::$app->formatter->asDecimal($totalPay,0)?></th>
                        <th class="text-danger"><?=Yii::$app->formatter->asDecimal($totalDebt,0)?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>



