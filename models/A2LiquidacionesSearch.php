<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\A2Liquidaciones;
use yii\data\SqlDataProvider;

/**
 * A2LiquidacionesSearch represents the model behind the search form about `app\models\A2Liquidaciones`.
 */
class A2LiquidacionesSearch extends A2Liquidaciones
{

    public $direccion;
    public $cliente;
    public $periodo;
    public $tipo_filtro;
    public $concepto;
    public $seccion;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_liquidacion', 'id_operacion', 'liq_anio', 'liq_mes'], 'integer'],
            [['fecha_pago', 'usuario', 'timestamp', 'estado', 'direccion', 'cliente', 'periodo', 'concepto', 'seccion'], 'safe'],
            [['monto_pagado', 'monto_intereses'], 'number'],
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
        $query = A2Liquidaciones::find();

        //$query->joinWith('operacionInmobiliaria',false, 'INNER JOIN');

        $query->joinWith(['operacionInmobiliaria' => function ($q) {
            $q->joinWith(['inmueble', 'cliente']);
            //$q->joinWith('cliente');
        }], false, 'INNER JOIN');
        if (isset($params['A2LiquidacionesSearch']['tipo_filtro']) && $params['A2LiquidacionesSearch']['tipo_filtro'] == 'sin_vencer') {
            $query->where("a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))<0");
        }

        if (isset($params['A2LiquidacionesSearch']['tipo_filtro']) && $params['A2LiquidacionesSearch']['tipo_filtro'] == 'por_vencer') {
            $query->where("a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(`a2_operaciones_inmobiliarias`.`hasta_anio`,'-',
            `a2_operaciones_inmobiliarias`.`hasta_mes`,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>=-70");
        }

        //VENCIDAS
        if (isset($params['A2LiquidacionesSearch']['tipo_filtro']) && $params['A2LiquidacionesSearch']['tipo_filtro'] == 'vencidas') {
            $query->where("a2_operaciones_inmobiliarias.estado='ACTIVO' AND (DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>0 AND 
            DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))<=30) ");
        }

        //VENCIDAS MAS DE UN MES
        if (isset($params['A2LiquidacionesSearch']['tipo_filtro']) && $params['A2LiquidacionesSearch']['tipo_filtro'] == 'vencidas_mas_mes') {
            $query->where("a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>30 ");
        }

        // add conditions that should always apply here
        if ($params['A2LiquidacionesSearch']['estado'] == 'ACTIVO') {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'liq_anio' => SORT_DESC,
                        'liq_mes' => SORT_DESC,
                    ]
                ],
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'fecha_pago' => SORT_DESC,
                    ]
                ],
            ]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->periodo)) {
            $arreglo_periodo = explode("/", $this->periodo);
            $this->liq_mes = $arreglo_periodo[0];
            $this->liq_anio = $arreglo_periodo[1];
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_liquidacion' => $this->id_liquidacion,
            'id_operacion' => $this->id_operacion,
            'liq_anio' => $this->liq_anio,
            'liq_mes' => $this->liq_mes,
            'fecha_pago' => $this->fecha_pago,
            'monto_pagado' => $this->monto_pagado,
            'monto_intereses' => $this->monto_intereses,
            'timestamp' => $this->timestamp,
        ]);


        $query->andFilterWhere(['like', 'usuario', $this->usuario])
            ->andFilterWhere(['like', 'a2_liquidaciones.estado', $this->estado])
            ->andFilterWhere(['like', 'a2_noticias.direccion', $this->direccion])
            ->andFilterWhere(['like', 'a2_clientes.nombre', $this->cliente]);


        return $dataProvider;
    }

    public function searchLiquidacionesExpensas($params)
    {
        $filtro_liq = "";
        $filtro_exp = "";
        if (
            isset($params['A2LiquidacionesSearch']['tipo_filtro']) &&
            $params['A2LiquidacionesSearch']['tipo_filtro'] == 'sin_vencer'
        ) {
            $filtro_liq = " AND  a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))<=0";

            $filtro_exp = " AND  (a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(deuda_expensas.year,'-',
            deuda_expensas.mes,'-',20))<0)";
        }

        if (
            isset($params['A2LiquidacionesSearch']['tipo_filtro']) &&
            $params['A2LiquidacionesSearch']['tipo_filtro'] == 'por_vencer'
        ) {
            $filtro_liq = " AND a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(`a2_operaciones_inmobiliarias`.`hasta_anio`,'-',
            `a2_operaciones_inmobiliarias`.`hasta_mes`,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>=-70";
        }

        if (
            isset($params['A2LiquidacionesSearch']['tipo_filtro']) &&
            $params['A2LiquidacionesSearch']['tipo_filtro'] == 'vencidas'
        ) {
            $filtro_liq = " AND  a2_operaciones_inmobiliarias.estado='ACTIVO' AND (DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>0 AND 
            DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))<=30) ";

            $filtro_exp = " AND  ((a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(deuda_expensas.year,'-',
            deuda_expensas.mes,'-',20))>0) AND DATEDIFF(NOW(),CONCAT(deuda_expensas.year,'-',
            deuda_expensas.mes,'-',20))<=30) ";
        }

        if (
            isset($params['A2LiquidacionesSearch']['tipo_filtro']) &&
            $params['A2LiquidacionesSearch']['tipo_filtro'] == 'vencidas_mas_mes'
        ) {
            $filtro_liq = " AND  a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>30";

            $filtro_exp = " AND  (a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(deuda_expensas.year,'-',
            deuda_expensas.mes,'-',20))>30)";
        }

        $query = "SELECT COUNT(*) FROM (SELECT 
        a2_liquidaciones.id_liquidacion,
        a2_liquidaciones.liq_anio,
        a2_liquidaciones.liq_mes,				
        a2_noticias.id_noticia, 				
        a2_noticias.direccion,								 				
        a2_secciones.seccion,
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,				
        a2_clientes.NOMBRE,
        0 AS monto,
        'ALQUILER' AS concepto
        
        FROM a2_liquidaciones 
        LEFT JOIN a2_operaciones_inmobiliarias ON 
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
        LEFT JOIN a2_noticias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_secciones	ON			
        a2_noticias.seccion=a2_secciones.id_seccion
        LEFT JOIN a2_objeto_de_propiedad ON
        a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
        LEFT JOIN a2_clientes ON
        a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        WHERE (a2_liquidaciones.estado='ACTIVO' OR a2_liquidaciones.estado='PREIMPRESO') AND a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND a2_secciones.id_seccion LIKE :id_seccion {$filtro_liq}

