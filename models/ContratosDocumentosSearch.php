<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ContratosDocumentos;

/**
 * ContratosDocumentosSearch represents the model behind the search form about `app\models\ContratosDocumentos`.
 */
class ContratosDocumentosSearch extends ContratosDocumentos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'operacion_inmobiliaria_id', 'plantilla_id'], 'integer'],
            [['texto', 'estado', 'usuario_create', 'time_create', 'usuario_update', 'time_update'], 'safe'],
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
        $query = ContratosDocumentos::find();

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
            'operacion_inmobiliaria_id' => $this->operacion_inmobiliaria_id,
            'time_create' => $this->time_create,
            'time_update' => $this->time_update,
            'plantilla_id' => $this->plantilla_id,
        ]);

        $query->andFilterWhere(['like', 'texto', $this->texto])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'usuario_create', $this->usuario_create])
            ->andFilterWhere(['like', 'usuario_update', $this->usuario_update]);

        return $dataProvider;
    }
}
