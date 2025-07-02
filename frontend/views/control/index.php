<?php

/* @var $data */

$c = Yii::$app->cache->get('count');
?>


<div class="row">
    <div class="col-md-12 p-5">
        <div class="mb-3">
            <a href="/control/index" class="bg-warning">Voices</a> /
            <a href="/control/uniq">Unique Phones</a>
            <span class="border border-secondary p-1"><b>Jami Berilgan Ovozlar: </b><?=$c??0?></span>
        </div>
        <div class="card card-body bg-white border border-secondary table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Xona</th>
                        <th>Admin</th>
                        <th>Balance</th>
                        <th>Holati</th>
                        <?php if (Yii::$app->getRequest()->getQueryParam('user') == 'user'): ?>
                            <th>Username</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $datum): ?>
                        <tr>
                            <td><a href="/control/view?chat_id=<?=$datum['chat_id']?>"><?=$datum['chat_id']?></a></td>
                            <td><i class="text-warning"><?=$datum['admin']?></i></td>
                            <td><?=$datum['balance']??0?></td>
                            <td>
                                <?php
                                    if ($datum['status'] == 0){
                                        echo '<i>Aloqa toxtagan</i>';
                                    }elseif ($datum['status'] == 1){
                                        echo '<i class="text-danger">Javob kutmoqda</i>';
                                    }
                                ?>
                            </td>
                            <?php if (Yii::$app->getRequest()->getQueryParam('user') == 'user'): ?>
                                <td><?=$datum['user_name']?></td>
                            <?php endif; ?>
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