UNION 

SELECT 0 as id_liquidacion,
        deuda_expensas.mes as liq_mes,
        deuda_expensas.year as liq_anio,
        a2_noticias.id_noticia,				
        a2_noticias.direccion,						 				
        a2_secciones.seccion, 				
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,				
        a2_clientes.NOMBRE,					
        deuda_expensas.deuda as monto,
'EXPENSA' AS concepto                        
                        
        FROM a2_noticias 
        LEFT JOIN a2_secciones				
        ON a2_noticias.seccion=a2_secciones.id_seccion
        LEFT JOIN a2_objeto_de_propiedad ON
        a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
        LEFT JOIN a2_operaciones_inmobiliarias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_clientes ON
         a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                        LEFT JOIN deuda_expensas ON
         a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
                         WHERE
EXISTS (SELECT 1 FROM a2_liquidaciones WHERE id_operacion=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria AND
liq_anio=deuda_expensas.year AND liq_mes=deuda_expensas.mes) AND a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND a2_secciones.id_seccion LIKE :id_seccion
        AND deuda_expensas.deuda>0 $filtro_exp  GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,
deuda_expensas.year) as t1 WHERE concepto LIKE :concepto";



        $count = Yii::$app->db->createCommand($query, [
            ':direccion' =>
            isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
            ':cliente' =>
            isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
            ':concepto' =>
            isset($params['A2LiquidacionesSearch']['concepto']) ? '%' . $params['A2LiquidacionesSearch']['concepto'] . "%" : '%%',
            ':id_seccion' =>
            isset($params['A2LiquidacionesSearch']['seccion']) ? '%' . $params['A2LiquidacionesSearch']['seccion'] . "%" : '%%'
        ])->queryScalar();


        $query = "SELECT * FROM (SELECT 
        a2_liquidaciones.id_liquidacion,        
        a2_liquidaciones.liq_mes,		
        a2_liquidaciones.liq_anio,		
        a2_noticias.id_noticia, 				
        a2_noticias.direccion,								 				
        a2_secciones.seccion,
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,				
        a2_clientes.NOMBRE,
        0 as monto,
        'ALQUILER' AS concepto,
        a2_operaciones_inmobiliarias.estado,
        a2_operaciones_inmobiliarias.hasta_mes,
        a2_operaciones_inmobiliarias.hasta_anio,
        a2_operaciones_inmobiliarias.dia_venc_mensual
        FROM a2_liquidaciones 
        LEFT JOIN a2_operaciones_inmobiliarias ON 
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
        LEFT JOIN a2_noticias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_secciones	ON			
        a2_noticias.seccion=a2_secciones.id_seccion
        LEFT JOIN a2_objeto_de_propiedad ON
        a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
        LEFT JOIN a2_clientes ON
        a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        WHERE (a2_liquidaciones.estado='ACTIVO' OR a2_liquidaciones.estado='PREIMPRESO') AND a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND a2_secciones.id_seccion LIKE :id_seccion {$filtro_liq}

