<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\A2Inmobiliarias;

/**
 * A2InmobiliariasSearch represents the model behind the search form about `app\models\A2Inmobiliarias`.
 */
class A2InmobiliariasSearch extends A2Inmobiliarias
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_inmobiliaria'], 'integer'],
            [['nombre_inmobiliaria'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = A2Inmobiliarias::find();

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
            'id_inmobiliaria' => $this->id_inmobiliaria,
        ]);

        $query->andFilterWhere(['like', 'nombre_inmobiliaria', $this->nombre_inmobiliaria]);

        return $dataProvider;
    }
}
