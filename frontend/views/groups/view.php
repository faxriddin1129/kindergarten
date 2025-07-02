<?php


/** @var frontend\models\Groups $model */
/** @var frontend\models\form\GroupPupilForm $createPupil */

$this->title = $model->title;
?>

<div id="app">

    <div class="row p-3">
        <div class="col-md-12">
            <div class="card p-4">
                <div class="btn-group">
                    <a class="btn btn-sm btn-info" href="/groups"><i class="mdi mdi-arrow-left"></i> Группы</a>
                    <a class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal">Добавить ребенка<i class="mdi mdi-human-greeting"></i></a>
                    <a class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal2">Удалить ребенка <i class="mdi mdi-trash-can"></i></a>
<!--                    <a class="btn btn-sm btn-outline-info" role="button" id="checking" @click="checking()">Проверить <i class="mdi mdi-refresh"></i></a>-->
<!--                    <a class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal3">Оплата <i class="mdi mdi-wallet"></i></a>-->
<!--                    <a class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal5" >Создание счетов-фактур <i class="mdi mdi-wallet"></i></a>-->

                    <?php if (Yii::$app->user->identity['role'] != 3): ?>
                    <a class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal6" >Счета-фактуры <i class="mdi mdi-wallet"></i></a>
                    <?php endif; ?>

                    <a class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal7" >Send Sms <i class="mdi mdi-wallet"></i></a>

                    <?php if (Yii::$app->user->identity['role'] != 3): ?>
                    <a class="btn btn-sm btn-outline-info" href="/groups/update?id=<?= $model->id ?>">Обновить <i class="mdi mdi-pen"></i></a>
                    <?php endif; ?>
                </div>
                <div id="pupils" class="table-responsive mt-3">
                    <table class="table table-sm table-responsive table-hover">
                        <thead>
                        <tr class="text-center">
                            <th>№</th>
                            <th>ID</th>
                            <th>Имя Фамилия</th>
                            <th>Счета-фактуры</th>
                        </tr>
                        </thead>
                        <tbody v-for="item in pupilData">
                            <tr v-if="item.status === 1">
                                <td class="text-center">{{item.sort + (1)}}</td>
                                <td class="text-center">{{item.id}}</td>
                                <td>
                                    <a :href="'/pupil/update?id='+item.id" :title="item.id">{{item.first_name}} {{item.last_name}} <div>{{item.phone}} (<i><small>{{item.date}}</small></i>)</div></a>
                                </td>
                                <th>
                                    <div class="d-flex justify-content-start">
                                        <div v-for="(invoice, index2) in item.invoice" class="border-start border-primary px-1">
                                            <div @click="getInvoice(invoice.id)" data-bs-toggle="modal" data-bs-target="#exampleModal4" v-if="invoice.status == 1" class="text-success cursor-pointer">
                                                <div><small class="mx-1"> {{invoice.amount}}</small></div>
                                                <div><small style="font-size: 8px">{{invoice.period_s}}</small> <small class="">{{invoice.period}} </small></div>
                                            </div>
                                            <div @click="getInvoice(invoice.id)" data-bs-toggle="modal" data-bs-target="#exampleModal4" v-if="invoice.status == 2" class="text-danger cursor-pointer">
                                                <div><small class="mx-1"> {{(invoice.amount - invoice.payment_amount)}}</small></div>
                                                <div> <small style="font-size: 8px"> ({{invoice.period_s}}) </small> <small class=""> {{invoice.period}} </small></div>
                                            </div>
                                            <div v-if="invoice.status == 0">
                                                <div><small class="mx-1"> -</small></div>
                                                <div><small style="font-size: 8px">{{invoice.period_s}}</small> <small style="font-weight: 400">{{invoice.period}} </small></div>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <a class="text-danger cursor-pointer mt-3" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <h6>Исключен из группы <i class="mdi mdi-arrow-bottom-left"></i></h6>
                </a>
                <div class="collapse" id="collapseExample">
                    <div id="pupils" class="table-responsive mt-3">
                        <table class="table table-bordered bg-gradient-danger text-light">
                            <tr>
                                <th style="min-width: 20px">№</th>
                                <th style="min-width: 120px">Имя Фамилия</th>
                                <th style="min-width: 120px">Телефон</th>
                                <th style="min-width: 120px">Пришел</th>
                                <th style="min-width: 120px">Ушел</th>
                                <th style="min-width: 120px">Комментарий</th>
                            </tr>
                            <tr v-for="item in pupilData">
                                <td v-if="item.status === 0" class="text-center">{{item.sort + (1)}}</td>
                                <td v-if="item.status === 0">{{item.first_name}} {{item.last_name}}</td>
                                <td v-if="item.status === 0">{{item.phone}}</td>
                                <td v-if="item.status === 0">{{item.date}}</td>
                                <td v-if="item.status === 0">{{item.leave_date}}</td>
                                <td v-if="item.status === 0">{{item.comment}}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div v-if="loader" class="d-flex justify-content-center w-100 mt-4">
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal CREATE PUPIL -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить ребенка</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <el-select
                            v-model="addPupil"
                            filterable
                            remote
                            reserve-keyword
                            :remote-method="remoteMethod2"
                            placeholder="Поиск ученика..."
                            class="form-control"
                    >
                        <el-option
                                v-for="item in pupils"
                                :key="item.id"
                                :label="item.first_name + ' ' + item.last_name + ' | ' + item.phone"
                                :value="item.id"
                        ></el-option>
                    </el-select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger p-2" data-bs-dismiss="modal">Закрыть</button>
                    <button @click="addPupilGroup()" type="button" data-bs-dismiss="modal" class="btn btn-primary p-2">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal DELETE PUPIL -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="exampleModalLabel">Вы уверены, что хотите удалить ребенка из группы?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <el-select
                            v-model="removePupil"
                            filterable
                            remote
                            reserve-keyword
                            :remote-method="remoteMethod"
                            placeholder="Поиск ученика..."
                            class="form-control"
                    >
                        <el-option
                                v-for="item in pupils"
                                :key="item.id"
                                :label="item.first_name + ' ' + item.last_name + ' | ' + item.phone + ' | ' + item.id"
                                :value="item.id"
                        ></el-option>
                    </el-select>
                    <el-input
                            v-model="deleteComment"
                            :autosize="{ minRows: 2, maxRows: 4 }"
                            type="textarea"
                            placeholder="Комментарий"
                            class="form-control"></el-input>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger p-2" data-bs-dismiss="modal">Закрыть</button>
                    <button @click="removePupilGroup()" type="button" data-bs-dismiss="modal" class="btn btn-primary p-2">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Payment PUPIL -->
    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Оплата</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="px-3">
                        <label class="w-100">
                            <input type="number" class="form-control" v-model="amount" placeholder="Сумма...">
                        </label>
                    </div>
                    <el-select
                            v-model="paymentPupil"
                            filterable
                            remote
                            reserve-keyword
                            :remote-method="remoteMethod"
                            placeholder="Поиск ученика..."
                            class="form-control"
                    >
                        <el-option
                                v-for="item in pupils"
                                :key="item.id"
                                :label="item.first_name + ' ' + item.last_name"
                                :value="item.id"
                        ></el-option>
                    </el-select>
                    <div class="px-3 py-1">
                        <input type="number" class="form-control" placeholder="Скидка..." v-model="discount">
                    </div>
                    <div class="px-3 py-1">
                        <textarea v-model="comment" class="form-control" name="" id="" cols="30" rows="10" placeholder="Комментарий..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger p-2" data-bs-dismiss="modal">Закрыть</button>
                    <button @click="payment()" type="button" data-bs-dismiss="modal" class="btn btn-primary p-2">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Payment Invoices -->
    <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Транзакции ({{invoiceId}})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table v-if="invoices.length > 0" class="table">
                        <tr class="border-light border">
                            <th>№</th>
                            <th>ID</th>
                            <th>Сумма</th>
                            <th>Время</th>
                            <th>Скидка</th>
                            <th>Комментарий</th>
                        </tr>
                        <tr v-for="(item, index) in invoices" class="border-light border">
                            <td>{{++index}}</td>
                            <td>{{item.id}}</td>
                            <td>{{item.amount}} UZS</td>
                            <td>{{item.date}}</td>
                            <td>{{item.discount}}</td>
                            <td>{{item.comment}}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger p-2" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create Invoices -->
    <div class="modal fade" id="exampleModal5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Создание счетов-фактур</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <el-select
                            v-model="invoicePupil"
                            filterable
                            remote
                            reserve-keyword
                            :remote-method="remoteMethod"
                            placeholder="Поиск ученика..."
                            class="form-control"
                    >
                        <el-option
                                v-for="item in pupils"
                                :key="item.id"
                                :label="item.first_name + ' ' + item.last_name"
                                :value="item.id"
                        ></el-option>
                    </el-select>
                    <div class="px-3">
                            <el-date-picker
                                    v-model="invoiceDate"
                                    type="month"
                                    placeholder="Pick a month"
                                    value-format="YYYY-MM"
                                    format="YYYY/MM"
                                    class="w-100"></el-date-picker>

                    </div>
                    <div class="px-3">
                        <el-input-number v-model="invoicePrice" :step="50000" class="w-100 mt-2"></el-input-number>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger p-2" data-bs-dismiss="modal">Закрыть</button>
                    <button @click="createInvoice()" type="button" data-bs-dismiss="modal" class="btn btn-primary p-2">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Group Update Invoices -->
    <div class="modal fade" id="exampleModal6" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="get" action="/groups/update-all">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Счета-фактуры</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?=$model->id?>">
                    <div>
                        <label for="start">Period</label>
                        <input class="form-control form-control-sm" type="month" name="start" required id="start" value="<?=date('Y-m')?>">
                    </div>
