<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GestionCobranzas;

/**
 * GestionCobranzasSearch represents the model behind the search form about `app\models\GestionCobranzas`.
 */
class GestionCobranzasSearch extends GestionCobranzas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cliente_id', 'inmueble_id', 'operacion_id', 'nivel'], 'integer'],
            [['fecha', 'hora', 'observaciones'], 'safe'],
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
        $query = GestionCobranzas::find();

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
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'cliente_id' => $this->cliente_id,
            'inmueble_id' => $this->inmueble_id,
            'operacion_id' => $this->operacion_id,
            'nivel' => $this->nivel,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
