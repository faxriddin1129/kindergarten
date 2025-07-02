<?php

/** @var $data */
/** @var $period */

$this->title = 'Долг';

?>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form class="modal-dialog" method="get" action="/groups/sms-all-in">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">SMS SENDING</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input class="form-control form-control-sm" required type="month" name="period">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </div>
    </form>
</div>



<div class="row p-3">
    <div class="col-md-12">
        <div class="card card-body">
            <div class="d-flex gap-2 justify-content-start align-items-start mb-2">
                <a role="button"  class="btn btn-sm btn-success float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">Sms sending</a>
                <form action="/groups/debts" class="d-flex align-items-start justify-content-start gap-2">
                    <div>
                        <input name="period" id="period" class="form-control form-control-sm" type="month" value="<?=$period?>">
                    </div>
                    <div>
                        <button class="btn btn-primary btn-sm" type="submit">Yuborish</button>
                    </div>
                </form>
            </div>
            <table class="table table-sm table-responsive table-striped table-hover datatables">
                <thead>
                <tr>
                    <th>#</th>
                    <th>O'quvchi</th>
                    <th>Guruh</th>
                    <th>To'lash kerak</th>
                    <th>To'landi</th>
                    <th>Turi</th>
                    <th>Saqlash</th>
                    <th>Qarzdorlik</th>
                    <th>To'lov oyi</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1; foreach ($data as $datum): ?>
                    <tr>
                        <td><?=$i++?></td>
                        <td data-sort="<?=$datum->pupil->first_name?>">
                            <a href="/pupil/update?id=<?=$datum->pupil->id?>">
                                <?=$datum->pupil->first_name?>
                                <?=$datum->pupil->last_name?>
                            </a>
                            <br>
                            <?php $tel  = $datum->pupil->user->phone; ?>
                            <span><a href="tel:<?=$tel?>"><?=$tel?></a></span>
                        </td>
                        <td data-sort="<?=$datum->group->title?>"><?=$datum->group->title?></td>
                        <td><?=$datum->amount?> UZS</td>
                        <td>
                            <input id="id<?=$datum->id?>" type="text" value="<?=$datum->payment_amount?>">
                            <span class="d-none"><?=$datum->payment_amount?></span>
                        </td>
                        <td>
                            <select id="id<?=$datum->id?>type">
                                <option <?php if ($datum->payment_type == 0) echo 'selected'; ?> value="0">Naqd</option>
                                <option <?php if ($datum->payment_type == 1) echo 'selected'; ?> value="1">Karta</option>
                            </select>
                        </td>
                        <td>
                            <a onclick="updateP('<?=$datum->id?>', ('id<?=$datum->id?>'), 'id<?=$datum->id?>type' )" role="button" class="badge-primary badge cursor-pointer s"><i class="mdi mdi-content-save"></i></a>
                        </td>
                        <td class="text-danger"><?=$datum->amount - $datum->payment_amount?> UZS</td>
                        <td><?=$datum->period?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    const updateP = function (invoice_id, inputId, type){
        var inputVla = document.getElementById(inputId).value
        var inputType = document.getElementById(type).value
        var url = '/groups/update-invoice?invoice_id='+invoice_id+'&amount='+inputVla+'&type='+inputType
        const myHeaders = new Headers();
        const requestOptions = {
            method: "GET",
            headers: myHeaders,
            redirect: "follow"
        };
        fetch(url, requestOptions)
            .then((response) => response.json())
            .then((result) => console.log(result))
            .catch((error) => console.error(error));
        Toastify({
            text: 'Success!',
            duration: 15000,
            position: "left",
            gravity: "bottom",
            close: true,
            stopOnFocus: true,
            style: {
                background: "#14d017",
            }
        }).showToast();
    }
</script>
