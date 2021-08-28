<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Gastos;

/**
 * GastosSearch represents the model behind the search form about `app\models\Gastos`.
 */
class GastosSearch extends Gastos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'inmueble_id','operacion_id'], 'integer'],
            [['fecha', 'estado', 'observacion'], 'safe'],
            [['importe'], 'number'],
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
        $query = Gastos::find();

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
            'fecha' => $this->fecha,
            'importe' => $this->importe,
        ]);

        $query->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
