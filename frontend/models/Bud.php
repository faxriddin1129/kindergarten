<?php

namespace frontend\models;

/**
 * This is the model class for table "{{%bud}}".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property string|null $messages
 * @property string|null $balance
 * @property string|null $admin
 * @property string|null $created_at
 * @property string|null $status
 * @property string|null $user_name
 */
class Bud extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%bud}}';
    }


    public function rules()
    {
        return [
            [['chat_id', 'messages', 'balance', 'admin', 'created_at', 'status', 'user_name'], 'default', 'value' => null],
            [['chat_id', 'messages', 'balance', 'admin', 'created_at', 'status', 'user_name'], 'safe'],
        ];
    }

}
