<?php

namespace frontend\modules\admin\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\admin\models\Config;

/**
 * ConfigSearch represents the model behind the search form of `frontend\modules\admin\models\Config`.
 */
class ConfigSearch extends Config
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'center_id'], 'integer'],
            [['title', 'bot_token', 'chat_id'], 'safe'],
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
        $query = Config::find();

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
            'center_id' => $this->center_id,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'bot_token', $this->bot_token])
            ->andFilterWhere(['ilike', 'chat_id', $this->chat_id]);

        return $dataProvider;
    }
}
