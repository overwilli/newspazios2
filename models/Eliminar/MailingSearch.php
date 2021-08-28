<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mailing;
use yii\data\ArrayDataProvider;
/**
 * MailingSearch represents the model behind the search form about `app\models\Mailing`.
 */
class MailingSearch extends Mailing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tipo'], 'integer'],
            [['subject', 'archivo', 'fecha_envio', 'ultimo_envio'], 'safe'],
            [['estado'], 'boolean'],
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
        $query = Mailing::find();

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
            'tipo' => $this->tipo,
            'fecha_envio' => $this->fecha_envio,
            'ultimo_envio' => $this->ultimo_envio,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'archivo', $this->archivo]);

        return $dataProvider;
    }
    
    public function plantillas() {

        $model = Mailing::find()->All();
        $arreglo_templates= null;
        foreach ($model as $row) {
            
            if ($row['tipo']==4) {
                $arreglo_templates[] = ['id' => $row['id'],'subject'=>$row['subject'], 'tipo' => $row['tipo'], 'fecha_envio' => $row['fecha_envio'],
                    'ultimo_envio' => $row['ultimo_envio'],'estado' => $row['estado']];
            }
        }
        $provider = new ArrayDataProvider([
            'allModels' => $arreglo_templates,
            'pagination' => [
                'pageSize' => 500,
            ],
            'sort' => [
                'attributes' => ['subject'],
            ],
        ]);
        return $provider;
    }
}
