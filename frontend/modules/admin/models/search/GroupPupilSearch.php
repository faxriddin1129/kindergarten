<?php

namespace frontend\modules\admin\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\admin\models\GroupPupil;

/**
 * GroupPupilSearch represents the model behind the search form of `frontend\modules\admin\models\GroupPupil`.
 */
class GroupPupilSearch extends GroupPupil
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'pupil_id'], 'integer'],
            [['date'], 'safe'],
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
            'group_id' => $this->group_id,
            'pupil_id' => $this->pupil_id,
        ]);

        $query->andFilterWhere(['ilike', 'date', $this->date]);

        return $dataProvider;
    }
}
