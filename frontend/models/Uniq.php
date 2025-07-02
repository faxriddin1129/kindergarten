<?php

namespace frontend\models;

/**
 * This is the model class for table "{{%uniq}}".
 *
 * @property int $id
 * @property int|null $phone
 */
class Uniq extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%uniq}}';
    }


    public function rules()
    {
        return [
            [['phone'], 'default', 'value' => null],
            [['phone'], 'required'],
            [['phone'], 'safe'],
            ['phone', 'unique', 'targetClass' => '\frontend\models\Uniq', 'message' => 'This phone has already been taken.'],
        ];
    }

}
