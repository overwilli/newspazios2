<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\A2OperacionesItems;

/**
 * A2OperacionesItemsSearch represents the model behind the search form about `app\models\A2OperacionesItems`.
 */
class A2OperacionesItemsSearch extends A2OperacionesItems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_item', 'id_operacion', 'anio', 'mes', 'id_factura'], 'integer'],
            [['monto'], 'number'],
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
        $query = A2OperacionesItems::find();

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
            'id_item' => $this->id_item,
            'id_operacion' => $this->id_operacion,
            'anio' => $this->anio,
            'mes' => $this->mes,
            'id_factura' => $this->id_factura,
            'monto' => $this->monto,
        ]);

        return $dataProvider;
    }
}