UNION 

SELECT 0 as id_liquidacion,
        deuda_expensas.mes as liq_mes,
        deuda_expensas.year as liq_anio,
        a2_noticias.id_noticia,				
        a2_noticias.direccion,						 				
        a2_secciones.seccion, 				
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,				
        a2_clientes.NOMBRE,					
        deuda_expensas.deuda as monto,
        'EXPENSA' AS concepto,
        a2_operaciones_inmobiliarias.estado,
        a2_operaciones_inmobiliarias.hasta_mes,
        a2_operaciones_inmobiliarias.hasta_anio,                        
        a2_operaciones_inmobiliarias.dia_venc_mensual                
        FROM a2_noticias 
        LEFT JOIN a2_secciones				
        ON a2_noticias.seccion=a2_secciones.id_seccion
        LEFT JOIN a2_objeto_de_propiedad ON
        a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
        LEFT JOIN a2_operaciones_inmobiliarias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_clientes ON
         a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                        LEFT JOIN deuda_expensas ON
         a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
                         WHERE
EXISTS (SELECT 1 FROM a2_liquidaciones WHERE id_operacion=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria AND
liq_anio=deuda_expensas.year AND liq_mes=deuda_expensas.mes) AND a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND a2_secciones.id_seccion LIKE :id_seccion
        AND deuda_expensas.deuda>0 $filtro_exp GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,
deuda_expensas.year) as t1 WHERE concepto LIKE :concepto ORDER BY direccion,liq_anio,liq_mes";

        $provider = new SqlDataProvider([
            'sql' => $query,
            'params' => [
                ':direccion' =>
                isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
                ':cliente' =>
                isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
                ':concepto' =>
                isset($params['A2LiquidacionesSearch']['concepto']) ? '%' . $params['A2LiquidacionesSearch']['concepto'] . "%" : '%%',
                ':id_seccion' =>
                isset($params['A2LiquidacionesSearch']['seccion']) ? '%' . $params['A2LiquidacionesSearch']['seccion'] . "%" : '%%'
            ],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $provider;
    }

    public function searchLiquidacionesExpensasPagadas($params)
    {
        $filtro_liq = "";
        $filtro_exp = "";
        if (
            isset($params['A2LiquidacionesSearch']['tipo_filtro']) &&
            $params['A2LiquidacionesSearch']['tipo_filtro'] == 'sin_vencer'
        ) {
            $filtro_liq = " AND  a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))<0";

            $filtro_exp = " AND  (a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(deuda_expensas.year,'-',
            deuda_expensas.mes,'-',20))<0)";
        }

        if (
            isset($params['A2LiquidacionesSearch']['tipo_filtro']) &&
            $params['A2LiquidacionesSearch']['tipo_filtro'] == 'por_vencer'
        ) {
            $filtro_liq = " AND a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(`a2_operaciones_inmobiliarias`.`hasta_anio`,'-',
            `a2_operaciones_inmobiliarias`.`hasta_mes`,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>=-70";
        }

        if (
            isset($params['A2LiquidacionesSearch']['tipo_filtro']) &&
            $params['A2LiquidacionesSearch']['tipo_filtro'] == 'vencidas'
        ) {
            $filtro_liq = " AND  a2_operaciones_inmobiliarias.estado='ACTIVO' AND (DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>0 AND 
            DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))<=30) ";

            $filtro_exp = " AND  ((a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(deuda_expensas.year,'-',
            deuda_expensas.mes,'-',20))>0) AND DATEDIFF(NOW(),CONCAT(deuda_expensas.year,'-',
            deuda_expensas.mes,'-',20))<=30) ";
        }

        if (
            isset($params['A2LiquidacionesSearch']['tipo_filtro']) &&
            $params['A2LiquidacionesSearch']['tipo_filtro'] == 'vencidas_mas_mes'
        ) {
            $filtro_liq = " AND  a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(a2_liquidaciones.liq_anio,'-',
            a2_liquidaciones.liq_mes,'-',`a2_operaciones_inmobiliarias`.`dia_venc_mensual`))>30";

            $filtro_exp = " AND  (a2_operaciones_inmobiliarias.estado='ACTIVO' AND DATEDIFF(NOW(),CONCAT(deuda_expensas.year,'-',
            deuda_expensas.mes,'-',20))>30)";
        }

        $query = "SELECT COUNT(*) FROM (SELECT 
        a2_liquidaciones.id_liquidacion,
        a2_liquidaciones.liq_anio,
        a2_liquidaciones.liq_mes,				
        a2_noticias.id_noticia, 				
        a2_noticias.direccion,								 				
        a2_secciones.seccion,
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,				
        a2_clientes.NOMBRE,
        0 AS monto,
        'ALQUILER' AS concepto
        
        FROM a2_liquidaciones 
        LEFT JOIN a2_operaciones_inmobiliarias ON 
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
        LEFT JOIN a2_noticias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_secciones	ON			
        a2_noticias.seccion=a2_secciones.id_seccion
        LEFT JOIN a2_objeto_de_propiedad ON
        a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
        LEFT JOIN a2_clientes ON
        a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        WHERE a2_liquidaciones.estado='PAGADO' AND a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND a2_secciones.id_seccion LIKE :id_seccion {$filtro_liq}

