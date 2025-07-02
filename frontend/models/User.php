<?php

namespace frontend\models;

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
 * @property Expenses[] $expenses
 * @property Expenses[] $expenses0
 * @property ExpensesCategory[] $expensesCategories
 * @property ExpensesCategory[] $expensesCategories0
 * @property Groups[] $groups
 * @property Groups[] $groups0
 * @property Groups[] $groups1
 * @property Groups[] $groups2
 * @property Pupil[] $pupils
 * @property Pupil[] $pupils0
 * @property Pupil[] $pupils1
 * @property Transactions[] $transactions
 * @property Transactions[] $transactions0
 * @property UserDetail[] $userDetails
 */
class User extends \yii\db\ActiveRecord
{
    public $password;

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
            [['username', 'phone', 'first_name', 'last_name'], 'required'],
            [['status', 'role', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'role', 'created_at', 'updated_at'], 'integer'],
            [['token'], 'string'],
            [['username', 'phone', 'first_name', 'last_name', 'password_hash', 'password_reset_token', 'lang', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['password_reset_token'], 'unique'],
            [['phone'], 'unique'],
            [['username'], 'unique'],
            [['password'], 'string', 'min' => 8],
//            [['password'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'phone' => 'Телефон',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Статус',
            'role' => 'Роль',
            'lang' => 'Язык',
            'token' => 'Token',
            'created_at' => 'Создан в',
            'updated_at' => 'Обновлено в',
            'verification_token' => 'Verification Token',
            'password' => 'Пароль',
        ];
    }

    /**
     * Gets query for [[Expenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Expenses0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses0()
    {
        return $this->hasMany(Expenses::class, ['updated_by' => 'id']);
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
        return $this->hasMany(Groups::class, ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[Groups0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups0()
    {
        return $this->hasMany(Groups::class, ['educator_id' => 'id']);
    }

    /**
     * Gets query for [[Groups1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups1()
    {
        return $this->hasMany(Groups::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Groups2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups2()
    {
        return $this->hasMany(Groups::class, ['updated_by' => 'id']);
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
        return $this->hasMany(Transactions::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Transactions0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions0()
    {
        return $this->hasMany(Transactions::class, ['updated_by' => 'id']);
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

    public function create()
    {
        if (!$this->validate()){
            return false;
        }


        $user = new \common\models\User();
        $user->username = $this->username;
        $user->phone = $this->phone;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->role = $this->role;
        $user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save();
    }
}
