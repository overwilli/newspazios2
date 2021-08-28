<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_liquidaciones".
 *
 * @property integer $id_liquidacion
 * @property integer $id_operacion
 * @property integer $liq_anio
 * @property integer $liq_mes
 * @property string $fecha_pago
 * @property string $monto_pagado
 * @property string $monto_intereses
 * @property string $usuario
 * @property string $timestamp
 * @property string $estado
 */
class A2Liquidaciones extends \yii\db\ActiveRecord {

    public $mes;
    public $anio;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'a2_liquidaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_operacion', 'liq_anio', 'liq_mes',], 'required', 'on' => 'crear_liq_periodos'],
            [['id_operacion', 'liq_anio', 'liq_mes'], 'integer'],
            [['fecha_pago', 'timestamp', 'anio', 'mes',], 'safe'],
            [['monto_pagado', 'monto_intereses'], 'number'],
            [['estado',], 'string'],
            [['usuario'], 'string', 'max' => 250],
            [['mes'], 'existen_operaciones'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_liquidacion' => 'Id Liquidacion',
            'id_operacion' => 'Contrato',
            'liq_anio' => 'Liq Anio',
            'liq_mes' => 'Liq Mes',
            'fecha_pago' => 'Fecha Pago',
            'monto_pagado' => 'Monto Pagado',
            'monto_intereses' => 'Monto Intereses',
            'usuario' => 'Usuario',
            'timestamp' => 'Timestamp',
            'estado' => 'Estado',
            'anio' => 'Años',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperacionInmobiliaria() {
        return $this->hasOne(A2OperacionesInmobiliarias::className(), ['id_operacion_inmobiliaria' => 'id_operacion']);
    }

    public function existen_operaciones() {
        $model_ope=A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' =>$this->id_operacion])->one();
        if($model_ope && $model_ope->estado=='PENDIENTE'){
            $model = A2OperacionesInmobiliarias::existen_operacion_periodo($this->mes, $this->anio, $this->id_operacion,"PENDIENTE");
        }else{
            $model = A2OperacionesInmobiliarias::existen_operacion_periodo($this->mes, $this->anio, $this->id_operacion);
        }
        if ($model) {
            $existe_uno = 0;
            foreach ($model as $row) {
                $resultado = A2Liquidaciones::find()->where(['id_operacion' => $row['id_operacion_inmobiliaria'],
                            'liq_anio' => $this->anio, 'liq_mes' => $this->mes])->all();
                if (!$resultado) {
                    $existe_uno++;
                }
            }
            if ($existe_uno == 0) {
                $this->addError('mes', "Ya existen las liquidaciones para el período {$this->mes}/{$this->anio}.");
            }
        } else {
            $this->addError('mes', "No hay operaciones para cobrar en este período:{$this->mes}/{$this->anio}.");
        }
    }

    public function crear_liquidaciones_periodo() {

        $model_ope=A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' =>$this->id_operacion])->one();
        if($model_ope && $model_ope->estado=='PENDIENTE'){
            $model = A2OperacionesInmobiliarias::existen_operacion_periodo($this->mes, $this->anio, $this->id_operacion,"PENDIENTE");
        }else{
            $model = A2OperacionesInmobiliarias::existen_operacion_periodo($this->mes, $this->anio, $this->id_operacion);
        }

        foreach ($model as $row) {
            $resultado = A2Liquidaciones::find()->where(['id_operacion' => $row['id_operacion_inmobiliaria'],
                        'liq_anio' => $this->anio, 'liq_mes' => $this->mes,'estado'=>'ACTIVO'])->all();
            if (!$resultado) {
                $model_liq = new A2Liquidaciones();
                $model_liq->id_operacion = $row['id_operacion_inmobiliaria'];
                $model_liq->liq_anio = $this->anio;
                $model_liq->liq_mes = $this->mes;
                $model_liq->scenario = 'crear_liq_periodos';
                $model_liq->estado = 'ACTIVO';
                if (!$model_liq->save()) {
                    
                }
            }
        }
    }

    public function ObtenerPeriodosLiquidados() {
        $query = "SELECT CONCAT(CONCAT(liq_mes,'/'),liq_anio) as periodos FROM a2_liquidaciones GROUP BY liq_anio,liq_mes ORDER BY liq_anio DESC, liq_mes DESC";

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function Calcular_Monto_Periodo($id_operacion, $anio, $mes, $dias_atraso = 0) {
        $model_operacion = A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => $id_operacion])->one();
        $model_operaciones_items = $model_operacion->getOperacionesItems()->where(['anio' => $anio, 'mes' => $mes])->all();
        $monto = 0;
        $arreglo_factura = null;
        foreach ($model_operaciones_items as $row) {
            $monto_iva = 0;
            if ($row->tipoFactura->iva == 1) {
                if ($row->monto >= 1501) {
                    $monto_iva = $row->monto + ($row->monto * 21) / 100;
                    $monto += $monto_iva;
                } else {
                    $monto+=$row->monto;
                }
            } else {
                $monto+=$row->monto;
            }
            $arreglo_factura[] = ['descripcion' => $row->tipoFactura->descripcion, 'plantilla' => $row->tipoFactura->plantilla, 'monto' => $row->monto,
                'monto_iva' => $monto_iva];
        }

                           
        $arreglo = null;
        
        $arreglo['facturas'] = $arreglo_factura;
        if ($dias_atraso > 0) {

            $interes_por_dia = $model_operacion->interes_dia_mora;
            $interes_por_mora = (($dias_atraso * $interes_por_dia) * $monto) / 100;
            //$interes_por_mora=(($dias_atraso*INTERES_DIA_MORA)*$monto_a_facturar)/100;
            //$interes_por_mora = number_format($interes_por_mora, CANT_DIGITOS, '.', '');
            $monto = $monto + $interes_por_mora;

            $arreglo['monto'] = $monto;
            $arreglo['interes'] = $interes_por_mora;
            $arreglo['interes_por_dia'] = $interes_por_dia;
            return $arreglo;
        } else {

            $arreglo['monto'] = $monto;
            $arreglo['interes'] = 0;
            $arreglo['interes_por_dia'] = 0;
            return $arreglo;
        }
    }

    public function calcular_pago_parcial($id_liquidacion){
        $model_pago_parcial=\app\models\PagosParciales::find()->where(['liquidacion_id'=>$id_liquidacion,
                            'estado'=>'pagado'])->all();
        $monto = 0;
        
        foreach ($model_pago_parcial as $row) {
            $monto+=$row['monto'];
        }
        return $monto;
    }

    public function obtener_dias_mora($id_liquidacion, $fecha_deposito = "",$expensas=FALSE) {
        $model_liq = A2Liquidaciones::find()->where(['id_liquidacion' => $id_liquidacion])->one();
		if($expensas){
			$zz_fecha_venc_liquidacion = $model_liq->liq_anio . '-' . $model_liq->liq_mes . '-' . $model_liq->operacionInmobiliaria->dia_venc_mensual;
			$zz_fecha_venc_liquidacion = strtotime ( '+5 day' , strtotime ( $zz_fecha_venc_liquidacion ) ) ;
			$zz_fecha_venc_liquidacion = date ( 'Y-m-d' , $zz_fecha_venc_liquidacion );
		}else{
			$zz_fecha_venc_liquidacion = $model_liq->liq_anio . '-' . $model_liq->liq_mes . '-' . ($model_liq->operacionInmobiliaria->dia_venc_mensual);
		}
        if (empty($fecha_deposito)) {
            $zz_fecha_actual = date("Y") . '-' . date("m") . '-' . date("d");
        } else {
            $zz_fecha_actual = $fecha_deposito;
        }
        $zz_dias_mora = (strtotime($zz_fecha_actual) - strtotime($zz_fecha_venc_liquidacion)) / 86400;

        $dias_atraso = round($zz_dias_mora);
        return $dias_atraso;
    }

    //------------EXPENSAS---------------

    public function ObtenerPeriodosDeudaExpensa($id_operacion) {
        $query = "SELECT DISTINCT mes,operaciones_expensas.`year` FROM operaciones_expensas WHERE estado='impago' AND operacion_id=" . $id_operacion;

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function detalle_deuda_expensa($operacion_id, $mes, $year) {
        $query = "SELECT operaciones_expensas.*,tipo_expensas.descripcion FROM operaciones_expensas INNER JOIN tipo_expensas ON 
            (operaciones_expensas.tipo_expensas_id=tipo_expensas.id) WHERE operaciones_expensas.operacion_id=" . $operacion_id .
                " AND mes=" . $mes . " AND year=" . $year . " AND estado='impago'  ORDER BY
                YEAR DESC,MES DESC,descripcion ASC";

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function obtener_expensas_pagadas($cliente, $inmueble, $mes, $year) {
        $query = "SELECT a2_movimientos.*,denominacion FROM  a2_movimientos INNER JOIN a2_movimiento_tipo ON "
                . "(a2_movimientos.movimiento_tipo=a2_movimiento_tipo.id_tipo) WHERE tipo_registro='pago' AND "
                . " (estado_pago_pendiente IS NULL OR estado_pago_pendiente='') "
                . " AND movimiento_tipo=11 AND (observaciones LIKE '%" . $cliente . "%' AND observaciones LIKE '%" . $inmueble . "%' "
                . " AND observaciones LIKE '%Periodo: {$mes}/{$year}%')";

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function actualizar_deuda_expensa($operacion_id, $mes, $year, $id_unico) {
        $query = "UPDATE operaciones_expensas SET estado='pagado',comprobante_id=" . $id_unico . " WHERE operaciones_expensas.operacion_id=" . $operacion_id .
                " AND mes=" . $mes . " AND year=" . $year;

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->execute();
        return $model;
    }

    public function actualizar_expensa_impago($operacion_id, $mes, $year) {
        $query = "UPDATE operaciones_expensas SET estado='impago',comprobante_id=NULL WHERE operaciones_expensas.operacion_id=" . $operacion_id .
                " AND mes=" . $mes . " AND year=" . $year;

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->execute();
        return $model;
    }

    public function borrar_pago_liquidacion($liquidacion_id) {
        $query = "UPDATE a2_liquidaciones SET fecha_pago='0000-00-00 00:00:00',monto_pagado=0,monto_intereses=0,usuario='',
        timestamp='0000-00-00 00:00:00',estado='ACTIVO' WHERE id_liquidacion={$liquidacion_id}";

        $connection = Yii::$app->getDb();
        $connection->createCommand($query)->execute();        
    }
    
    public function obtener_liquidacion_by_propietario($propietario_id,$fecha_pago_liq=""){
        $condicones = " AND inmuebles_propietarios.propietario_id=" . $propietario_id;
        
        if (!empty($fecha_pago_liq)) {
            $condicones.=" AND DATE(a2_liquidaciones.fecha_pago)<=DATE('" . $fecha_pago_liq . "')";
        }
        
        $query="SELECT * FROM (SELECT 
				a2_liquidaciones.id_liquidacion,
				a2_liquidaciones.liq_anio,
				a2_liquidaciones.liq_mes,
				a2_liquidaciones.fecha_pago,
				a2_liquidaciones.monto_pagado,
				a2_liquidaciones.monto_intereses,
				a2_liquidaciones.usuario,
				a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion,
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
				a2_operaciones_inmobiliarias.dia_venc_mensual,	
				a2_clientes.NOMBRE as nombrecliente
				
				FROM a2_liquidaciones 
				LEFT JOIN a2_operaciones_inmobiliarias ON 
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
				LEFT JOIN a2_noticias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                                LEFT JOIN inmuebles_propietarios ON a2_noticias.id_noticia=inmuebles_propietarios.inmueble_id
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				LEFT JOIN a2_clientes ON
				a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
				WHERE 1	" . $condicones . " AND a2_liquidaciones.fecha_pago<>'0000-00-00 00:00:00'"
                . ") as t1 ORDER BY t1.fecha_pago DESC";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
    
     public function tiene_movimiento_tipo_by_liquidacion_id($id_liquidacion,$tipo_movimiento=null) {
        $query = "SELECT count(*) as cantidad FROM  a2_movimientos INNER JOIN a2_movimiento_tipo ON "
                . "(a2_movimientos.movimiento_tipo=a2_movimiento_tipo.id_tipo) WHERE tipo_registro='pago' AND "
                . "(estado_pago_pendiente IS NULL OR estado_pago_pendiente='')AND movimiento_tipo<> 9 AND movimiento_tipo<> 7 AND id_liquidacion=" . $id_liquidacion;
        if($tipo_movimiento!=null){
            $query.=" AND movimiento_tipo={$tipo_movimiento}";
        }        
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;        
    }
    
    public function obtener_importe_pagado_by_liq($id_liquidacion) {
        $query = "SELECT a2_movimientos.*,denominacion FROM  a2_movimientos INNER JOIN a2_movimiento_tipo ON "
                . "(a2_movimientos.movimiento_tipo=a2_movimiento_tipo.id_tipo) WHERE tipo_registro='pago' AND "
                . "(estado_pago_pendiente IS NULL OR estado_pago_pendiente='')AND movimiento_tipo<> 9 AND movimiento_tipo<> 7 AND id_liquidacion=" . $id_liquidacion;
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        $importe_cobrado = 0;
        $nota_credito = 0;
        $interes = 0;
        foreach($model as $fila) {
            //if ($fila['movimiento_tipo'] != 7 && $fila['movimiento_tipo'] != 9) {
            $importe_cobrado+=$fila['monto'];
            
        }
        return $importe_cobrado;
    }
    
    public function obtener_mora_pagada($id_liquidacion) {
        $query = "SELECT a2_movimientos.monto FROM  a2_movimientos WHERE id_liquidacion=$id_liquidacion AND movimiento_tipo=7 AND estado_pago_pendiente<>'anulado'";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

}