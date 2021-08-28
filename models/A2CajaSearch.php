<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\A2Caja;

/**
 * A2CajaSearch represents the model behind the search form about `app\models\A2Caja`.
 */
class A2CajaSearch extends A2Caja
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_caja', 'numero_caja'], 'integer'],
            [['inicio_fecha', 'inicio_hora', 'inicio_usuario', 'cierre_fecha', 'cierre_hora', 'cierre_usuario', 'timestamp'], 'safe'],
            [['inicio', 'caja_final', 'caja_dia_siguiente', 'rendicion', 'sobrante_faltante', 'caja_cierre', 'cobranzas_efectivo', 'ingresos_varios', 'cheques', 'intereses_mora', 'depositos', 'retenciones', 'gastos_varios', 'supermercado'], 'number'],
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
        $query = A2Caja::find();

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
            'id_caja' => $this->id_caja,
            'inicio_fecha' => $this->inicio_fecha,
            'inicio_hora' => $this->inicio_hora,
            'inicio' => $this->inicio,
            'caja_final' => $this->caja_final,
            'caja_dia_siguiente' => $this->caja_dia_siguiente,
            'rendicion' => $this->rendicion,
            'sobrante_faltante' => $this->sobrante_faltante,
            'caja_cierre' => $this->caja_cierre,
            'cierre_fecha' => $this->cierre_fecha,
            'cierre_hora' => $this->cierre_hora,
            'cobranzas_efectivo' => $this->cobranzas_efectivo,
            'ingresos_varios' => $this->ingresos_varios,
            'cheques' => $this->cheques,
            'intereses_mora' => $this->intereses_mora,
            'depositos' => $this->depositos,
            'retenciones' => $this->retenciones,
            'gastos_varios' => $this->gastos_varios,
            'supermercado' => $this->supermercado,
            'timestamp' => $this->timestamp,
            'numero_caja' => $this->numero_caja,
        ]);

        $query->andFilterWhere(['like', 'inicio_usuario', $this->inicio_usuario])
            ->andFilterWhere(['like', 'cierre_usuario', $this->cierre_usuario]);

        return $dataProvider;
    }
}
