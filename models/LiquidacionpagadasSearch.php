<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Liquidacionpagadas;

/**
 * LiquidacionpagadasSearch represents the model behind the search form about `app\models\Liquidacionpagadas`.
 */
class LiquidacionpagadasSearch extends Liquidacionpagadas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'propietario_id'], 'integer'],
            [['fecha', 'estado', 'fecha_creacion', 'usuario'], 'safe'],
            [['total_cobrado', 'interes_mora', 'comision', 'iva', 'gastos'], 'number'],
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
        $query = Liquidacionpagadas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'fecha' => $this->fecha,
            'propietario_id' => $this->propietario_id,
            'total_cobrado' => $this->total_cobrado,
            'interes_mora' => $this->interes_mora,
            'comision' => $this->comision,
            'iva' => $this->iva,
            'gastos' => $this->gastos,
            'fecha_creacion' => $this->fecha_creacion,
        ]);
        
        
        if($this->estado=='Pagado'){
            $query->andFilterWhere(['or',['like', 'estado', $this->estado],['like', 'estado', 'Anulado']])
            ->andFilterWhere(['like', 'usuario', $this->usuario]);
        }else{
            $query->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'usuario', $this->usuario]);
        }
        

        return $dataProvider;
    }
}
