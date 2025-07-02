<?php

namespace frontend\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%transactions}}".
 *
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $amount
 * @property int|null $type
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $date
 * @property string|null $comment
 * @property string|null $discount
 *
 * @property User $createdBy
 * @property Invoice $invoice
 * @property User $updatedBy
 */
class Transactions extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transactions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'amount', 'type', 'status', 'created_by', 'updated_by', 'comment', 'discount'], 'default', 'value' => null],
            [['invoice_id', 'amount', 'type', 'status', 'created_by', 'updated_by'], 'integer'],
            [['date'], 'safe'],
            [['comment'], 'string'],
            [['discount'], 'number'],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::class, 'targetAttribute' => ['invoice_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'amount' => 'Сумма',
            'type' => 'Type',
            'status' => 'Status',
            'created_by' => 'Создан',
            'updated_by' => 'Updated By',
            'date' => 'Дата и время',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::class, ['id' => 'invoice_id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }
}
