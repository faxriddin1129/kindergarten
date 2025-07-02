<?php

namespace frontend\models\form;

use frontend\models\GroupPupil;
use frontend\models\Invoice;

class TransferForm extends \yii\base\Model
{

    public $pupil_id;
    public $group_id;
    public $id;

    public function rules()
    {
        return [
            [['pupil_id', 'group_id'], 'required'],
            [['pupil_id', 'group_id', 'id'], 'integer'],
        ];
    }

    public function save(){
        if (!$this->validate()){
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();

        $groupPupil = GroupPupil::findOne(['group_id' => $this->id, 'pupil_id' => $this->pupil_id]);

//        print_r($groupPupil);
//        die();
        if (!$groupPupil){
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger','Ребенок не найден!');
            return false;
        }

        $groupPupil->group_id = $this->group_id;
        if (!$groupPupil->save()){
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger','Обращайтесь к администратору!');
            return false;
        }

        $invoices = Invoice::find()->andWhere(['pupil_id' => $this->pupil_id])->andWhere(['group_id' => $this->id])->asArray()->all();
        foreach ($invoices as $invoice){
            $invoiceModel = Invoice::findOne($invoice['id']);
            $invoiceModel->group_id = $this->group_id;
            if (!$invoiceModel->save()){
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger','Обращайтесь к администратору!');
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

}