<?php

namespace frontend\controllers;

use common\models\BotHelpers;
use common\models\SmsHelpers;
use DateInterval;
use DatePeriod;
use DateTime;
use frontend\models\form\GroupPupilForm;
use frontend\models\form\TransferForm;
use frontend\models\GroupPupil;
use frontend\models\Groups;
use frontend\models\Invoice;
use frontend\models\Pupil;
use frontend\models\search\GroupsSearch;
use frontend\models\Transactions;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

date_default_timezone_set('Asia/Tashkent');

class GroupsController extends AppController
{

    public static function months(){
        return [
            '01' => 'yanvar',
            '02' => 'fevral',
            '03' => 'mart',
            '04' => 'aprel',
            '05' => 'may',
            '06' => 'Iyun',
            '07' => 'iyul',
            '08' => 'avgust',
            '09' => 'sentabr',
            '10' => 'oktiabr',
            '11' => 'noyabr',
            '12' => 'dekabr',
        ];
    }

    public function actionIndex($teacher = null)
    {
        $searchModel = new GroupsSearch(['teacher' => $teacher]);
        $data = $searchModel->search($this->request->queryParams);

        $period = date('Y-m');
        $dataQ = Invoice::find()
            ->andWhere(['invoice.status' => Invoice::STATUS_OPEN])
            ->orderBy(['invoice.group_id' => SORT_DESC])
            ->leftJoin('group_pupil gp', 'invoice.pupil_id=gp.pupil_id')
            ->andWhere(['gp.status' => GroupPupil::STATUS_ACTIVE]);
//        if ($period){
//            $dataQ->andWhere(['invoice.period' => $period]);
//        }
        $dataQW = $dataQ->asArray()->all();
        $count = count($dataQW);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'data' => $data,
            'activeCount' => GroupPupil::find()->andWhere(['status' => GroupPupil::STATUS_ACTIVE])->count(),
            'debtCount' => $count,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $createPupil = new GroupPupilForm();

        if ($createPupil->load(\Yii::$app->request->post())) {
            die('asd');
        }

        return $this->render('view', [
            'model' => $model,
            'createPupil' => $createPupil,
        ]);
    }

