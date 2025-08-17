<?php

namespace frontend\controllers;

use frontend\models\GroupPupil;
use frontend\models\Groups;
use frontend\models\Invoice;
use yii\helpers\ArrayHelper;

class FinanceController extends AppController
{

    public function actionIndex($month = null, $test = null)
    {

        $activePupils = GroupPupil::find()->asArray()->andWhere(['status' => 1])->all();
        $pupilIds = ArrayHelper::getColumn($activePupils, 'pupil_id');

        if (!$month){
            $month = date('Y-m');
        }
        $totalCount = Invoice::find()
            ->andWhere(['in' ,'pupil_id', $pupilIds])
            ->andWhere(['period' => $month])->count();
        $debtCount = Invoice::find()
            ->andWhere(['in' ,'pupil_id', $pupilIds])
            ->andWhere(['period' => $month, 'status' => Invoice::STATUS_OPEN])->count();
        $fullCount = Invoice::find()
            ->andWhere(['in' ,'pupil_id', $pupilIds])
            ->andWhere(['period' => $month, 'status' => Invoice::STATUS_CLOSE])->count();
        $periodAmount = Invoice::find()
            ->andWhere(['in' ,'pupil_id', $pupilIds])
            ->andWhere(['period' => $month])->sum('amount');
        $paymentAmount = Invoice::find()
            ->andWhere(['in' ,'pupil_id', $pupilIds])
            ->andWhere(['period' => $month])->sum('payment_amount');
        return $this->render('index',[
            'totalCount' => $totalCount,
            'debtCount' => $debtCount,
            'fullCount' => $fullCount,
            'periodAmount' => $periodAmount,
            'paymentAmount' => $paymentAmount,
            'month' => $month
        ]);
    }

    public function actionGroups($month = null)
    {
        \Yii::$app->response->format = 'json';
        if (!$month){
            $month = date('Y-m');
        }
        $groups = Groups::find()->andWhere(['status' => Groups::STATUS_ACTIVE])->all();
        $data = [];
        $j = 0;
        foreach ($groups as $group){
            $pupils = GroupPupil::find()->andWhere(['group_id' => $group['id']])->andWhere(['status' => 1])->all();
            $pupilIds = ArrayHelper::getColumn($pupils, 'pupil_id');
            $amount = Invoice::find()
                ->andWhere(['group_id' => $group['id'], 'period' => $month])
                ->andWhere(['in', 'pupil_id', $pupilIds])
                ->sum('amount');
            $paymentAmount = Invoice::find()
                ->andWhere(['group_id' => $group['id'], 'period' => $month])
                ->andWhere(['in', 'pupil_id', $pupilIds])
                ->sum('payment_amount');

            $paymentAmountAll = Invoice::find()
                ->andWhere(['group_id' => $group['id'], 'period' => $month])
                ->andWhere(['in', 'pupil_id', $pupilIds])
                ->asArray()->all();
            $card = 0;
            $cash = 0;
            foreach ($paymentAmountAll as $item){
                if ($item['payment_type'] == 1){
                    $card += $item['payment_amount'];
                }else{
                    $cash += $item['payment_amount'];
                }
            }

            $data[$j]['id'] = $group['id'];
            $data[$j]['title'] = $group['title'];
            $data[$j]['teacher'] = $group->teacher->first_name.' '.$group->teacher->last_name;
            $data[$j]['coming'] = \Yii::$app->formatter->asDecimal($amount,0);
            $data[$j]['payment'] = \Yii::$app->formatter->asDecimal($paymentAmount,0);
            $data[$j]['payment_cash'] = \Yii::$app->formatter->asDecimal($cash,0);
            $data[$j]['payment_card'] = \Yii::$app->formatter->asDecimal($card,0);
            $data[$j]['debt'] = \Yii::$app->formatter->asDecimal(($amount - $paymentAmount),0);
            $pupilsData = [];
            $i = 0;
            foreach ($pupils as $pupil){
                $invoice = Invoice::findOne(['group_id' => $group['id'], 'pupil_id' =>$pupil['pupil_id'], 'period'=>$month]);
                $pupilsData[$i]['full_name'] = $pupil->pupil->first_name.' '.$pupil->pupil->last_name;
                $pupilsData[$i]['phone'] = $pupil->pupil->user->phone;
                $pupilsData[$i]['status'] = $pupil['status'];
                $pupilsData[$i]['comment'] = $pupil['comment'];
                $pupilsData[$i]['leave_date'] = $pupil['leave_date'];
                if ($invoice){
                    $pupilsData[$i]['invoice_coming'] = \Yii::$app->formatter->asDecimal($invoice->amount,0);
                    $pupilsData[$i]['invoice_payment'] = \Yii::$app->formatter->asDecimal($invoice->payment_amount,0);
                    $pupilsData[$i]['invoice_debt'] = \Yii::$app->formatter->asDecimal(($invoice->amount - $invoice->payment_amount),0);
                }
                $i++;
            }
            $data[$j]['pupils'] = $pupilsData;
            $j++;
        }
        return $data;
    }

    public function actionCheck($start = null, $end = null)
    {
        if (!$start){ $start = date('Y-m-d'); }
        if (!$end){ $end = date('Y-m-d'); }

        return $this->render('check',[
            'start' => $start,
            'end' => $end
        ]);
    }
}
