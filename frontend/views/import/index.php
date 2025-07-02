<?php

/** @var $data */
/** @var $key */
/** @var $groups */
/** @var $users */

$this->title = 'Импортный центр';

?>

<div class="container">
    <?php if (empty($data)): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body">
                    <h6>Импортный центр > <a href="/import/index">Импорт транзакций</a></h6>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <div class="card card-body h-100">
                    <?php $form = \yii\widgets\ActiveForm::begin([
                        'action' => '/import/index',
                        'options' => [
                            'enctype' => 'multipart/form-data'
                        ]
                    ])?>
                    <h5>Выберите файл для импорта</h5>
                    <label for="file"><b>Обзор..</b></label>
                    <input required class="form-control" id="file" name="file" type="file" accept=".xlsx">
                    <button class="btn btn-sm btn-primary mt-2" type="submit">Отправить</button>
                    <?php \yii\widgets\ActiveForm::end() ?>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <div class="card card-body h-100">
                    <h6>Примеры шаблонов</h6>
                    <a href="/example/pupil.xlsx">Ученики в "xlsx" формате</a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-body h-100 mt-2">
                    <h6><b>Внешний вид и правила</b></h6>
                    <table class="table">
                        <tr>
                            <td>ID</td>
                            <td>Название группы</td>
                        </tr>
                        <?php foreach ($groups as $group): ?>
                            <tr>
                                <td class="bg-warning"><?=$group['id']?></td>
                                <td><?=$group['title']?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body">
                    <table class="table table-sm table-hover">
                        <tr>
                            <th>Фамилия</th>
                            <th>Имя</th>
                            <th>Телефон</th>
                            <th>Группа (c)</th>
                            <th>Ученик (c)</th>
                            <th>Старт</th>
                        </tr>
                        <?php foreach ($data as $datum): ?>
                            <tr>
                                <td><?=$datum[0]??null?></td>
                                <th><?=$datum[1]??null?></th>
                                <th><?=$datum[2]??null?></th>
                                <th>
                                    <span class="text-warning"><?=$groups[($datum[3]??null)]['title']??'noGroup'?></span>
                                </th>
                                <th>
                                    <span class="text-warning"><?=$users[($datum[2]??null)]['first_name']??'noPupil'?></span>
                                </th>
                                <th><?=$datum[4]??null?></th>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <a href="/import/pupil?key=<?=$key?>" class="btn btn-sm btn-primary mt-2"><i class="fa fa-save"></i> Сохранить</a>
    <?php endif; ?>
</div>