    public function actionCreate()
    {
        $model = new Groups();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionTransfer($id)
    {
        $model = $this->findModel($id);
        $form = new TransferForm(['id' => $id]);

        if ($form->load(\Yii::$app->request->post()) && $form->save()) {
            return $this->redirect(["/groups/view?id=$id"]);
        }

        return $this->render('transfer', [
            'model' => $model,
            'form' => $form
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Groups::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreatePupilGroup($pupil_id, $group_id)
    {
        \Yii::$app->response->format = 'json';
        $groupModel = $this->findModel($group_id);
        $pupilModel = Pupil::findOne((int)$pupil_id);
        if (!$pupilModel) {
            \Yii::$app->response->statusCode = 400;
            return ['message' => 'Ученик не найден!'];
        }

        $check = GroupPupil::findOne(['pupil_id' => $pupil_id, 'group_id' => $group_id]);
        if ($check) {
            $check->status = GroupPupil::STATUS_ACTIVE;
            if (!$check->save()) {
                \Yii::$app->response->statusCode = 400;
                return ['message' => 'Ученик не найден!'];
            }
            return ['message' => 'Добавлено успешно!'];
        }

        $model = new GroupPupil();
        $model->group_id = $group_id;
        $model->pupil_id = $pupil_id;
        $model->status = GroupPupil::STATUS_ACTIVE;
        $model->date = date('Y-m-d');
        if (!$model->save()) {
            \Yii::$app->response->statusCode = 400;
            return ['message' => 'Сообщите об ошибке администратору!'];
        }

        return ['message' => 'Добавлено успешно!'];
    }

    public function actionDeletePupilGroup($pupil_id, $group_id, $text)
    {
        \Yii::$app->response->format = 'json';
        $check = GroupPupil::findOne(['id' => $pupil_id, 'group_id' => $group_id]);
        if (!$check) {
            \Yii::$app->response->statusCode = 400;
            return ['message' => 'Этот ребенок не в группе!!'];
        }

        $transaction = \Yii::$app->db->beginTransaction();
        $check->leave_date = date('Y-m-d');
        $check->comment = $text;
        $check->status = GroupPupil::STATUS_LEAVE;

        $invoices = Invoice::find()->andWhere(['group_id' => $group_id, 'pupil_id' => $pupil_id, 'status' => Invoice::STATUS_OPEN])->all();
        foreach ($invoices as $invoice) {
            $modelInvoice = Invoice::findOne($invoice['id']);
            $modelInvoice->amount = $modelInvoice->payment_amount;
            $modelInvoice->status = Invoice::STATUS_CLOSE;
            if (!$modelInvoice->save()) {
                $transaction->rollBack();
                return ['message' => 'Сообщите об ошибке администратору!'];
            }
        }

        if (!$check->save()) {
            $transaction->rollBack();
            return ['message' => 'Сообщите об ошибке администратору!'];
        }

        $transaction->commit();
        \Yii::$app->response->statusCode = 200;
        return ['message' => 'Удален успешно!'];
    }

    public function actionGroupPupils($group_id, $test = null)
    {
        $months = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Май',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь'
        ];
        \Yii::$app->response->format = 'json';
        $groupModel = $this->findModel($group_id);
        $sql = 'SELECT (p.id), (p.first_name), (p.last_name), ("user".phone), (group_pupil.date), group_pupil.group_id, group_pupil.status, group_pupil.comment, group_pupil.leave_date FROM group_pupil LEFT JOIN pupil p on p.id = group_pupil.pupil_id LEFT JOIN "user" on p.user_id = "user".id WHERE group_pupil.group_id = ' . $group_id . ' AND group_pupil.status = 1 ORDER BY p.first_name ASC';
        $groupPupils = \Yii::$app->db->createCommand($sql)->queryAll();
        $data = [];
        $i = 0;
        $invoicesArr = Invoice::find()->andWhere(['group_id' => $group_id])->orderBy(['period' => SORT_ASC])->asArray()->all();
        $invoices = [];
        foreach ($invoicesArr as $invoice) {
            $in = $invoice;
            $in['period'] = $months[date('m', strtotime($invoice['period']))];
            $in['period_s'] = $invoice['period'];
            $invoices[$invoice['pupil_id']][] = $in;
        }

        $obraz = [];
        $obrazMonths = [];
        foreach ($invoices as $datum){
            if (count($obraz) < count($datum)){
                $obraz = $datum;
                $obrazMonths = [];
                foreach ($obraz as $item){
                    $obrazMonths[] = $item['period_s'];
                }
            }
        }
        $newInvoices = [];
        foreach ($invoices as $user_id => $invoice){
            if (count($invoice) != count($obrazMonths)){
                $newI = $invoice;
                $checkM = ArrayHelper::getColumn($newI,'period_s');
                $diff = array_diff($obrazMonths,$checkM);
                foreach ($diff as $item){
                    $newI[] = [
                        'status' => 0,
                        'period_s' => $item,
                        'period' => $months[date('m', strtotime($item))]
                    ];
                }
                usort($newI, function($a, $b) {
                    return strcmp($a['period_s'], $b['period_s']);
                });
                $newInvoices[$user_id] = $newI;
            }else{
                $newInvoices[$user_id] = $invoice;
            }
        }
        $invoices = $newInvoices;

        foreach ($groupPupils as $groupPupil) {

            $data[$i]['sort'] = $i;
            $data[$i]['id'] = $groupPupil['id'];
            $data[$i]['first_name'] = $groupPupil['first_name'];
            $data[$i]['last_name'] = $groupPupil['last_name'];
            $data[$i]['phone'] = $groupPupil['phone'];
            $data[$i]['date'] = date('d.m.Y', strtotime($groupPupil['date']));
            $data[$i]['status'] = $groupPupil['status'];
            $data[$i]['leave_date'] = $groupPupil['leave_date'];
            $data[$i]['comment'] = $groupPupil['comment'];
            $data[$i]['invoice'] = $invoices[$groupPupil['id']] ?? [];
            $i++;
        }

        return $data;
    }

    public function actionGroupPupilsChecking($group_id)
    {
        \Yii::$app->response->format = 'json';
        $group = $this->findModel($group_id);
        $period = date('Y-m');
        $groupPupils = GroupPupil::find()->andWhere(['group_id' => $group_id, 'status' => GroupPupil::STATUS_ACTIVE])->asArray()->all();
        $db = \Yii::$app->db->beginTransaction();
        foreach ($groupPupils as $groupPupil) {
            $model = Invoice::findOne(['group_id' => $group_id, 'pupil_id' => $groupPupil['pupil_id'], 'period' => $period]);
            if ($model) {
                if ($model->amount == $model->payment_amount) {
                    $model->status = Invoice::STATUS_CLOSE;
                }
                if (!$model->save()) {
                    $db->rolback();
                    \Yii::$app->response->statusCode = 400;
                    return ['message' => 'Сообщите об ошибке администратору!'];
                }
                if ($model->amount = 0 && $model->payment_amount = 0) {
                    $model->delete();
                }
            } else {
                $newModel = new Invoice();
                $newModel->group_id = $group_id;
                $newModel->pupil_id = $groupPupil['pupil_id'];
                $newModel->amount = $group->price;
                $newModel->payment_amount = 0;
                $newModel->status = Invoice::STATUS_OPEN;
                $newModel->period = $period;
                if (!$newModel->save()) {
                    $db->rolback();
                    \Yii::$app->response->statusCode = 400;
                    return ['message' => 'Сообщите об ошибке администратору!'];
                }
            }
        }

        $db->commit();
        \Yii::$app->response->statusCode = 200;
        return ['message' => 'Успешно!'];
    }

    public function actionGroupPupilsPayment($group_id, $pupil_id, $amount, $comment = null, $discount = null)
    {
        \Yii::$app->response->format = 'json';
        $group = $this->findModel($group_id);
        $pupil = Pupil::findOne(['id' => (int)$pupil_id]);
        $groupPupil = GroupPupil::findOne(['group_id' => (int)$group_id, 'pupil_id' => (int)$pupil_id]);
        if (!$pupil || !$groupPupil) {
            \Yii::$app->response->statusCode = 400;
            return ['message' => 'Ученик не найден!'];
        }

        $invoice = Invoice::find()
            ->orderBy(['id' => SORT_ASC])
            ->andWhere(['group_id' => $group_id])
            ->andWhere(['pupil_id' => $pupil_id])
            ->andWhere(['status' => Invoice::STATUS_OPEN])
            ->one();

        if ($invoice) {
            if (((int)$invoice['amount'] - ((int)$invoice['payment_amount'])) < ((int)$amount + (int)$discount)) {
                \Yii::$app->response->statusCode = 400;
                return ['message' => 'Цена аккаунта неверна!'];
            }

            $db = \Yii::$app->db->beginTransaction();
            $transaction = new Transactions();
            $transaction->invoice_id = $invoice['id'];
            $transaction->amount = $amount;
            $transaction->comment = $comment;
            $transaction->discount = $discount;
            $transaction->date = date('Y-m-d H:i');
            if (!$transaction->save()) {
                $db->rollBack();
                \Yii::$app->response->statusCode = 400;
                return ['message' => 'Сообщите об ошибке администратору!'];
            }
            $invoiceModel = Invoice::findOne(['id' => $invoice['id']]);
            $invoiceModel->payment_amount += ((int)$amount + (int)$discount);
            if ($invoiceModel->payment_amount == (int)$invoiceModel->amount) {
                $invoiceModel->status = Invoice::STATUS_CLOSE;
            }
            if (!$invoiceModel->save()) {
                $db->rollBack();
                \Yii::$app->response->statusCode = 400;
                return ['message' => 'Сообщите об ошибке администратору!'];
            }


            $db->commit();
            \Yii::$app->response->statusCode = 200;
            return ['message' => 'Успешно!'];
        }


        \Yii::$app->response->statusCode = 200;
        return ['message' => 'Успешно!'];
    }

    public function actionInvoices($invoice_id)
    {
        \Yii::$app->response->format = 'json';

        $invoice = Invoice::findOne($invoice_id);
        if (!$invoice) {
            \Yii::$app->response->statusCode = 400;
            return ['message' => 'Счета не найдены!!'];
        }

        \Yii::$app->response->statusCode = 200;
        return Transactions::find()->andWhere(['invoice_id' => $invoice_id])->asArray()->all();
    }

    public function actionDebts($period = null)
    {
        if (!$period){
            $period = date('Y-m');
        }
        $dataQ = Invoice::find()
            ->andWhere(['invoice.status' => Invoice::STATUS_OPEN])
            ->orderBy(['invoice.group_id' => SORT_DESC])
            ->leftJoin('group_pupil gp', 'invoice.pupil_id=gp.pupil_id')
            ->andWhere(['gp.status' => GroupPupil::STATUS_ACTIVE]);
            if ($period){
                $dataQ->andWhere(['invoice.period' => $period]);
            }
            $data = $dataQ->all();
        return $this->render('debts', [
            'data' => $data,
            'period' => $period,
        ]);
    }

    public function actionCreateInvoice($group_id, $pupil_id, $price, $date)
    {
        \Yii::$app->response->format = 'json';
        $group = $this->findModel($group_id);
        $pupil = Pupil::findOne(['id' => (int)$pupil_id]);
        $groupPupil = GroupPupil::findOne(['group_id' => (int)$group_id, 'pupil_id' => (int)$pupil_id]);
        if (!$pupil || !$groupPupil) {
            \Yii::$app->response->statusCode = 400;
            return ['message' => 'Ученик не найден!'];
        }
        $model = Invoice::findOne(['group_id' => $group_id, 'pupil_id' => $pupil_id, 'period' => $date]);
        if (!$model) {
            $model = new Invoice();
        }
        $model->pupil_id = (int)$pupil_id;
        $model->group_id = $group_id;
        $model->payment_amount = 0;
        $model->status = Invoice::STATUS_OPEN;
        $model->period = $date;
        if ($price) {
            if ($price > 0) {
                $model->amount = $price;
            } else {
                $model->amount = $price;
            }
        }
        if (!$model->save()) {
            \Yii::$app->response->statusCode = 400;
            return ['message' => 'Сообщить об ошибке администратору!'];
        }

        \Yii::$app->response->statusCode = 200;
        return ['message' => 'Успешно!'];
    }

    public function actionUpdateAll($start, $end = null, $id = null)
    {
        $end = $start;
        $group = $this->findModel($id);
        $pupilsArr = Pupil::find()
            ->asArray()->indexBy('id')->all();
        $groupsPupils = GroupPupil::find()
            ->andWhere(['group_id' => $id])
            ->andWhere(['status' => 1])
            ->asArray()->all();
        $pupils = [];
        $months = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Май',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь'
        ];
        $paramsStart = $start;
        $paramsEnd = $end;


        $start = new DateTime($start);
        $end = new DateTime($end);
        $end->modify('+1 month');
        $interval = new DateInterval('P1M');
        $period = new DatePeriod($start, $interval, $end);
        $arrInvoices = [];
        $db = \Yii::$app->db->beginTransaction();
        foreach ($period as $date) {
            $period = $date->format('Y-m');
            foreach ($groupsPupils as $groupPupil) {
                $model = Invoice::findOne(['group_id' => $id, 'pupil_id' => $groupPupil['pupil_id'], 'period' => $period]);
                if (!$model) {
                    $model = new Invoice();
                    $model->payment_amount = 0;
                    $model->pupil_id = (int)$groupPupil['pupil_id'];
                    $model->group_id = $id;
                    $model->status = Invoice::STATUS_OPEN;
                    $model->period = $period;
                    $model->amount = $group->price;
                }
                $model->save();
                $inv = $model->attributes;
                $tr = Transactions::findOne(['invoice_id' => $inv['id']]);
                if (!$tr) {
                    $tr = new Transactions();
                    $tr->invoice_id = $inv['id'];
                    $tr->amount = 0;
                    $tr->status = 0;
                    $tr->created_by = \Yii::$app->user->id;
                    $tr->date = date('Y-m-d H:i');
                    $tr->save();
                }
                $inv['transaction'] = $tr->attributes;
                $inv['period_s'] = $months[date('m', strtotime($model['period']))];;

                $arrInvoices[$groupPupil['pupil_id']][] = $inv;
            }
        }

        foreach ($groupsPupils as $groupPupil) {
            $item = $groupPupil;
            $item['pupil'] = $pupilsArr[$groupPupil['pupil_id']];
            $item['invoices'] = $arrInvoices[$groupPupil['pupil_id']];
            $pupils[] = $item;
        }

        $db->commit();

        return $this->render('update-all', [
            'group' => $group,
            'start' => $paramsStart,
            'end' => $paramsEnd,
            'pupils' => $pupils,
        ]);
    }

    public function actionUpdateInvoiceAll($id)
    {
        $group = Groups::findOne($id);
        $bodyParams = Yii::$app->getRequest()->getBodyParams();
        $db = Yii::$app->db->beginTransaction();
        foreach ($bodyParams['invoice'] as $bodyParam) {

            $tr = Transactions::findOne(['id' => $bodyParam['transaction_id']]);
            $in = Invoice::findOne(['id' => $bodyParam['invoice_id']]);
            $old = $in->payment_amount;

            if ($bodyParam['transaction_amount'] != $tr->amount || $bodyParam['invoice_amount'] != $in->amount || $bodyParam['payment_type'] != $in->payment_type) {
                $tr->amount = $bodyParam['transaction_amount'];

                $in->amount = $bodyParam['invoice_amount'];
                $in->payment_type = $bodyParam['payment_type'];
                $in->payment_amount = $tr->amount;

                if ((int)$in->payment_amount >= (int)$in->amount) {
                    $in->status = Invoice::STATUS_CLOSE;
                }else{
                    $in->status = Invoice::STATUS_OPEN;
                }

                if (!$in->save()){
                    $db->rollBack();
                    dd($in->errors);
                }
                if (!$tr->save()){
                    $db->rollBack();
                    dd($tr->errors);
                }


                $pupil = Pupil::findOne($in->pupil_id);

                if ($in->payment_type == 0){
                    $tyS = 'Naqd';
                }else{
                    $tyS = 'Karta';
                }

                $text = 'https://simply.uz/groups/debts';
                $text .= "\n".'💰 To`lov, Invoice №'.$in->id;
                $text .= "\n".'Oldin: '.$old;
                $text .= "\n".'Hozir: '.$in->payment_amount;
                $text .= "\n".'Qarzdorlik: '.($in->amount-$in->payment_amount);
                $text .= "\n".'Gruh: '.$group->title;
                $text .= "\n".'Turi: '.$tyS;
                $text .= "\n".'Period: '.$in->period;
                $text .= "\n".'O`quvchi: '.$pupil->first_name.' '.$pupil->last_name;;
                $text .= "\n".'Admin: '.Yii::$app->user->identity->username;
                $res = BotHelpers::sendMessage([
                    'chat_id' => BotHelpers::GROUP_MAIN,
                    'text' => $text,
                ]);

            }
        }
        $db->commit();
        return $this->redirect("/groups/view?id=$id");
    }

    public function actionSmsAll($period, $group_id)
    {
        $group = $this->findModel($group_id);
        $invoices = Invoice::find()
            ->select(['invoice.id', 'invoice.period', 'invoice.amount', 'invoice.payment_amount', 'invoice.status', 'invoice.group_id', 'invoice.pupil_id', 'pupil.user_id', 'pupil.first_name', 'pupil.last_name', 'public.user.phone'])
            ->leftJoin('pupil', 'invoice.pupil_id = pupil.id')
            ->leftJoin('public.user', 'public.user.id = pupil.user_id')
            ->where(['invoice.period' => $period])
            ->andWhere(['invoice.status' => Invoice::STATUS_OPEN])
            ->andWhere(['invoice.group_id' => $group_id])
            ->asArray()->all();

        $arrTxt = [];
        $i = 0;
        $month = self::months()[(date('m', strtotime($period)))];
        foreach ($invoices as $invoice) {
            if (empty($invoice['phone'])) continue;
            $group_name = $group->title;
            $debt = $invoice['amount']-$invoice['payment_amount'];
            $txt = "Hurmatli ota-ona! Farzandingizni $group_name guruhi $month oyidan $debt UZS qarzdorligini yopishingiz so'raladi. Aks holda dars mashg'ulotlaridan chetlatishga majburmiz. Parvoz o'quv markazi rahbariyati.";
            $arrTxt[$i]['user_sms_id'] = 'sms'.$invoice['phone'];
            $arrTxt[$i]['to'] = "998".$invoice['phone'];
            $arrTxt[$i]['text'] = $txt;
            $i++;
        }
        $ar = [
            'from' => "4546",
            "dispatch_id" => 123,
            "messages" => $arrTxt
        ];
        $m = new SmsHelpers();
        $response = $m->sendSms($ar);
        dd($ar,$response);
    }

    public function actionUpdateInvoice($invoice_id,$amount, $type)
    {
        $db = Yii::$app->db->beginTransaction();
        $invoice = Invoice::findOne($invoice_id);
        $pupil = Pupil::findOne($invoice->pupil_id);
        $old = $invoice->payment_amount;
        $transaction = Transactions::find()->andWhere(['invoice_id' => $invoice->id])->one();
        $transaction->amount = $amount;
        $invoice->payment_amount = $amount;
        $invoice->payment_type = $type;
        if ((int)$invoice->payment_amount >= (int)$invoice->amount) {
            $invoice->status = Invoice::STATUS_CLOSE;
        }else{
            $invoice->status = Invoice::STATUS_OPEN;
        }
        $transaction->save();
        $invoice->save();
        Yii::$app->response->format = 'json';

        $db->commit();

        $group = Groups::findOne($invoice->group_id);

        if ($type == 0){
            $tyS = 'Naqd';
        }else{
            $tyS = 'Karta';
        }

        $text = 'https://simply.uz/groups/debts';
        $text .= "\n".'💰 To`lov, Invoice №'.$invoice->id;
        $text .= "\n".'Oldin: '.$old;
        $text .= "\n".'Hozir: '.$invoice->payment_amount;
        $text .= "\n".'Qarzdorlik: '.($invoice->amount-$invoice->payment_amount);
        $text .= "\n".'Gruh: '.$group->title;
        $text .= "\n".'Turi: '.$tyS;
        $text .= "\n".'Period: '.$invoice->period;
        $text .= "\n".'O`quvchi: '.$pupil->first_name.' '.$pupil->last_name;
        $text .= "\n".'Admin: '.Yii::$app->user->identity->username;
        $res = BotHelpers::sendMessage([
            'chat_id' => BotHelpers::GROUP_MAIN,
            'text' => $text,
        ]);



        return json_encode($res);
    }

    public function actionSmsAllIn($period = null)
    {
        $dataQ = Invoice::find()
            ->select(['invoice.id', 'invoice.period', 'invoice.amount', 'invoice.payment_amount', 'invoice.status', 'invoice.group_id', 'invoice.pupil_id', 'pupil.user_id', 'pupil.first_name', 'pupil.last_name', 'public.user.phone', 'g.title'])
            ->leftJoin('pupil', 'invoice.pupil_id = pupil.id')
            ->leftJoin('public.user', 'public.user.id = pupil.user_id')
            ->leftJoin('group_pupil gp', 'invoice.pupil_id=gp.pupil_id')
            ->leftJoin('groups g', 'g.id=invoice.group_id')
            ->andWhere(['invoice.status' => Invoice::STATUS_OPEN])
            ->orderBy(['invoice.group_id' => SORT_DESC])
            ->andWhere(['gp.status' => GroupPupil::STATUS_ACTIVE]);
            if ($period){
                $dataQ->andWhere(['invoice.period' => $period]);
            }
            $data = $dataQ->asArray()->all();

        $arrTxt = [];
        $i = 0;
        $month = self::months()[(date('m', strtotime($period)))];
        foreach ($data as $invoice) {
            if (empty($invoice['phone'])) continue;
            $group_name = $invoice['title'];
            $debt = $invoice['amount']-$invoice['payment_amount'];
            $txt = "Hurmatli ota-ona! Farzandingizni $group_name guruhi $period oyidan $debt UZS qarzdorligini yopishingiz so'raladi. Aks holda dars mashg'ulotlaridan chetlatishga majburmiz. Parvoz o'quv markazi rahbariyati.";
            $arrTxt[$invoice['group_id']][$i]['user_sms_id'] = 'sms'.$invoice['phone'];
            $arrTxt[$invoice['group_id']][$i]['to'] = "998".$invoice['phone'];
            $arrTxt[$invoice['group_id']][$i]['text'] = $txt;
            $i++;
        }
        $ar = [];
        foreach ($arrTxt as $item){
            $rt = array_values($item);
            $ar[] = [
                'from' => "4546",
                "dispatch_id" => 123,
                "messages" => $rt
            ];
        }
        $re = [];
        foreach ($ar as $item){
            $m = new SmsHelpers();
            $response = $m->sendSms($item);
            $re[] = $response;
            sleep(0.5);
        }
        dd($ar,$re);
    }
}
