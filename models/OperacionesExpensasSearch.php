<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OperacionesExpensas;

/**
 * OperacionesExpensasSearch represents the model behind the search form about `app\models\OperacionesExpensas`.
 */
class OperacionesExpensasSearch extends OperacionesExpensas
{	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'operacion_id', 'tipo_expensas_id', 'inmuebles_id', 'mes', 'year', 'comprobante_id'], 'integer'],
            [['importe'], 'number'],
            [['estado'], 'safe'],
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
        $query = OperacionesExpensas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'year' => SORT_DESC,
                    'mes' => SORT_DESC,                    
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
            'operacion_id' => $this->operacion_id,
            'tipo_expensas_id' => $this->tipo_expensas_id,
            'inmuebles_id' => $this->inmuebles_id,
            'mes' => $this->mes,
            'year' => $this->year,
            'importe' => $this->importe,
            'comprobante_id' => $this->comprobante_id,
			'estado_reg' => $this->estado_reg,
        ]);

        $query->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
