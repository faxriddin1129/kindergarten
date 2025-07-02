<?php

namespace frontend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Groups;

/**
 * GroupsSearch represents the model behind the search form of `frontend\models\Groups`.
 */
class GroupsSearch extends Groups
{

    public $teacher;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'educator_id', 'auto_discount', 'created_by', 'updated_by', 'created_at', 'updated_at', 'status', 'room_id'], 'integer'],
            [['title', 'description'], 'safe'],
            [['price', 'teacher'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return array|ActiveDataProvider|\yii\db\ActiveRecord[]
     */
    public function search($params)
    {
        $query = Groups::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'auto_discount' => $this->auto_discount,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'room_id' => $this->room_id,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'description', $this->description]);

        if (\Yii::$app->user->identity['role'] == 3){
            $query->andWhere(['teacher_id' => \Yii::$app->user->id]);
        }

        $query->andWhere(['status' => 0]);

        if ($this->teacher){
            $query->andWhere(['teacher_id' => $this->teacher]);
        }

        return $query->all();
    }
}
