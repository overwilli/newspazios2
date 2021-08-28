<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AuditoriaContratos;

/**
 * AuditoriaContratosSearch represents the model behind the search form about `app\models\AuditoriaContratos`.
 */
class AuditoriaContratosSearch extends AuditoriaContratos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'operacion_id'], 'integer'],
            [['fecha_contrato', 'fecha_procesamiento', 'estado_contrato'], 'safe'],
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
        $query = AuditoriaContratos::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                    'operacion_id' => SORT_ASC, 
                ]
            ]
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
            'fecha_contrato' => $this->fecha_contrato,
            'fecha_procesamiento' => $this->fecha_procesamiento,
        ]);

        $query->andFilterWhere(['like', 'estado_contrato', $this->estado_contrato]);

        return $dataProvider;
    }
}
