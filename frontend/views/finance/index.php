<?php

/** @var yii\web\View $this */
/** @var frontend\models\search\GroupPupilSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

/** @var $month */
/** @var $totalCount */
/** @var $debtCount */
/** @var $fullCount */
/** @var $periodAmount */
/** @var $paymentAmount */


$this->title = Yii::t('app', 'Month');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row px-3 mb-2">
    <div class="col-md-12">
        <div class="d-flex">
            <a href="/finance/index" type="submit" class="btn btn-sm btn-primary">Gruhlar bo'yicha</a>
            <a href="/finance/check" type="submit" class="btn btn-sm btn-outline-primary">Kunlik Hisobot</a>
        </div>
    </div>
</div>
<div class="row px-3" id="app">
    <div class="col-md-12">
        <div class="card card-body">
            <form action="/finance/index" class="row">
                <div class="col-md-3">
                    <input name="month" class="form-control" type="month" value="<?=$month?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Tekshirish</button>
                </div>
            </form>
            <div class="row mt-3">
                <div class="col-md-6">
                    <table class="table table-sm table-bordered">
                        <tr><td>Всего счетов-фактур</td><td><?=$totalCount?></td></tr>
                        <tr><td>Счета-фактуры должника</td><td><?=$debtCount?></td></tr>
                        <tr class="bg-gradient-success"><td>100% оплаченные счета</td><td><?=$fullCount?></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-bordered">
                        <tr><td>Расчетная сумма</td><td><?=Yii::$app->formatter->asDecimal($periodAmount,0)?> UZS</td></tr>
                        <tr class="bg-gradient-danger"><td>Задолженность</td><td><?=Yii::$app->formatter->asDecimal(($periodAmount - $paymentAmount),0)?> UZS</td></tr>
                        <tr><td>Оплачено</td><td><?=Yii::$app->formatter->asDecimal($paymentAmount,0)?> UZS</td></tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div v-if="loader" class="d-flex justify-content-center w-100 mt-4">
                        <div class="loader"></div>
                    </div>
                </div>
                <div v-for="item in groups" class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+item.id" aria-expanded="false">
                                {{item.title}}
                            </button>
                        </h2>
                        <div :id="'collapse'+item.id" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div class="col-md-12 table-responsive">
                                    <hr>
                                    <h6 class="text-center">{{item.title}}</h6>
                                    <table class="table table  table-sm mb-2">
                                        <tr>
                                            <th>{{item.teacher}}</th>
                                            <th><b>Umumiy qiymat: </b> <span v-html="item.coming"></span></th>
                                            <th>
                                                <b class="text-success">To'langan: </b> <span v-html="item.payment"></span>
                                                <div>Karta:{{item.payment_card}} | Naqd: {{item.payment_cash}}</div>
                                            </th>
                                            <th><b class="text-danger">Qarzdorlik: </b> <span v-html="item.debt"></span></th>
                                        </tr>
                                    </table>
                                    <div class="text-center mb-2">Bolalar</div>
                                    <table class="table table table-bordered table-sm">
                                        <tr>
                                            <th class="text-secondary">Имя Фамилия</th>
                                            <th class="text-secondary">Телефон</th>
                                            <th class="text-secondary">Счета-фактуры</th>
                                            <th class="text-secondary">Оплачено</th>
                                            <th class="text-secondary">Задолженность</th>
                                        </tr>
                                        <tr v-for="value in item.pupils">
                                            <td v-if="value.status === 1">{{value.full_name}}</td>
                                            <td v-if="value.status === 1">{{value.phone}}</td>
                                            <td v-if="value.status === 1">{{value.invoice_coming}}</td>
                                            <td v-if="value.status === 1" class="text-success">{{value.invoice_payment}}</td>
                                            <td v-if="value.status === 1" class="text-danger">{{value.invoice_debt}}</td>
                                        </tr>
                                    </table>
                                    <a class="text-danger cursor-pointer mt-3" data-bs-toggle="collapse" :href="'#collapseExample'+item.id" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        <h6>Исключен из группы <i class="mdi mdi-arrow-bottom-left"></i></h6>
                                    </a>
                                    <div class="collapse" :id="'collapseExample'+item.id">
                                        <div id="pupils" class="table-responsive mt-3">
                                            <table class="table table table-bordered table-sm">
                                                <tr>
                                                    <th class="text-secondary">Имя Фамилия</th>
                                                    <th class="text-secondary">Телефон</th>
                                                    <th class="text-secondary">Ушли</th>
                                                    <th class="text-secondary">Комментарий</th>
                                                </tr>
                                                <tr v-for="value in item.pupils" class="bg-gradient-danger">
                                                    <td v-if="value.status === 0">{{value.full_name}}</td>
                                                    <td v-if="value.status === 0">{{value.phone}}</td>
                                                    <td v-if="value.status === 0">{{value.leave_date}}</td>
                                                    <td v-if="value.status === 0" colspan="5">{{value.comment}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const {createApp} = Vue
    const DATE = '<?=$month?>'
    const app = createApp({
        data() {
            return {
                groups: '',
                loader:false
            };
        },
        methods: {
            utils() {
                this.getPupils()
            },
            getPupils() {
                this.loader = true
                let Url = '/finance/groups?month='+DATE
                axios.get(Url).then((r) => {
                    this.loader = false
                    this.groups = r.data
                })
            },
        },
        components: {},
        mounted() {
            this.utils()
        }
    });
    app.mount('#app')
</script>
<style>
    .table th, .table td{
        padding: 5px!important;
    }
</style>