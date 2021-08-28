<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\A2Noticias;
use yii\data\SqlDataProvider;

/**
 * A2NoticiasSearch represents the model behind the search form about `app\models\A2Noticias`.
 */
class A2NoticiasSearch extends A2Noticias {

    public $tipo_filtro;
	public $propietario_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_noticia', 'seccion', 'pesoseccion', 'clicks', 'operacion', 'alq_vendida', 'id_estado', 'id_grupo', 'ambientes', 'sup_cubierta', 'sup_terreno', 'habitaciones', 
			'dormitorios', 'banios', 'conv_desocup', 'frente', 'fondo', 'provincia_id', 'localidad_id'], 'integer'],
            [['fecha', 'antetitulo', 'titulo', 'bajada', 'cuerpocompleto', 'foto', 'fotopie', 'autor', 'editor', 'timestamp', 'fechatimestamp', 'fechacarga', 'direccion', 
			'padroniibb', 'padronaguas', 'padronmunicipal','propietario_id','codigo_postal'], 'safe'],
            [['precio'], 'number'],
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
        //$query = A2Noticias::find();
        $query = A2Noticias::find();
        if (isset($params['A2NoticiasSearch']['tipo_filtro']) && $params['A2NoticiasSearch']['tipo_filtro'] == 'disponibles') {
            //$query->joinWith(['operacionInmobiliaria']);
            //$query->where('id_operacion_inmobiliaria IS NULL ');
            $query->where("id_noticia NOT IN(SELECT DISTINCT(cod_propiedad) FROM `a2_operaciones_inmobiliarias` WHERE a2_operaciones_inmobiliarias.estado='ACTIVO' ) AND a2_noticias.estado_reg='ACTIVO'");
        }
        if (isset($params['A2NoticiasSearch']['tipo_filtro']) && $params['A2NoticiasSearch']['tipo_filtro'] == 'administradas') {
            $query->joinWith(['operacionInmobiliaria']);
            //$query->where('id_operacion_inmobiliaria IS NOT NULL  ');
            $query->where("a2_operaciones_inmobiliarias.estado='ACTIVO'");
        }
        if (isset($params['A2NoticiasSearch']['tipo_filtro']) && $params['A2NoticiasSearch']['tipo_filtro'] == 'por_renovar') {
            $query->joinWith(['operacionInmobiliaria']);
            $query->joinWith(['inmueblePropietario']);
            $query->where("contrato_firmado=0 AND (a2_operaciones_inmobiliarias.estado='PENDIENTE' OR 
            a2_operaciones_inmobiliarias.estado='ACTIVO' OR a2_operaciones_inmobiliarias.estado='CONFIRMADO') ");
        }
        if (isset($params['A2NoticiasSearch']['tipo_filtro']) && $params['A2NoticiasSearch']['tipo_filtro'] == 'por_vencer') {
            $query->joinWith(['operacionInmobiliaria']);
			$query->joinWith(['inmueblePropietario']);
			
            $query->where("a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(`a2_operaciones_inmobiliarias`.`hasta_anio`,'-',
					`a2_operaciones_inmobiliarias`.`hasta_mes`,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>=-70");
        }
        if (isset($params['A2NoticiasSearch']['tipo_filtro']) && $params['A2NoticiasSearch']['tipo_filtro'] == 'renovacion_pendientes') {
            $query->joinWith(['operacionInmobiliaria']);
            $query->where("a2_operaciones_inmobiliarias.estado='PENDIENTE'");
        }
        if (isset($params['A2NoticiasSearch']['tiene_expensas'])){
            $query->joinWith(['operacionInmobiliaria']);  
            $query->where("a2_operaciones_inmobiliarias.estado='ACTIVO' AND a2_operaciones_inmobiliarias.tiene_expensas=1");            
        } 
        //$query->where("estado_reg='ACTIVO' OR estado_reg='PENDIENTE'");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'titulo' => SORT_ASC,
                ]
            ],
        ]);


        $this->load($params);
		
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //$query->joinWith(['operacionInmobiliaria']);
            //$query->where('id_operacion_inmobiliaria IS NULL');
            return $dataProvider;
        }

        if ($this->seccion == 16)
            unset($this->seccion);

          
        //$this->tipo_filtro = $params['A2NoticiasSearch']['tipo_filtro'];
        // grid filtering conditions
        $query->andFilterWhere([
            'id_noticia' => $this->id_noticia,
            'fecha' => $this->fecha,
            'seccion' => $this->seccion,
            'pesoseccion' => $this->pesoseccion,
            'clicks' => $this->clicks,
            'timestamp' => $this->timestamp,
            'fechacarga' => $this->fechacarga,
            'operacion' => $this->operacion,
            'alq_vendida' => $this->alq_vendida,
            'id_estado' => $this->id_estado,
            'id_grupo' => $this->id_grupo,
            'precio' => $this->precio,
            'ambientes' => $this->ambientes,
            'sup_cubierta' => $this->sup_cubierta,
            'sup_terreno' => $this->sup_terreno,
            'habitaciones' => $this->habitaciones,
            'dormitorios' => $this->dormitorios,
            'banios' => $this->banios,
            'conv_desocup' => $this->conv_desocup,
            'frente' => $this->frente,
            'fondo' => $this->fondo,
            'provincia_id' => $this->provincia_id,
            'localidad_id' => $this->localidad_id,
			'inmuebles_propietarios.propietario_id' => $this->propietario_id,
            
            
        ]);
		
        $query->andFilterWhere(['like', 'antetitulo', $this->antetitulo])
                ->andFilterWhere(['like', 'titulo', $this->titulo])
				->andFilterWhere(['like', 'codigo_postal', $this->codigo_postal])
                ->andFilterWhere(['like', 'bajada', $this->bajada])
                ->andFilterWhere(['like', 'cuerpocompleto', $this->cuerpocompleto])
                ->andFilterWhere(['like', 'foto', $this->foto])
                ->andFilterWhere(['like', 'fotopie', $this->fotopie])
                ->andFilterWhere(['like', 'autor', $this->autor])
                ->andFilterWhere(['like', 'editor', $this->editor])
                ->andFilterWhere(['like', 'fechatimestamp', $this->fechatimestamp])
                ->andFilterWhere(['like', 'direccion', $this->direccion])
                ->andFilterWhere(['like', 'padroniibb', $this->padroniibb])
                ->andFilterWhere(['like', 'padronaguas', $this->padronaguas])
                ->andFilterWhere(['like', 'padronmunicipal', $this->padronmunicipal]);


        return $dataProvider;
    }

}
