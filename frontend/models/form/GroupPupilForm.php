<?php

namespace frontend\models\form;

use frontend\models\Pupil;
use frontend\models\User;
use yii\base\Model;
use yii\web\BadRequestHttpException;

class GroupPupilForm extends Model
{

    public $pupil_id;

    public function rules()
    {
        return [
            [['pupil_id'], 'required'],
            [['pupil_id'], 'integer'],
            [['pupil_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pupil::class, 'targetAttribute' => ['pupil_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'phone' => 'Телефон',
        ];
    }

    public function save(){
        if (!$this->validate()){
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();
        $model = new Pupil();
        if ($this->id){
            $model = Pupil::findOne(['id' => $this->id]);
        }
        $model->first_name = $this->first_name;
        $model->last_name = $this->last_name;
        $user = User::findOne(['phone' => $this->phone]);
        if (!$user){
            $user = new \common\models\User();
            $user->username = $this->phone;
            $user->phone = $this->phone;
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->role = \common\models\User::ROLE_PUPIL;
            $user->status = \common\models\User::STATUS_ACTIVE;
            $user->setPassword($this->phone);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            if (!$user->save()){
                $transaction->rollBack();
                throw new BadRequestHttpException('Ошибка пользователя!');
            }
        }
        $model->user_id = $user->id;
        if (!$model->save()){
            $transaction->rollBack();
            throw new BadRequestHttpException('Ошибка ученика!');
        }

        $transaction->commit();
        return true;
    }

}
