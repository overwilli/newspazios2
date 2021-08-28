<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\A2OperacionesInmobiliarias;
use yii\data\SqlDataProvider;

/**
 * A2OperacionesInmobiliariasSearch represents the model behind the search form about `app\models\A2OperacionesInmobiliarias`.
 */
class A2OperacionesInmobiliariasSearch extends A2OperacionesInmobiliarias {

    public $direccion;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['desde_anio', 'desde_mes', 'hasta_anio', 'hasta_mes', 'fecha_ope', 'cod_propiedad', 'cod_cliente', 'locador', 
				 'conv_desocup', 'dia_venc_mensual', 'id_inmobiliaria', 'interes_dia_mora','estado','estado_renovacion','tipo_contrato','destino_contrato','cod_garante'], 'required'],
            [['desde_anio', 'desde_mes', 'hasta_anio', 'hasta_mes', 'cod_propiedad', 'cod_cliente', 'cod_garante', 'confirmada', 'conv_desocup', 'dia_venc_mensual', 
				'id_inmobiliaria', 'permite_pagos_pendientes', 'tiene_expensas', 'plazo', 'firma_representante', 'inquilino_1', 'inquilino_2', 'deposito_garantia', 
				'deposito_cuotas', 'excento', 'honorarios', 'contrato_firmado','locador_1','locador_2'], 'integer'],
            [['direccion','fecha_ope', 'fecha_desde', 'fecha_hasta', 'fecha_confirmacion', 'fechatimestamp','fecha_firma_contrato','fecha_firma_convenio','expensas','nota','ultimo_contacto'], 'safe'],
            [['observaciones', 'tipo_contrato','nota'], 'string'],
            [['interes_dia_mora', 'deposito_monto', 'deposito_contrato_monto', 'excento_monto', 'excento_cuotas'], 'number'],
            [['nro_ope'], 'string', 'max' => 256],
            [['editor'], 'string', 'max' => 250],
            [['dia_venc_mensual'],'number','min'=>1,'max'=>31],
            [['deposito_monto','deposito_contrato_monto'],'number','min'=>0,],
            [['firma_representante'],'validar_firma_representante'],
            [['hasta_mes'],'validar_hasta'],
            [['estado'],'validar_estado_contrato'],
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
        $query = A2OperacionesInmobiliarias::find();
        $query->joinWith(['inmueble']);
        $query->where("contrato_firmado=0 AND (a2_operaciones_inmobiliarias.estado='PENDIENTE' OR 
            a2_operaciones_inmobiliarias.estado='ACTIVO' OR a2_operaciones_inmobiliarias.estado='CONFIRMADO') ");
        $dataProvider = new yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    //'titulo' => SORT_ASC,
                ]
            ],
        ]);

        $this->load($params);
        //print_r($params);
        //var_dump($this->direccion);exit;
        $query->andFilterWhere(['like', 'a2_noticias.direccion', $this->direccion]);

        return $dataProvider;
    }

}