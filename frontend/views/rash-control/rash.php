<?php

/** @var $model */
/** @var $answers */
/** @var $questions */

$this->title = 'Rash Control';

$maxCount = 0;

function check($answer, $question):int
{
    $res = 0;
    if ($question['type'] == 'Close'){
        if ($answer == $question['answer_1']){
            $res = 1;
        }
    }else{
        if ($answer == $question['answer_1'] || $answer == $question['answer_2'] || $answer == $question['answer_3'] || $answer == $question['answer_4'] || $answer == $question['answer_5']) {
            $res = 1;
        }
    }

    return $res;
}

$responses = [];
foreach ($answers as $key => $answer) {
    $data = json_decode($answer['answers'],1);

    if ($maxCount < count($data)){
        $maxCount = count($data);
    }

    $padRes = [];
    foreach ($data as $key2 => $d) {
        $res = check($d, $questions[$key2]);
        $padRes[] = $res;
    }
    $responses[] = $padRes;
}

$allData = [];
foreach ($responses as $key => $response) {
    $allData[] = [
        'res' => $response,
        'user' => $answers[$key],
        'ball' => 0,
        'degree' => 0,
    ];

}

?>

<div class="row px-3">
    <div class="col-md-12">
        <div class="card card-body table-responsive">
            <table class="table table-sm datatables">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <?php for ($i = 0; $i<$maxCount; $i++): ?>
                            <th><?=($i+1)?></th>
                        <?php endfor; ?>
                        <th>Ball</th>
                        <th>Daraja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allData as $allDatum): ?>
                        <tr>
                            <td><?=$allDatum['user']['full_name']?></td>
                            <?php foreach ($allDatum['res'] as $re): ?>
                                <td><?=$re?></td>
                            <?php endforeach; ?>
                            <th><?=$allDatum['ball']?></th>
                            <th><?=$allDatum['degree']?></th>
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
        table = $(".datatables").DataTable({
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
            // order: [[ 1, "asc" ]],
            language:dataTabLang
        });
    });
JS;
$this->registerJs($script);
?>