UNION 

SELECT 0 as id_liquidacion,
        expensas_pagadas.mes as liq_mes,
        expensas_pagadas.year as liq_anio,
        a2_noticias.id_noticia,				
        a2_noticias.direccion,						 				
        a2_secciones.seccion, 				
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,				
        a2_clientes.NOMBRE,					
        expensas_pagadas.pagado as monto,
'EXPENSA' AS concepto                        
                        
        FROM a2_noticias 
        LEFT JOIN a2_secciones				
        ON a2_noticias.seccion=a2_secciones.id_seccion
        LEFT JOIN a2_objeto_de_propiedad ON
        a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
        LEFT JOIN a2_operaciones_inmobiliarias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_clientes ON
         a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
         INNER JOIN expensas_pagadas ON
				 a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=expensas_pagadas.operacion_id
        WHERE a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND a2_secciones.id_seccion LIKE :id_seccion
        $filtro_exp  GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,expensas_pagadas.mes,expensas_pagadas.year) as t1 WHERE concepto LIKE :concepto";



        $count = Yii::$app->db->createCommand($query, [
            ':direccion' =>
            isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
            ':cliente' =>
            isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
            ':concepto' =>
            isset($params['A2LiquidacionesSearch']['concepto']) ? '%' . $params['A2LiquidacionesSearch']['concepto'] . "%" : '%%',
            ':id_seccion' =>
            isset($params['A2LiquidacionesSearch']['seccion']) ? '%' . $params['A2LiquidacionesSearch']['seccion'] . "%" : '%%'
        ])->queryScalar();


        $query = "SELECT * FROM (SELECT 
        a2_liquidaciones.id_liquidacion,        
        a2_liquidaciones.liq_mes,		
        a2_liquidaciones.liq_anio,		
        a2_noticias.id_noticia, 				
        a2_noticias.direccion,								 				
        a2_secciones.seccion,
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,				
        a2_clientes.NOMBRE,
        a2_liquidaciones.monto_pagado as monto,
        'ALQUILER' AS concepto,
        a2_operaciones_inmobiliarias.estado,
        a2_operaciones_inmobiliarias.hasta_mes,
        a2_operaciones_inmobiliarias.hasta_anio,
        a2_liquidaciones.fecha_pago
        FROM a2_liquidaciones 
        LEFT JOIN a2_operaciones_inmobiliarias ON 
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
        LEFT JOIN a2_noticias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_secciones	ON			
        a2_noticias.seccion=a2_secciones.id_seccion
        LEFT JOIN a2_objeto_de_propiedad ON
        a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
        LEFT JOIN a2_clientes ON
        a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        WHERE a2_liquidaciones.estado='PAGADO' AND a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND a2_secciones.id_seccion LIKE :id_seccion {$filtro_liq}

UNION 

