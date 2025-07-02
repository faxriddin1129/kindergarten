<?php

namespace frontend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\GroupPupil;

/**
 * GroupPupilSearch represents the model behind the search form of `frontend\models\GroupPupil`.
 */
class GroupPupilSearch extends GroupPupil
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'updated_by', 'created_by'], 'integer'],
            [['date', 'comment', 'leave_date', 'group_id', 'pupil_id',], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = GroupPupil::find();

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
            'group_pupil.status' => $this->status,
            'leave_date' => $this->leave_date,
            'updated_by' => $this->updated_by,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['ilike', 'date', $this->date])
            ->andFilterWhere(['ilike', 'comment', $this->comment]);

        if ($this->group_id){
            $query->leftJoin('groups g','g.id=group_pupil.group_id');
            $query->andFilterWhere(['ilike', 'g.title', $this->group_id]);
        }

        if ($this->pupil_id){
            $query->leftJoin('pupil p','p.id=group_pupil.pupil_id');
            $query->andFilterWhere(['ilike', 'p.first_name', $this->pupil_id]);
        }

        return $dataProvider;
    }
}
