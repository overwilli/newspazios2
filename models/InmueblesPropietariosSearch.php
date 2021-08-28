<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InmueblesPropietarios;

/**
 * InmueblesPropietariosSearch represents the model behind the search form about `app\models\InmueblesPropietarios`.
 */
class InmueblesPropietariosSearch extends InmueblesPropietarios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'inmueble_id', 'propietario_id'], 'integer'],
            [['comision','porcentaje'], 'number'],
            [['fecha_carga', 'fecha_actualizacion', 'usuario_carga', 'usuario_actualizacion','estado'], 'safe'],
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
        $query = InmueblesPropietarios::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination'=> ['pageSize'=>3],

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
            'propietario_id' => $this->propietario_id,
            'comision' => $this->comision,
            'porcentaje' => $this->porcentaje,
            'fecha_carga' => $this->fecha_carga,
            'fecha_actualizacion' => $this->fecha_actualizacion,
            'estado'=>$this->estado
        ]);

        $query->andFilterWhere(['like', 'usuario_carga', $this->usuario_carga])
            ->andFilterWhere(['like', 'usuario_actualizacion', $this->usuario_actualizacion]);

        return $dataProvider;
    }
}
