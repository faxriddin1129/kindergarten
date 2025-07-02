<?php

namespace frontend\models;

use yii\behaviors\TimestampBehavior;

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
 * @property int|null $period
 * @property int|null $group_id
 * @property int|null $payment_type
 *
 * @property Pupil $pupil
 * @property Transactions[] $transactions
 */
class Invoice extends \yii\db\ActiveRecord
{
    const STATUS_OPEN = 2;
    const STATUS_CLOSE = 1;

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

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
            [['pupil_id', 'created_at', 'updated_at', 'status', 'period', 'group_id'], 'default', 'value' => null],
            [['pupil_id', 'created_at', 'updated_at', 'status', 'group_id', 'payment_type'], 'integer'],
            [['amount', 'payment_amount'], 'number'],
            [['period'], 'string'],
            [['pupil_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pupil::class, 'targetAttribute' => ['pupil_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::class, 'targetAttribute' => ['group_id' => 'id']],
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
    public function getGroup()
    {
        return $this->hasOne(Groups::class, ['id' => 'group_id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transactions::class, ['invoice_id' => 'id']);
    }
}
