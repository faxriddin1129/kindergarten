<?php

namespace frontend\models\rash;

/**
 * This is the model class for table "{{%rash_quiz}}".
 * @property int $id
 * @property string|null $rash_control_id
 * @property float|null $type
 * @property float|null $answer_1
 * @property float|null $answer_2
 * @property float|null $answer_3
 * @property float|null $answer_4
 * @property float|null $answer_5
 * @property float|null $number
 * @property float|null $format
 */
class RashQuiz extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%rash_quiz}}';
    }

    public function rules()
    {
        return [
            [['rash_control_id', 'type', 'answer_1', 'number'], 'required'],
            [['answer_1', 'answer_2', 'answer_3', 'answer_4', 'answer_5', 'format', 'number'], 'string'],
        ];
    }

}
