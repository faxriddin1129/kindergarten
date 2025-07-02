<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%user_detail}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $passport
 * @property int|null $birthday
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $card_id
 *
 * @property User $user
 */
class UserDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'birthday', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['user_id', 'birthday', 'created_at', 'updated_at'], 'integer'],
            [['passport', 'card_id'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'passport' => 'Passport',
            'birthday' => 'Birthday',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'card_id' => 'Card ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
