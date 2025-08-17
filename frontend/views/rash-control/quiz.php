<?php

/** @var $data */
/** @var $model */
/** @var $id */


$this->title = 'Quiz';

?>

<script type="module">
    import 'https://unpkg.com/mathlive?module';
</script>

<div class="row p-3" id="app">
    <div class="col-md-12">
        <div class="card card-body p-4">
            <div>
                <?php $form = \yii\widgets\ActiveForm::begin() ?>
                <div class="row">
                    <h3>Yaratish</h3>
                    <div class="col-md-3">
                        <?=$form->field($model,'type')->dropDownList(['Close' => 'Close', 'Open' => 'Open'])?>
                    </div>
                    <div class="col-md-3"><?=$form->field($model,'number')?></div>



                    <div class="col-md-3"><div class="form-group field-rashquiz-answer_1 required">
                            <label class="control-label" for="rashquiz-answer_1">Answer 1</label>

                            <math-field
                                    virtual-keyboard-mode="onfocus"
                                    virtual-keyboard-theme="material"
                                    virtual-keyboard-layout="numeric"
                                    v-on:input="updateField('latex1', $event)" style="width: 100%; font-size: 1.2em;">
                            </math-field>

                            <input v-model="latex1" required type="hidden" id="rashquiz-answer_1" class="form-control" name="RashQuiz[answer_1]" aria-required="true">
                        </div>
                    </div>


                    <div class="col-md-3"><div class="form-group field-rashquiz-answer_2">
                            <label class="control-label" for="rashquiz-answer_2">Answer 2</label>
                            <math-field v-on:input="updateField('latex2', $event)" style="width: 100%; font-size: 1.2em;"></math-field>
                            <input v-model="latex2"  type="hidden" id="rashquiz-answer_2" class="form-control" name="RashQuiz[answer_2]">
                        </div>
                    </div>

                    <div class="col-md-3"><div class="form-group field-rashquiz-answer_3">
                            <label class="control-label" for="rashquiz-answer_3">Answer 3</label>
                            <math-field v-on:input="updateField('latex3', $event)" style="width: 100%; font-size: 1.2em;"></math-field>
                            <input v-model="latex3"  type="hidden" id="rashquiz-answer_3" class="form-control" name="RashQuiz[answer_3]">
                        </div>
                    </div>

                    <div class="col-md-3"><div class="form-group field-rashquiz-answer_4">
                            <label class="control-label" for="rashquiz-answer_4">Answer 4</label>
                            <math-field v-on:input="updateField('latex4', $event)" style="width: 100%; font-size: 1.2em;"></math-field>
                            <input v-model="latex4"  type="hidden" id="rashquiz-answer_4" class="form-control" name="RashQuiz[answer_4]">
                        </div>
                    </div>

                    <div class="col-md-3"><div class="form-group field-rashquiz-answer_5">
                            <label class="control-label" for="rashquiz-answer_5">Answer 5</label>
                            <math-field v-on:input="updateField('latex5', $event)" style="width: 100%; font-size: 1.2em;"></math-field>
                            <input v-model="latex5"  type="hidden" id="rashquiz-answer_5" class="form-control" name="RashQuiz[answer_5]">
                        </div>
                    </div>

                    <div class="col-md-3"><div class="form-group field-rashquiz-answer_5">
                            <label class="control-label" for="rashquiz-format">Format</label>
                            <select class="form-select form-select-sm" id="rashquiz-format" name="RashQuiz[format]">
                                <option value="A,B,C,D">A,B,C,D</option>
                                <option value="A,B,C,D,E,F">A,B,C,D,E,F</option>
                            </select>
                        </div>
                    </div>










                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm mt-3">Saqlash</button>
                    </div>
                    <?=$form->field($model,'rash_control_id')->input('hidden',['value' => $id])->label(false)?>
                </div>
                <?php \yii\widgets\ActiveForm::end() ?>
            </div>
            <hr>
            <table class="table datatables">
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Type</th>
                        <th>Answer 1</th>
                        <th>Answer 2</th>
                        <th>Answer 3</th>
                        <th>Answer 4</th>
                        <th>Answer 5</th>
                        <th>Format</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $datum): ?>
                        <tr>
                            <td data-sort="<?=$datum['id']?>"><?=$datum['number']?></td>
                            <td><?=$datum['type']?></td>
                            <td><math-field read-only> <?=$datum['answer_1']?> </math-field></td>
                            <td><math-field read-only> <?=$datum['answer_2']?> </math-field></td>
                            <td><math-field read-only> <?=$datum['answer_3']?> </math-field></td>
                            <td><math-field read-only> <?=$datum['answer_4']?> </math-field></td>
                            <td><math-field read-only> <?=$datum['answer_5']?> </math-field></td>
                            <td><?=$datum['format']?></td>
                            <td><a href="/rash-control/quiz-delete?id=<?=$id?>&quiz_id=<?=$datum['id']?>">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    const {createApp} = Vue
    const app = createApp({
        data() {
            return {
                latex1: '',
                latex2: '',
                latex3: '',
                latex4: '',
                latex5: '',
            };
        },
        methods: {
            updateField(field, event) {
                this[field] = event.target.value;
            },
        },
        components: {},
        mounted() {

        }
    });
    app.mount('#app')
</script>