<?php

namespace frontend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%room}}".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Room extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%room}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function getCreatedBy(){
        return $this->hasOne(\common\models\User::class,['id' => 'created_by']);
    }

    public function getUpdatedBy(){
        return $this->hasOne(\common\models\User::class,['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'created_by' => 'Сделано',
            'updated_by' => 'Обновлено',
            'created_at' => 'Создан в',
            'updated_at' => 'Обновлено в',
        ];
    }
}
