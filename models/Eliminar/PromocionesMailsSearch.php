<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PromocionesMails;

/**
 * PromocionesMailsSearch represents the model behind the search form about `app\models\PromocionesMails`.
 */
class PromocionesMailsSearch extends PromocionesMails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['asunto', 'fecha_creacion', 'fecha_envio', 'condiciones', 'estado'], 'safe'],
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
        $query = PromocionesMails::find()->orderBy('fecha_envio DESC');

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

        $fecha_envio=$this->fecha_envio;
        if(!empty($this->fecha_envio)){
            if(PromocionesMails::validar_fecha()){
            $fecha_envio=explode('/',$this->fecha_envio);
            $fecha_envio=$fecha_envio[2].'-'.$fecha_envio[1].'-'.$fecha_envio[0];
            }
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_envio' => $fecha_envio,
        ]);

        $query->andFilterWhere(['like', 'asunto', $this->asunto])
            ->andFilterWhere(['like', 'condiciones', $this->condiciones])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
