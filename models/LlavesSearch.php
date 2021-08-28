<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Llaves;

/**
 * LlavesSearch represents the model behind the search form about `app\models\Llaves`.
 */
class LlavesSearch extends Llaves
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'inmueble_id', 'inmobiliaria_id'], 'integer'],
            [['numero_llave', 'fecha_solicitud', 'tipo_solicitud', 'persona', 'observacion'], 'safe'],
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
        $query = Llaves::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'fecha_solicitud' => SORT_DESC,
                ]
            ],
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
            'inmobiliaria_id' => $this->inmobiliaria_id,
            'fecha_solicitud' => $this->fecha_solicitud,
        ]);

        $query->andFilterWhere(['like', 'numero_llave', $this->numero_llave])
            ->andFilterWhere(['like', 'tipo_solicitud', $this->tipo_solicitud])
            ->andFilterWhere(['like', 'persona', $this->persona])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