<!--                    <div>-->
<!--                        <label for="end">End</label>-->
<!--                        <input class="form-control form-control-sm" type="month" name="end" required id="end" value="--><?php //=date('Y-m')?><!--">-->
<!--                    </div>-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger p-2" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" data-bs-dismiss="modal" class="btn btn-primary p-2">
                        Отправить
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SMS Invoices -->
    <div class="modal fade" id="exampleModal7" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="get" action="/groups/sms-all">
                <input type="hidden" name="group_id" value="<?=$model->id?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">SMS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?=$model->id?>">
                    <div>
                        <label for="period">Period</label>
                        <input class="form-control form-control-sm" type="month" name="period" required id="period" value="2024-10">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger p-2" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" data-bs-dismiss="modal" class="btn btn-primary p-2">
                        Отправить
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/element-plus"></script>
<script>
    const {createApp} = Vue
    const GROUP_ID = '<?=$model->id?>'
    const app = createApp({
        data() {
            return {
                addPupil: '',
                removePupil: '',
                paymentPupil: '',
                amount: '',
                pupils: [],
                loader: false,
                empty: false,
                invoices: [],
                invoiceDate: '',
                invoicePupil: '',
                invoicePrice: '',
                comment: '',
                x: '',
                discount: '',
                deleteComment:'',
                invoiceId:'',
                mainS1: 0,
                mainS2: 0,
                pupilData:[]
            };
        },
        methods: {
            utils() {
                this.getPupils()
            },
            getPupils() {
                this.loader = true
                let Url = '/groups/group-pupils?group_id=' + GROUP_ID
                axios.get(Url).then((r) => {
                    this.loader = false
                    this.pupilData = r.data
                })
            },
            remoteMethod(query) {
                if (query.length >= 3) {
                    let Url = "/pupil/pupil-search?query=" + query+'&group_id=<?=$model->id?>'
                    axios.get(Url)
                        .then(response => {
                            this.pupils = response.data
                            console.log(this.pupils)
                        })
                }
            },
            remoteMethod2(query) {
                if (query.length >= 3) {
                    let Url = "/pupil/pupil-search?query=" + query
                    axios.get(Url)
                        .then(response => {
                            this.pupils = response.data
                            console.log(this.pupils)
                        })
                }
            },
            addPupilGroup() {
                let Url = '/groups/create-pupil-group?pupil_id=' + this.addPupil + '&group_id=' + GROUP_ID
                axios.get(Url).then((r) => {
                    Toastify({
                        text: r.data.message,
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#14d017",
                        }
                    }).showToast();
                    this.addPupil = ''
                    this.data = []
                    this.getPupils()
                }).catch((e) => {
                    Toastify({
                        text: e.response.data.message,
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#a01b43",
                        }
                    }).showToast();
                })
            },
            removePupilGroup() {
                if (this.removePupil.length === 0){
                    Toastify({
                        text: 'Требуемый ввод',
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#a01b43",
                        }
                    }).showToast();
                }
                let Url = '/groups/delete-pupil-group?pupil_id=' + this.removePupil + '&group_id=' + GROUP_ID+"&text="+this.deleteComment
                axios.get(Url).then((r) => {
                    Toastify({
                        text: r.data.message,
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#14d017",
                        }
                    }).showToast();
                    this.removePupil = ''
                    this.data = []
                    this.getPupils()
                }).catch((e) => {
                    Toastify({
                        text: e.response.data.message,
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#a01b43",
                        }
                    }).showToast();
                })
            },
            payment() {
                let Url = '/groups/group-pupils-payment?pupil_id=' + this.paymentPupil + '&group_id=' + GROUP_ID + '&amount='+this.amount+'&comment='+this.comment+'&discount='+this.discount
                axios.get(Url).then((r) => {
                    Toastify({
                        text: r.data.message,
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#14d017",
                        }
                    }).showToast();
                    this.removePupil = ''
                    this.data = []
                    this.getPupils()
                }).catch((e) => {
                    Toastify({
                        text: e.response.data.message,
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#a01b43",
                        }
                    }).showToast();
                })
            },
            checking(){
                document.getElementById('checking').disabled = true
                document.getElementById('checking').innerHTML = 'Loading...'
                let Url = '/groups/group-pupils-checking?group_id=' + GROUP_ID
                axios.get(Url)
                    .then((r) => {
                        Toastify({
                            text: r.data.message,
                            duration: 15000,
                            position: "left",
                            gravity: "bottom",
                            close: true,
                            stopOnFocus: true,
                            style: {
                                background: "#14d017",
                            }
                        }).showToast();
                        document.getElementById('checking').disabled = false
                        document.getElementById('checking').innerHTML = 'Проверить <i class="mdi mdi-refresh"></i>'
                        this.data = []
                        this.getPupils()
                    })
                    .catch((e) => {
                    document.getElementById('checking').disabled = false
                    document.getElementById('checking').innerHTML = 'Проверить <i class="mdi mdi-refresh"></i>'
                    Toastify({
                        text: e.response.data.message,
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#a01b43",
                        }
                    }).showToast();
                })
            },
            getInvoice(id){
                this.invoiceId = id
                let Url = '/groups/invoices?invoice_id=' + id
                axios.get(Url)
                    .then((r) => {
                       console.log(r)
                        this.invoices = r.data
                    })
                    .catch((e) => {
                        Toastify({
                            text: e.response.data.message,
                            duration: 15000,
                            position: "left",
                            gravity: "bottom",
                            close: true,
                            stopOnFocus: true,
                            style: {
                                background: "#a01b43",
                            }
                        }).showToast();
                    })
            },
            createInvoice() {
                let Url = `/groups/create-invoice?group_id=${GROUP_ID}&pupil_id=${this.invoicePupil}&price=${this.invoicePrice}&date=${this.invoiceDate}`
                axios.get(Url).then((r) => {
                    Toastify({
                        text: r.data.message,
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#14d017",
                        }
                    }).showToast();
                    this.invoiceDate = ''
                    this.invoicePupil = ''
                    this.invoicePrice = ''
                    this.getPupils()
                }).catch((e) => {
                    Toastify({
                        text: e.response.data.message,
                        duration: 15000,
                        position: "left",
                        gravity: "bottom",
                        close: true,
                        stopOnFocus: true,
                        style: {
                            background: "#a01b43",
                        }
                    }).showToast();
                })
            },
        },
        components: {},
        mounted() {
            this.utils()
        }
    });
    app.use(ElementPlus);
    app.mount('#app')
</script>