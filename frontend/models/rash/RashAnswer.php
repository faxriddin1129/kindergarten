<?php

namespace frontend\models\rash;

/**
 * This is the model class for table "{{%rash_answer}}".
 * @property int $id
 * @property string|null $rash_control_id
 * @property float|null $full_name
 * @property float|null $answers
 * @property float|null $created_at
 * @property float|null $chat_id
 */
class RashAnswer extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%rash_answer}}';
    }

    public function rules()
    {
        return [
            [['full_name', 'rash_control_id', 'answers', 'chat_id'], 'required'],
            [['created_at'], 'safe'],
        ];
    }

}
