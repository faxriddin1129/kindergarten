<?php

/** @var $data */

$this->title = 'Error';
?>

<div class="row p-3">
    <div class="col-md-12">
        <div class="card card-body table-responsive">
            <table class="table table-sm table-responsive table-striped table-hover datatables">
                <thead>
                <tr>
                    <th>#</th>
                    <th>O'quvchi</th>
                    <th>Telefon</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1; foreach ($data as $datum): ?>
                    <tr>
                        <td><?=$i++?></td>
                        <td><a target="_blank" href="/pupil/update?id=<?=$datum['id']?>"><?=$datum['first_name']?> <?=$datum['last_name']?></a></td>
                        <td><?=$datum['phone']?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
