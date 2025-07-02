<?php

/** @var $data */
/** @var $model */


$this->title = 'Quiz';
?>

<div class="row  p-3">
    <div class="col-md-12">
        <div class="card card-body p-4 table-responsive">
            <table class="table datatables">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Created At</th>
                        <th>Answers</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $datum): ?>
                        <tr>
                            <td><?=$datum['id']?></td>
                            <td><?=$datum['full_name']?></td>
                            <td><?=$datum['created_at']?></td>
                            <td><?=$datum['answers']?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
