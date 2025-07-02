<?php

namespace frontend\modules\admin\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $phone
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property int $status
 * @property int|null $role
 * @property string|null $lang
 * @property string|null $token
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 *
 * @property Expense[] $expenses
 * @property Expense[] $expenses0
 * @property ExpensesCategory[] $expensesCategories
 * @property ExpensesCategory[] $expensesCategories0
 * @property Group[] $groups
 * @property Group[] $groups0
 * @property Group[] $groups1
 * @property Group[] $groups2
 * @property Pupil[] $pupils
 * @property Pupil[] $pupils0
 * @property Pupil[] $pupils1
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions0
 * @property UserDetail[] $userDetails
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'phone', 'auth_key', 'password_hash', 'created_at', 'updated_at'], 'required'],
            [['status', 'role', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'role', 'created_at', 'updated_at'], 'integer'],
            [['token'], 'string'],
            [['username', 'phone', 'first_name', 'last_name', 'password_hash', 'password_reset_token', 'lang', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['password_reset_token'], 'unique'],
            [['phone'], 'unique'],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'phone' => 'Phone',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'role' => 'Role',
            'lang' => 'Lang',
            'token' => 'Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
        ];
    }

    /**
     * Gets query for [[Expenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expense::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Expenses0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses0()
    {
        return $this->hasMany(Expense::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[ExpensesCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpensesCategories()
    {
        return $this->hasMany(ExpensesCategory::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[ExpensesCategories0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpensesCategories0()
    {
        return $this->hasMany(ExpensesCategory::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[Groups0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups0()
    {
        return $this->hasMany(Group::class, ['educator_id' => 'id']);
    }

    /**
     * Gets query for [[Groups1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups1()
    {
        return $this->hasMany(Group::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Groups2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups2()
    {
        return $this->hasMany(Group::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Pupils]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPupils()
    {
        return $this->hasMany(Pupil::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Pupils0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPupils0()
    {
        return $this->hasMany(Pupil::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Pupils1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPupils1()
    {
        return $this->hasMany(Pupil::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Transactions0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions0()
    {
        return $this->hasMany(Transaction::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[UserDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserDetails()
    {
        return $this->hasMany(UserDetail::class, ['user_id' => 'id']);
    }
}
