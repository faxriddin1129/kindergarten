<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property int $id
 * @property int|null $center_id
 * @property string|null $title
 * @property string|null $bot_token
 * @property string|null $chat_id
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['center_id'], 'default', 'value' => null],
            [['center_id'], 'integer'],
            [['title', 'bot_token', 'chat_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'center_id' => 'Center ID',
            'title' => 'Title',
            'bot_token' => 'Bot Token',
            'chat_id' => 'Chat ID',
        ];
    }
}
