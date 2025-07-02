<?php

namespace frontend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%expenses}}".
 *
 * @property int $id
 * @property string|null $comment
 * @property float|null $amount
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $period
 * @property int|null $category_id
 * @property int|null $tearcher_id
 * @property int|null $month
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Expenses extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%expenses}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'tearcher_id', 'month'], 'required'],
            [['amount'], 'number'],
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'period'], 'default', 'value' => null],
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'period', 'category_id'], 'integer'],
            [['comment', 'month'], 'string', 'max' => 255],
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
            'comment' => 'Комментарий',
            'amount' => 'Сумма',
            'created_by' => 'Создан',
            'updated_by' => 'Updated By',
            'created_at' => 'Создан в',
            'updated_at' => 'Updated At',
            'period' => 'Период',
            'category_id' => 'Категория',
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

    public function getCategory()
    {
        return $this->hasOne(ExpensesCategory::class, ['id' => 'category_id']);
    }

    public function getTeacher()
    {
        return $this->hasOne(User::class, ['id' => 'tearcher_id']);
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
