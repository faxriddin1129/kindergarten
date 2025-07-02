<?php

namespace frontend\modules\admin\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\admin\models\UserDetail;

/**
 * UserDetailSearch represents the model behind the search form of `frontend\modules\admin\models\UserDetail`.
 */
class UserDetailSearch extends UserDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'birthday', 'created_at', 'updated_at'], 'integer'],
            [['passport', 'card_id'], 'safe'],
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
        $query = UserDetail::find();

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
            'user_id' => $this->user_id,
            'birthday' => $this->birthday,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'passport', $this->passport])
            ->andFilterWhere(['ilike', 'card_id', $this->card_id]);

        return $dataProvider;
    }
}
