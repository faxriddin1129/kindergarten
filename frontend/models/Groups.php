<?php

namespace frontend\models;

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
 * @property int|null $start
 * @property int|null $end
 *
 * @property User $createdBy
 * @property User $educator
 * @property GroupPupil[] $groupPupils
 * @property GroupPupil[] $groupPupilsActive
 * @property User $teacher
 * @property User $updatedBy
 */
class Groups extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

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
            [['price', 'teacher_id', 'educator_id', 'title', 'room_id'], 'required'],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['price'], 'number'],
            [['start', 'end'], 'safe'],
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
            'title' => 'Название',
            'price' => 'Цена',
            'description' => 'Описание',
            'teacher_id' => 'Учитель',
            'educator_id' => 'Педагог',
            'auto_discount' => 'Авто скидка',
            'created_by' => 'Сделано',
            'updated_by' => 'Обновлено',
            'created_at' => 'Создан в',
            'updated_at' => 'Обновлено в',
            'status' => 'Статус',
            'room_id' => 'Комната',
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

    public function getGroupPupilsActive()
    {
        return $this->hasMany(GroupPupil::class, ['group_id' => 'id'])->andWhere(['status' => GroupPupil::STATUS_ACTIVE]);
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

    public function getRoom()
    {
        return $this->hasOne(Room::class, ['id' => 'room_id']);
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
