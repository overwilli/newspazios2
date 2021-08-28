<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InmueblePorcentajePropietario;

/**
 * InmueblePorcentajePropietarioSearch represents the model behind the search form about `app\models\InmueblePorcentajePropietario`.
 */
class InmueblePorcentajePropietarioSearch extends InmueblePorcentajePropietario
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'inmueble_id'], 'integer'],
            [['porcentaje'], 'number'],
            [['propietario_id', 'estado'], 'safe'],
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
        $query = InmueblePorcentajePropietario::find();

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
            'inmueble_id' => $this->inmueble_id,
            'porcentaje' => $this->porcentaje,
        ]);

        $query->andFilterWhere(['like', 'propietario_id', $this->propietario_id])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
