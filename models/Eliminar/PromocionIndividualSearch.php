<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PromocionIndividual;

/**
 * PromocionIndividualSearch represents the model behind the search form about `app\models\PromocionIndividual`.
 */
class PromocionIndividualSearch extends PromocionIndividual
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','orden', 'promociones_mails_id'], 'integer'],
            [['enlace', 'url_image', 'estado'], 'safe'],
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
        $query = PromocionIndividual::find();

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
            'promociones_mails_id' => $this->promociones_mails_id,
        ]);

        $query->andFilterWhere(['like', 'enlace', $this->enlace])
            ->andFilterWhere(['like', 'url_image', $this->url_image])
                ->andFilterWhere(['like', 'orden', $this->orden])
            ->andFilterWhere(['=', 'estado', $this->estado]);

        return $dataProvider;
    }
}
