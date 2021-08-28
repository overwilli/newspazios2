<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Clientes;
use yii\data\ArrayDataProvider;

/**
 * ClientesSearch represents the model behind the search form about `app\models\Clientes`.
 */
class ClientesSearch extends Clientes {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nombre', 'direccion', 'barrio', 'empresa', 'empresa_direccion', 'empresa_puesto', 'empresa_antiguedad', 'telefono_pre', 'telefono', 'telefono_alternativo_pre', 'telefono_alternativo', 'celular_pre', 'celular', 'celular_alternativo_pre', 'celular_alternativo', 'email', 'email_alternativo', 'password', 'password_temp', 'hash', 'cumpleanios', 'fecha_actualizacion', 'updated_date'], 'safe'],
            [['dni', 'id_localidad', 'estado'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Clientes::find();

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
            'dni' => $this->dni,
            'id_localidad' => $this->id_localidad,
            'estado' => $this->estado,
            'cumpleanios' => $this->cumpleanios,
            'fecha_actualizacion' => $this->fecha_actualizacion,
            'updated_date' => $this->updated_date,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
                ->andFilterWhere(['like', 'direccion', $this->direccion])
                ->andFilterWhere(['like', 'barrio', $this->barrio])
                ->andFilterWhere(['like', 'empresa', $this->empresa])
                ->andFilterWhere(['like', 'empresa_direccion', $this->empresa_direccion])
                ->andFilterWhere(['like', 'empresa_puesto', $this->empresa_puesto])
                ->andFilterWhere(['like', 'empresa_antiguedad', $this->empresa_antiguedad])
                ->andFilterWhere(['like', 'telefono_pre', $this->telefono_pre])
                ->andFilterWhere(['like', 'telefono', $this->telefono])
                ->andFilterWhere(['like', 'telefono_alternativo_pre', $this->telefono_alternativo_pre])
                ->andFilterWhere(['like', 'telefono_alternativo', $this->telefono_alternativo])
                ->andFilterWhere(['like', 'celular_pre', $this->celular_pre])
                ->andFilterWhere(['like', 'celular', $this->celular])
                ->andFilterWhere(['like', 'celular_alternativo_pre', $this->celular_alternativo_pre])
                ->andFilterWhere(['like', 'celular_alternativo', $this->celular_alternativo])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'email_alternativo', $this->email_alternativo])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'password_temp', $this->password_temp])
                ->andFilterWhere(['like', 'hash', $this->hash]);

        return $dataProvider;
    }

    public function mails_incorrecto() {

        $model = Clientes::find()->All();
        $arreglo_clientes = null;
        foreach ($model as $row) {
            $expresion="/^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,3}$/";
            $expresion1="/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/";
            $expresion2="/^[^0-9][.a-zA-Z0-9_-]+([.][_][-a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/";            
            if (!preg_match($expresion1, $row['email'])) {
                $arreglo_clientes[] = ['dni' => $row['dni'], 'nombre' => $row['nombre'], 'email' => $row['email']];
            }
        }
        $provider = new ArrayDataProvider([
            'allModels' => $arreglo_clientes,
            'pagination' => [
                'pageSize' => 500,
            ],
            'sort' => [
                'attributes' => ['dni', 'nombre','email'],
            ],
        ]);
        return $provider;
    }

}
