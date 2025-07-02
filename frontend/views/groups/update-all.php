<?php

/** @var $group */
/** @var $start */
/** @var $end */
/** @var $pupils */

$this->title = 'Update Center'

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-body table-responsive">
                <?php \yii\widgets\ActiveForm::begin(['action' => '/groups/update-invoice-all?id='.$group['id']]) ?>
                <h6><?= $group->title ?> [ <?= $start ?> & <?= $end ?> ]</h6>
                <table class="table table-bordered table-sm datatables">
                    <thead>
                        <tr>
                            <th>Pupil</th>
                            <th colspan="10000">Invoices</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pupils as $pupil): ?>
                        <tr>
                            <td><?= $pupil['pupil']['last_name'] ?> <?= $pupil['pupil']['first_name'] ?></td>
                            <?php foreach ($pupil['invoices'] as $invoice): ?>
                                <?php
                                    $color = '';
                                    if ($invoice['amount'] > $invoice['transaction']['amount']){
                                        $color = 'table-danger';
                                    }
                                ?>
                                <td class="<?= $color ?>">
                                    <div>
                                        <div> [<?= $invoice['period'] ?>] [<?= $invoice['period_s'] ?>]</div>
                                        <label>
                                            <input required type="text" name="invoice[<?=$invoice['id']?>][invoice_amount]" value="<?= $invoice['amount'] ?>">
                                            <input required type="hidden" name="invoice[<?=$invoice['id']?>][invoice_id]" value="<?= $invoice['id'] ?>">
                                        </label>
                                    </div>
                                    <div>
                                        <div>Payment</div>
                                        <label>
                                            <input required type="text" name="invoice[<?=$invoice['id']?>][transaction_amount]"  value="<?= $invoice['transaction']['amount'] ?>">
                                            <input required type="hidden" name="invoice[<?=$invoice['id']?>][transaction_id]"  value="<?= $invoice['transaction']['id'] ?>">
                                        </label>
                                    </div>
                                    <div>
                                        <div>Type</div>
                                        <label>
                                            <select name="invoice[<?=$invoice['id']?>][payment_type]">
                                                <option <?php if ($invoice['payment_type'] == 0) echo 'selected'; ?> value="0">Naqd</option>
                                                <option <?php if ($invoice['payment_type'] == 1) echo 'selected'; ?> value="1">Karta</option>
                                            </select>
                                        </label>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div>
                    <button type="submit" class="btn btn-sm btn-primary mt-2">Сахранить</button>
                </div>
                <?php \yii\widgets\ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