SELECT 0 as id_liquidacion,

        expensas_pagadas.mes as liq_mes,
        expensas_pagadas.year as liq_anio,
        a2_noticias.id_noticia,				
        a2_noticias.direccion,						 				
        a2_secciones.seccion, 				
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,				
        a2_clientes.NOMBRE,					
        expensas_pagadas.pagado as monto,
        'EXPENSA' AS concepto,
        a2_operaciones_inmobiliarias.estado,
        a2_operaciones_inmobiliarias.hasta_mes,
        a2_operaciones_inmobiliarias.hasta_anio,                      
        '' AS fecha_pago                
        FROM a2_noticias 
        LEFT JOIN a2_secciones				
        ON a2_noticias.seccion=a2_secciones.id_seccion
        LEFT JOIN a2_objeto_de_propiedad ON
        a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
        LEFT JOIN a2_operaciones_inmobiliarias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_clientes ON
         a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
         INNER JOIN expensas_pagadas ON
				 a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=expensas_pagadas.operacion_id
        WHERE 
            a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND a2_secciones.id_seccion LIKE :id_seccion
        $filtro_exp GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,expensas_pagadas.mes,expensas_pagadas.year) as t1 WHERE concepto LIKE :concepto ORDER BY fecha_pago DESC,direccion,liq_anio,liq_mes";

        $provider = new SqlDataProvider([
            'sql' => $query,
            'params' => [
                ':direccion' =>
                isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
                ':cliente' =>
                isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
                ':concepto' =>
                isset($params['A2LiquidacionesSearch']['concepto']) ? '%' . $params['A2LiquidacionesSearch']['concepto'] . "%" : '%%',
                ':id_seccion' =>
                isset($params['A2LiquidacionesSearch']['seccion']) ? '%' . $params['A2LiquidacionesSearch']['seccion'] . "%" : '%%'
            ],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $provider;
    }

    public function expensas_search($params)
    {

        $mes_periodo = "";
        $year_periodo = "";
        if (isset($params['A2LiquidacionesSearch']['periodo']) && trim($params['A2LiquidacionesSearch']['periodo'])) {
            $arreglo_periodo = explode('/', $params['A2LiquidacionesSearch']['periodo']);
            $mes_periodo = $arreglo_periodo[0];
            $year_periodo = $arreglo_periodo[1];
        }

        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM (SELECT a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion as Direccion_inmueble,
				a2_noticias.precio,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_clientes.nombre,
				a2_clientes.direccion,
				a2_clientes.telefono,	
				deuda_expensas.deuda,
                                deuda_expensas.mes,
                                deuda_expensas.year
                                
				FROM a2_noticias 
				LEFT JOIN a2_secciones				
				ON a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
				LEFT JOIN a2_operaciones_inmobiliarias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_clientes ON
				 a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                LEFT JOIN deuda_expensas ON
				 a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
                                 WHERE
EXISTS (SELECT 1 FROM a2_liquidaciones WHERE id_operacion=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria AND
 liq_anio=deuda_expensas.year AND liq_mes=deuda_expensas.mes) AND a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND deuda_expensas.mes LIKE :mes AND deuda_expensas.year LIKE :year
				 AND deuda_expensas.deuda>0 GROUP BY 
                 a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,deuda_expensas.year 
                 ORDER BY a2_noticias.direccion ASC) AS T1', [
            ':direccion' =>
            isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
            ':cliente' =>
            isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
            ':mes' =>
            isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $mes_periodo . "%" : '%%',
            ':year' =>
            isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $year_periodo . "%" : '%%'
        ])->queryScalar();

        $provider = new SqlDataProvider([
            'sql' => 'SELECT a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion as Direccion_inmueble,
				a2_noticias.precio,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_clientes.nombre,
				a2_clientes.direccion,
				a2_clientes.telefono,	
				deuda_expensas.deuda,
                                deuda_expensas.mes,
                                deuda_expensas.year
                                
				FROM a2_noticias 
				LEFT JOIN a2_secciones				
				ON a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
				LEFT JOIN a2_operaciones_inmobiliarias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_clientes ON
				 a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                LEFT JOIN deuda_expensas ON
				 a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
                                 WHERE
EXISTS (SELECT 1 FROM a2_liquidaciones WHERE id_operacion=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria AND
 liq_anio=deuda_expensas.year AND liq_mes=deuda_expensas.mes) AND a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND deuda_expensas.mes LIKE :mes AND deuda_expensas.year LIKE :year
				AND deuda_expensas.deuda>0 GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,deuda_expensas.year ORDER BY a2_noticias.direccion ASC',
            'params' => [
                ':direccion' =>
                isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
                ':cliente' =>
                isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
                ':mes' =>
                isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $mes_periodo . "%" : '%%',
                ':year' =>
                isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $year_periodo . "%" : '%%'
            ],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
            //                    'sort' => [
            //                        'attributes' => [
            //                            'title',
            //                            'view_count',
            //                            'created_at',
            //                        ],
            //                    ],
        ]);

        return $provider;
    }

    public function expensas_pagadas_search($params)
    {
        $mes_periodo = "";
        $year_periodo = "";
        if (isset($params['A2LiquidacionesSearch']['periodo']) && trim($params['A2LiquidacionesSearch']['periodo'])) {
            $arreglo_periodo = explode('/', $params['A2LiquidacionesSearch']['periodo']);
            $mes_periodo = $arreglo_periodo[0];
            $year_periodo = $arreglo_periodo[1];
        }

        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM (SELECT a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion as Direccion_inmueble,
				a2_noticias.precio,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_clientes.nombre,
				a2_clientes.direccion,
				a2_clientes.telefono,	
				expensas_pagadas.pagado,
                                expensas_pagadas.mes,
                                expensas_pagadas.year
                                
				FROM a2_noticias 
				LEFT JOIN a2_secciones				
				ON a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
				LEFT JOIN a2_operaciones_inmobiliarias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_clientes ON
				 a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                INNER JOIN expensas_pagadas ON
				 a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=expensas_pagadas.operacion_id
                 WHERE a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND expensas_pagadas.mes LIKE :mes AND expensas_pagadas.year LIKE :year
				 GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,expensas_pagadas.mes, 
                 expensas_pagadas.year ORDER BY a2_noticias.direccion,expensas_pagadas.mes,
                 expensas_pagadas.year) AS T1', [
            ':direccion' =>
            isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
            ':cliente' =>
            isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
            ':mes' =>
            isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $mes_periodo . "%" : '%%',
            ':year' =>
            isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $year_periodo . "%" : '%%'
        ])->queryScalar();

        $provider = new SqlDataProvider([
            'sql' => 'SELECT a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion as Direccion_inmueble,
				a2_noticias.precio,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_clientes.nombre,
				a2_clientes.direccion,
				a2_clientes.telefono,	
				expensas_pagadas.pagado,
                                expensas_pagadas.mes,
                                expensas_pagadas.year
                                
				FROM a2_noticias 
				LEFT JOIN a2_secciones				
				ON a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
				LEFT JOIN a2_operaciones_inmobiliarias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_clientes ON
				 a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                INNER JOIN expensas_pagadas ON
				 a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=expensas_pagadas.operacion_id
                WHERE a2_noticias.direccion LIKE :direccion 
                AND a2_clientes.nombre LIKE :cliente AND expensas_pagadas.mes LIKE :mes AND expensas_pagadas.year LIKE :year
				GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,expensas_pagadas.mes,expensas_pagadas.year ORDER BY a2_noticias.direccion,expensas_pagadas.mes,expensas_pagadas.year',
            'params' => [
                ':direccion' =>
                isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
                ':cliente' =>
                isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
                ':mes' =>
                isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $mes_periodo . "%" : '%%',
                ':year' =>
                isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $year_periodo . "%" : '%%'
            ],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
            //                    'sort' => [
            //                        'attributes' => [
            //                            'title',
            //                            'view_count',
            //                            'created_at',
            //                        ],
            //                    ],
        ]);

        return $provider;
    }


    /*
    Morosos al día de la fecha, liquidaciones y expensas
    */
    public function morosos_search($params)
    {
        $mes_periodo = "";
        $year_periodo = "";
        if (isset($params['A2LiquidacionesSearch']['periodo']) && trim($params['A2LiquidacionesSearch']['periodo'])) {
            $arreglo_periodo = explode('/', $params['A2LiquidacionesSearch']['periodo']);
            $mes_periodo = $arreglo_periodo[0];
            $year_periodo = $arreglo_periodo[1];
        }

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM (SELECT * FROM (SELECT t1.id_liquidacion,t1.id_operacion,liq_anio,liq_mes,
        a2_noticias.direccion,a2_clientes.NOMBRE,'ALQUILER' AS tipo,0 as deuda,a2_noticias.id_noticia FROM(
				SELECT * FROM a2_liquidaciones WHERE a2_liquidaciones.fecha_pago='0000-00-00 00:00:00'
        UNION
        SELECT DISTINCT a2_liquidaciones.* FROM a2_liquidaciones LEFT JOIN  a2_movimientos ON a2_liquidaciones.id_liquidacion=a2_movimientos.id_liquidacion 
        WHERE (tipo_registro='pago_pendiente'AND estado_pago_pendiente='pendiente'))
        AS t1 
        LEFT JOIN a2_operaciones_inmobiliarias ON 
                        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=t1.id_operacion
        LEFT JOIN a2_noticias ON
                        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_clientes ON
                        a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        WHERE a2_operaciones_inmobiliarias.estado='ACTIVO' 
        
        UNION 
        
        SELECT 0,deuda_expensas.operacion_id,deuda_expensas.`year`,deuda_expensas.`mes`,a2_noticias.direccion,a2_clientes.NOMBRE,
        'EXPENSAS' AS tipo, deuda_expensas.deuda,a2_noticias.id_noticia FROM a2_noticias LEFT JOIN a2_operaciones_inmobiliarias ON
                        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                        LEFT JOIN a2_clientes ON
                         a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                        LEFT JOIN deuda_expensas ON
                         a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
        WHERE fecha_ope<>'' AND deuda_expensas.deuda>0 GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,deuda_expensas.year 
        ) AS T2 GROUP BY T2.id_operacion,T2.tipo ORDER BY direccion,liq_anio,liq_mes) as T3", [
            /*':direccion' =>
            isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
            ':cliente' =>
            isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
            ':mes' =>
            isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $mes_periodo . "%" : '%%',
            ':year' =>
            isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $year_periodo . "%" : '%%'*/
        ])->queryScalar();

        $provider = new SqlDataProvider([
            'sql' => "SELECT * FROM (SELECT t1.id_liquidacion,t1.id_operacion,liq_anio,liq_mes,
            a2_noticias.direccion,a2_clientes.NOMBRE,'ALQUILER' AS tipo,0 as deuda,a2_noticias.id_noticia FROM(
                    SELECT * FROM a2_liquidaciones WHERE a2_liquidaciones.fecha_pago='0000-00-00 00:00:00'
            UNION
            SELECT DISTINCT a2_liquidaciones.* FROM a2_liquidaciones LEFT JOIN  a2_movimientos ON a2_liquidaciones.id_liquidacion=a2_movimientos.id_liquidacion 
            WHERE (tipo_registro='pago_pendiente'AND estado_pago_pendiente='pendiente'))
            AS t1 
            LEFT JOIN a2_operaciones_inmobiliarias ON 
                            a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=t1.id_operacion
            LEFT JOIN a2_noticias ON
                            a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
            LEFT JOIN a2_clientes ON
                            a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
            WHERE a2_operaciones_inmobiliarias.estado='ACTIVO' 
            
            UNION 
            
            SELECT 0,deuda_expensas.operacion_id,deuda_expensas.`year`,deuda_expensas.`mes`,a2_noticias.direccion,a2_clientes.NOMBRE,
            'EXPENSAS' AS tipo, deuda_expensas.deuda,a2_noticias.id_noticia FROM a2_noticias LEFT JOIN a2_operaciones_inmobiliarias ON
                            a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                            LEFT JOIN a2_clientes ON
                             a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                            LEFT JOIN deuda_expensas ON
                             a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
            WHERE fecha_ope<>'' AND deuda_expensas.deuda>0 GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,deuda_expensas.year 
            ) AS T2 GROUP BY T2.id_operacion,T2.tipo ORDER BY direccion,liq_anio,liq_mes",
            'params' => [
                /*':direccion' =>
                isset($params['A2LiquidacionesSearch']['direccion']) ? '%' . $params['A2LiquidacionesSearch']['direccion'] . "%" : '%%',
                ':cliente' =>
                isset($params['A2LiquidacionesSearch']['cliente']) ? '%' . $params['A2LiquidacionesSearch']['cliente'] . "%" : '%%',
                ':mes' =>
                isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $mes_periodo . "%" : '%%',
                ':year' =>
                isset($params['A2LiquidacionesSearch']['periodo']) ? '%' . $year_periodo . "%" : '%%'*/
            ],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $provider;
    }

}
