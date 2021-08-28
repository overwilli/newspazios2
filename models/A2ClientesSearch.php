<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\A2Clientes;

/**
 * A2ClientesSearch represents the model behind the search form about `app\models\A2Clientes`.
 */
class A2ClientesSearch extends A2Clientes
{

	public $id_inmueble;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_cliente', 'EST_CIVIL', 'PRIM_NUPCIAS'], 'integer'],
            [['DNI', 'NOMBRE', 'NOMBRE_FANTASIA', 'DIRECCION', 'LOCALIDAD', 'BARRIO', 'PROVINCIA', 
                'TELEFONO', 'TELEF2', 'TELEF3', 'EMAIL', 'NRO_CUENTA', 'CUIL', 'FECNAC', 'DNI_CONYUGE', 'NOMBRE_CONYUGE', 
                'CUIL_CONYUGE', 'OBSERVACIONES','estado'], 'safe'],
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
        $query = A2Clientes::find();
		$query->joinWith(['operacionInmobiliaria']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'NOMBRE' => SORT_ASC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		if(isset($params['A2ClientesSearch']['id_inmueble'])){
			$this->id_inmueble=$params['A2ClientesSearch']['id_inmueble'];
		}else{
			$this->id_inmueble="";
		}
		
        // grid filtering conditions
        $query->andFilterWhere([
            'id_cliente' => $this->id_cliente,
            'EST_CIVIL' => $this->EST_CIVIL,
            'FECNAC' => $this->FECNAC,
            'PRIM_NUPCIAS' => $this->PRIM_NUPCIAS,
			'a2_operaciones_inmobiliarias.cod_propiedad'=> $this->id_inmueble ,
        ]);
		
        $query->andFilterWhere(['like', 'DNI', $this->DNI])
            ->andFilterWhere(['like', 'NOMBRE', $this->NOMBRE])
            ->andFilterWhere(['like', 'NOMBRE_FANTASIA', $this->NOMBRE_FANTASIA])
            ->andFilterWhere(['like', 'DIRECCION', $this->DIRECCION])
            ->andFilterWhere(['like', 'LOCALIDAD', $this->LOCALIDAD])
            ->andFilterWhere(['like', 'BARRIO', $this->BARRIO])
            ->andFilterWhere(['like', 'PROVINCIA', $this->PROVINCIA])
            ->andFilterWhere(['like', 'TELEFONO', $this->TELEFONO])
            ->andFilterWhere(['like', 'TELEF2', $this->TELEF2])
            ->andFilterWhere(['like', 'TELEF3', $this->TELEF3])
            ->andFilterWhere(['like', 'EMAIL', $this->EMAIL])
            ->andFilterWhere(['like', 'NRO_CUENTA', $this->NRO_CUENTA])
            ->andFilterWhere(['like', 'CUIL', $this->CUIL])
            ->andFilterWhere(['like', 'DNI_CONYUGE', $this->DNI_CONYUGE])
            ->andFilterWhere(['like', 'NOMBRE_CONYUGE', $this->NOMBRE_CONYUGE])
            ->andFilterWhere(['like', 'CUIL_CONYUGE', $this->CUIL_CONYUGE])
            ->andFilterWhere(['like', 'OBSERVACIONES', $this->OBSERVACIONES])
            ->andFilterWhere(['like', 'a2_clientes.estado', $this->estado]);
			
		

        return $dataProvider;
    }
}
