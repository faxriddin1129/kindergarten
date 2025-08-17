<?php

/* @var $data */
/* @var $model */


?>


<div class="row">
    <div class="col-md-12 p-5">
        <div class="mb-3">
            <a href="/control/index">Voices</a> /
            <a href="/control/uniq" class="bg-warning">Unique Phones</a>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Qo'shish
            </button>

            <?php $form = \yii\widgets\ActiveForm::begin() ?>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Qo'shish</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?=$form->field($model,'phone')?>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm  btn-primary">Saqlash</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php \yii\widgets\ActiveForm::end() ?>


        </div>
        <div class="card card-body bg-white border border-secondary table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $datum): ?>
                        <tr>
                            <td><?=$datum['id']?></td>
                            <td><?=$datum['phone']?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$script = <<< JS
    jQuery(document).ready(function() {
        table = $(".table").DataTable({
            dom:dataTabDom,
            buttons: [
                'excel'
            ],
            paging:true,
            pageLength: 100,
            lengthMenu: [
                [100, 500, 1000, -1],
                [100, 500, 1000, 'Все']
            ],
            fixedHeader: true,
            deferRender:true,
            info:true,
            filter:true,
            order: [[2,'desc']],
            language:dataTabLang
        });
    });
JS;
$this->registerJs($script);
?>

<style>
    *{
       font-size: 20px!important;
    }
</style>
