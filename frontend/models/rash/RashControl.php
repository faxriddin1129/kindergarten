<?php

namespace frontend\models\rash;

/**
 * This is the model class for table "{{%rash_control}}".
 * @property int $id
 * @property string|null $quiz_id
 * @property float|null $status
 */
class RashControl extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%rash_control}}';
    }

    public function rules()
    {
        return [
            [['quiz_id', 'status'], 'required'],
        ];
    }

}
