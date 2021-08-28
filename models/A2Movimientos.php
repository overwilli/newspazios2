<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_movimientos".
 *
 * @property integer $id_movimiento
 * @property string $fecha_movimiento
 * @property string $usuario
 * @property integer $movimiento_tipo
 * @property string $monto
 * @property integer $id_liquidacion
 * @property string $observaciones
 * @property string $comprobante_fecha
 * @property string $comprobante_nro
 * @property integer $comprobante_pago_unico
 * @property integer $numero_caja
 * @property integer $caja_id
 * @property string $tipo_registro
 * @property string $estado_pago_pendiente
 */
class A2Movimientos extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'a2_movimientos';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fecha_movimiento', 'usuario', 'movimiento_tipo', 'monto', 'id_liquidacion', 'observaciones', 'numero_caja', 'caja_id', 'tipo_registro'], 'required'],
            [['fecha_movimiento', 'comprobante_fecha','data'], 'safe'],
            [['movimiento_tipo', 'id_liquidacion', 'comprobante_nro', 'comprobante_pago_unico', 'numero_caja', 'caja_id','propiedad_id','operacion_id'], 'integer'],
            [['monto','propiedad_id'], 'number'],
            [['observaciones', 'tipo_registro', 'estado_pago_pendiente'], 'string'],
            [['usuario'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_movimiento' => 'Id Movimiento',
            'fecha_movimiento' => 'Fecha Movimiento',
            'usuario' => 'Usuario',
            'movimiento_tipo' => 'Movimiento Tipo',
            'monto' => 'Monto',
            'id_liquidacion' => 'Id Liquidacion',
            'observaciones' => 'Observaciones',
            'comprobante_fecha' => 'Comprobante Fecha',
            'comprobante_nro' => 'Comprobante Nro',
            'comprobante_pago_unico' => 'Comprobante Pago Unico',
            'numero_caja' => 'Numero Caja',
            'caja_id' => 'Caja ID',
            'tipo_registro' => 'Tipo Registro',
            'estado_pago_pendiente' => 'Estado Pago Pendiente',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoMovimiento() {
        return $this->hasOne(A2MovimientoTipo::className(), ['id_tipo' => 'movimiento_tipo']);
    }

    public function existen_operacion_periodo($usuario, $id_liquidacion) {
        $query = "SELECT count(*) as cantidad FROM a2_movimientos WHERE usuario='" . $usuario . "' AND id_liquidacion=" .
                $id_liquidacion . " AND tipo_registro='pago' AND "
                . " fecha_movimiento='" . date("Y-m-d") . "' AND estado_pago_pendiente<>'anulado' AND movimiento_tipo<>9";


        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryOne();
        return $model['cantidad'];
    }

    public function obtener_total_pagado($id_liquidacion) {
        $query = "SELECT a2_movimientos.*,denominacion FROM  a2_movimientos INNER JOIN a2_movimiento_tipo ON 
        (a2_movimientos.movimiento_tipo=a2_movimiento_tipo.id_tipo) WHERE tipo_registro='pago' AND 
        estado_pago_pendiente<>'anulado' AND movimiento_tipo<> 9 AND movimiento_tipo<> 7 AND 
        id_liquidacion=" . $id_liquidacion;


        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();

        $importe_pagado = 0;
        foreach ($model as $row) {
            $importe_pagado+=$row['monto'];
        }
        return $importe_pagado;
    }

    public function obtener_registro_pagado($id_liquidacion) {
        $query = "SELECT a2_movimientos.*,denominacion FROM  a2_movimientos INNER JOIN a2_movimiento_tipo ON 
        (a2_movimientos.movimiento_tipo=a2_movimiento_tipo.id_tipo) WHERE tipo_registro='pago' AND 
        estado_pago_pendiente<>'anulado' AND movimiento_tipo<> 9 AND movimiento_tipo<> 7 AND 
        id_liquidacion=" . $id_liquidacion;


        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();

        return $model;
    }

    public function borrar_movimientos_pagos($id_liquidacion) {
        $query = "SELECT * FROM a2_movimientos WHERE tipo_registro='pago' AND estado_pago_pendiente<>'anulado' "
                . "AND movimiento_tipo<> 9 AND id_liquidacion=" . $id_liquidacion;

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        foreach ($model as $row) {
            if ($row['movimiento_tipo'] != 7) {
                //---------PARAMETROS---------                
                $obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);
                

                $obj_movimiento = new A2Movimientos();
                $obj_movimiento->fecha_movimiento = date("Y-m-d");
                $obj_movimiento->movimiento_tipo = 9;
                $obj_movimiento->monto=$row['monto'];
                $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
                $obj_movimiento->id_liquidacion = $id_liquidacion;
                $obj_movimiento->observaciones = "<b>Mov. Generado por borrado:</b><br/>" . $row['observaciones'];
                $obj_movimiento->comprobante_pago_unico = 0;
                $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
                $obj_movimiento->caja_id = $obj_caja->id_caja;
                $obj_movimiento->tipo_registro = 'pago';
                $obj_movimiento->estado_pago_pendiente = '';
                
                if($obj_movimiento->save()){
                    
                }
            }
            A2Movimientos::anular_movimiento($row['id_movimiento']);
        }
    }
    
    public function anular_movimiento($id_movimiento) {
        $query = "UPDATE a2_movimientos SET estado_pago_pendiente='anulado' WHERE id_movimiento=".
                $id_movimiento;
        $connection = Yii::$app->getDb();
        $connection->createCommand($query)->execute();
    }
    
    public function existe_movimiento_tipo_pendiente($liquidacion_id) {
        $query = "SELECT count(*) as total FROM a2_movimientos WHERE estado_pago_pendiente='pendiente' AND id_liquidacion=" . $liquidacion_id;
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryOne();
        if($model['total']>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function obtener_ingresos_gastos_por_propietario($propietario_id){        
        $query="SELECT * FROM a2_movimientos INNER JOIN inmuebles_propietarios 
        ON a2_movimientos.propiedad_id=inmuebles_propietarios.inmueble_id WHERE (movimiento_tipo=6 OR 
        movimiento_tipo=8) AND inmuebles_propietarios.propietario_id=".$propietario_id;
        

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }


}
