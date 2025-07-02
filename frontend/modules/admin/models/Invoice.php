<?php

namespace frontend\modules\admin\models;

use Yii;

/**
 * This is the model class for table "{{%invoice}}".
 *
 * @property int $id
 * @property int|null $pupil_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property float|null $amount
 * @property float|null $payment_amount
 * @property int|null $status
 * @property string|null $period
 * @property int|null $group_id
 *
 * @property Pupil $pupil
 * @property Transaction[] $transactions
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invoice}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pupil_id', 'created_at', 'updated_at', 'status', 'group_id'], 'default', 'value' => null],
            [['pupil_id', 'created_at', 'updated_at', 'status', 'group_id'], 'integer'],
            [['amount', 'payment_amount'], 'number'],
            [['period'], 'string'],
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
            'pupil_id' => 'Pupil ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'amount' => 'Amount',
            'payment_amount' => 'Payment Amount',
            'status' => 'Status',
            'period' => 'Period',
            'group_id' => 'Group ID',
        ];
    }

    /**
     * Gets query for [[Pupil]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPupil()
    {
        return $this->hasOne(Pupil::class, ['id' => 'pupil_id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['invoice_id' => 'id']);
    }
}
