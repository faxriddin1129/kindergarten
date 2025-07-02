<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $phone
 * @property integer $role
 * @property integer $first_name
 * @property integer $last_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_SUPER_ADMIN = 1;
    const ROLE_ADMIN = 2;
    const ROLE_TEACHER = 3;
    const ROLE_CASH = 4;
    const ROLE_PUPIL = 5;
    const ROLE_CHEF = 6;
    const ROLE_NANNY = 7;
    const ROLE_ASSISTANT_COOK = 8;
    const ROLE_CLEANER = 9;
    const ROLE_SECURITY = 10;
    const ROLE_MUDIRA = 11;
    const ROLE_ADMISSION_MANAGER = 12;
    const ROLE_MUSICIAN = 13;
    const ROLE_SPORT_MASTER = 14;
    const ROLE_LOGOPED = 15;
    const ROLE_NURSE = 16;
    const ROLE_GYMNAST = 17;


    public static function dropDownRole($html){
        if ($html){
            return [
                self::ROLE_SUPER_ADMIN => '<span class="badge badge-primary">Суперадмин</span></span>',
                self::ROLE_ADMIN => '<span class="badge badge-primary">Администратор</span></span>',
                self::ROLE_TEACHER => '<span class="badge badge-primary">Учитель</span></span>',
                self::ROLE_CASH => '<span class="badge badge-primary">Кассир</span></span>',
                self::ROLE_PUPIL => '<span class="badge badge-primary">Ученик</span></span>',
                self::ROLE_CHEF => '<span class="badge badge-primary">шеф-повар</span>',
                self::ROLE_NANNY => '<span class="badge badge-primary">Няня</span>',
                self::ROLE_ASSISTANT_COOK => '<span class="badge badge-primary">Помощник повара</span>',
                self::ROLE_CLEANER => '<span class="badge badge-primary">Очиститель</span>',
                self::ROLE_SECURITY => '<span class="badge badge-primary">Безопасность</span>',
                self::ROLE_MUDIRA => '<span class="badge badge-primary">Mudira</span>',
                self::ROLE_ADMISSION_MANAGER => '<span class="badge badge-primary">Административный менеджер</span>',
                self::ROLE_MUSICIAN => '<span class="badge badge-primary">Музыкант</span>',
                self::ROLE_SPORT_MASTER => '<span class="badge badge-primary">Мастер спорта</span>',
                self::ROLE_LOGOPED => '<span class="badge badge-primary">Logoped</span>',
                self::ROLE_NURSE => '<span class="badge badge-primary">Медсестра</span>',
                self::ROLE_GYMNAST => '<span class="badge badge-primary">Гимнаст</span>',

            ];
        }

        return [
            self::ROLE_SUPER_ADMIN => 'Суперадмин',
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_TEACHER => 'Учитель',
            self::ROLE_CASH => 'Бухгалтер',
            self::ROLE_PUPIL => 'Ученик',
            self::ROLE_CHEF => 'шеф-повар',
            self::ROLE_NANNY => 'Няня',
            self::ROLE_ASSISTANT_COOK => 'Помощник повара',
            self::ROLE_CLEANER => 'Очиститель',
            self::ROLE_SECURITY => 'Безопасность',
            self::ROLE_MUDIRA => 'Mudira',
            self::ROLE_ADMISSION_MANAGER => 'Административный менеджер',
            self::ROLE_MUSICIAN => 'Музыкант',
            self::ROLE_SPORT_MASTER => 'Мастер спорта',
            self::ROLE_LOGOPED => 'Logoped',
            self::ROLE_NURSE => 'Медсестра',
            self::ROLE_GYMNAST => 'Гимнаст',
        ];
    }


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
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['phone', 'role', 'last_name', 'first_name'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function dropDownSatus($html){
        if ($html){
            return [
                self::STATUS_ACTIVE => '<span class="badge badge-primary">Активный</span>',
                self::STATUS_INACTIVE => '<span class="badge badge-primary">Неактивный</span>',
                self::STATUS_DELETED => '<span class="badge badge-primary">Удалено</span>',
            ];
        }

        return [
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_INACTIVE => 'Неактивный',
        ];
    }
}
