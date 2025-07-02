<?php

namespace frontend\modules\admin\models;

use Yii;

/**
 * This is the model class for table "{{%groups}}".
 *
 * @property int $id
 * @property string|null $title
 * @property float|null $price
 * @property string|null $description
 * @property int|null $teacher_id
 * @property int|null $educator_id
 * @property int|null $auto_discount
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $status
 * @property int|null $room_id
 *
 * @property User $createdBy
 * @property User $educator
 * @property GroupPupil[] $groupPupils
 * @property User $teacher
 * @property User $updatedBy
 */
class Groups extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%groups}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['description'], 'string'],
            [['teacher_id', 'educator_id', 'auto_discount', 'created_by', 'updated_by', 'created_at', 'updated_at', 'status', 'room_id'], 'default', 'value' => null],
            [['teacher_id', 'educator_id', 'auto_discount', 'created_by', 'updated_by', 'created_at', 'updated_at', 'status', 'room_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['teacher_id' => 'id']],
            [['educator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['educator_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'price' => 'Price',
            'description' => 'Description',
            'teacher_id' => 'Teacher ID',
            'educator_id' => 'Educator ID',
            'auto_discount' => 'Auto Discount',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'room_id' => 'Room ID',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Educator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducator()
    {
        return $this->hasOne(User::class, ['id' => 'educator_id']);
    }

    /**
     * Gets query for [[GroupPupils]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupPupils()
    {
        return $this->hasMany(GroupPupil::class, ['group_id' => 'id']);
    }

    /**
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(User::class, ['id' => 'teacher_id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }
}
