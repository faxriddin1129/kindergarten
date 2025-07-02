<?php

/** @var yii\web\View $this */
/** @var $model */
/** @var $oldModel */
/** @var $data */

$this->title = 'Hisob';


$total = 0;

foreach ($data as $datum){
    $total += $datum['amount'];
}
?>


<div class="row p-3">
    <div class="col-md-12">
        <div class="card p-4 table-responsive">
            <div class="d-flex gap-2">
                <a class="mb-2" href="/expenses/index?month=<?=$oldModel->month?>">#Orqaga</a>
                <h4><?=$oldModel->month?> => <?=$oldModel->teacher->username?></h4>
                <a class="mb-2" href="/expenses/view-create?id=<?=$oldModel->id?>">#To'lov qilish</a>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr class="table-info"> <th>Rejada</th> <td><?=Yii::$app->formatter->asDecimal($oldModel->amount,0)?></td> </tr>
                        <tr class="table-success"> <th>Berilgan</th> <td><?=Yii::$app->formatter->asDecimal($total,0)?></td> </tr>
                        <tr class="table-danger"> <th>Qarzdorlik</th> <td><?=Yii::$app->formatter->asDecimal(($oldModel->amount-$total),0)?></td> </tr>
                    </table>
                </div>
            </div>

            <h6 class="mt-3">To'lovlar tarixi</h6>
            <table class="table datatables">
                <thead>
                <tr>
                    <th nowrap>ID</th>
                    <th nowrap>Vaqti</th>
                    <th nowrap>Summa</th>
                    <th nowrap>Bergan</th>
                    <th nowrap>Kamentariya</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $datum): ?>
                    <tr>
                        <td nowrap><?=$datum['id']?></td>
                        <td nowrap><?=date('Y-m-d H:i', $datum['created_at'])?></td>
                        <td nowrap><?=Yii::$app->formatter->asDecimal($datum['amount'],0)?></td>
                        <td nowrap><?=$datum->createdBy->username?></td>
                        <td nowrap><?=$datum['comment']?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
