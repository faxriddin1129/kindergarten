<?php

/** @var $data */
/** @var $model */


$this->title = 'Quiz';
?>

<div class="row  p-3">
    <div class="col-md-12">
        <div class="card card-body p-4">
            <table class="table datatables">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>QuizID</th>
                        <th>Status</th>
                        <th>Answers</th>
                        <th>User Answers</th>
                        <th>Rash</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $datum): ?>
                        <tr>
                            <td>#<?=$datum['id']?></td>
                            <td><?=$datum['quiz_id']?></td>
                            <td class="<?php if ($datum['status'] == 'Close') echo 'table-danger'; else echo 'table-success'; ?>"><a href="/rash-control/update?id=<?=$datum['id']?>"><?=$datum['status']?></a></td>
                            <td><a href="/rash-control/quiz?id=<?=$datum['id']?>">#Open</a></td>
                            <td><a href="/rash-control/user?id=<?=$datum['id']?>">#Open</a></td>
                            <td><a href="/rash-control/rash?id=<?=$datum['id']?>">#Open</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div>
                <hr>
                <?php $form = \yii\widgets\ActiveForm::begin() ?>
                    <div class="row">
                        <h3>Yaratish</h3>
                        <div class="col-md-3">
                            <?=$form->field($model,'quiz_id')->label('QuizID')?>
                        </div>
                        <div class="col-md-3">
                            <?=$form->field($model,'status')->dropDownList(['Open' => 'Open', 'Close' => 'Close'])?>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-sm mt-3">Saqlash</button>
                        </div>
                    </div>
                <?php \yii\widgets\ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
