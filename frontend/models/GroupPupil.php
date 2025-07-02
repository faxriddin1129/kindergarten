<?php

namespace frontend\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%group_pupil}}".
 *
 * @property int $id
 * @property int|null $group_id
 * @property int|null $pupil_id
 * @property int|null $date
 * @property int|null $leave_date
 * @property int|null $status
 * @property int|null $comment
 * @property int|null $updated_by
 * @property int|null $created_by
 *
 * @property Groups $group
 * @property Pupil $pupil
 * @property Pupil $updatedBy
 */
class GroupPupil extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            BlameableBehavior::class
        ];
    }

    const STATUS_ACTIVE = 1;
    const STATUS_LEAVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%group_pupil}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'pupil_id', 'date', 'leave_date', 'status', 'comment', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['group_id', 'pupil_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['date', 'comment'], 'string'],
            [['leave_date'], 'safe'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::class, 'targetAttribute' => ['group_id' => 'id']],
            [['pupil_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pupil::class, 'targetAttribute' => ['pupil_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'pupil_id' => 'Pupil ID',
        ];
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Groups::class, ['id' => 'group_id']);
    }

    /**
     * Gets query for [[Pupil]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPupil()
    {
        return $this->hasOne(Pupil::class, ['id' => 'pupil_id']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }
}
