<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "a2_operaciones_items".
 *
 * @property integer $id_item
 * @property integer $id_operacion
 * @property integer $anio
 * @property integer $mes
 * @property integer $id_factura
 * @property string $monto
 */
class A2OperacionesItems extends \yii\db\ActiveRecord
{
    public $cantidad_meses;
    public $renovar_contrato;
    public $validar_contrato_pendiente;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_operaciones_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_operacion', 'anio', 'mes', 'id_factura', 'monto', 'estado'], 'required'],
            //[['cantidad_meses'], 'required',],
            [['mes'], 'validar_periodo',],
            [['id_operacion', 'anio', 'mes', 'id_factura'], 'integer'],
            [['monto'], 'number'],
        ];
    }

    public function validar_periodo()
    {
        if ($this->validar_contrato_pendiente) {
            $model_cambio = CambiosOperaciones::find()->where(['operacion_id' => $this->id_operacion, 'estado' => 'pendiente'])->one();
            if ($model_cambio) {
                $desde_anio = $model_cambio->desde_anio;
                $desde_mes = $model_cambio->desde_mes;
                $hasta_anio = $model_cambio->hasta_anio;
                $hasta_mes = $model_cambio->hasta_mes;
            } else {
                $this->addError('mes', utf8_encode("No se puede cargar los periodos porque no existe la renovacion del contrato cargado previamente."));
                return FALSE;
            }
        } else {
            $desde_anio = $this->operacionInmobiliaria->desde_anio;
            $desde_mes = $this->operacionInmobiliaria->desde_mes;
            $hasta_anio = $this->operacionInmobiliaria->hasta_anio;
            $hasta_mes = $this->operacionInmobiliaria->hasta_mes;
        }


        $contrato_desde = ($desde_anio * 100) + $desde_mes;
        $contrato_hasta = ($hasta_anio * 100) + $hasta_mes;

        $_errores = "";

        $primer_periodo_elegido = ($this->anio * 100) + $this->mes;
        //CHEQUEO QUE EL PRIMER PERIODO ELEGIJO SE MAYOR QUE LA FECHA DESDE DEL CONTRATO Y MENOR QUE LA FECHA HASTA DEL CONTRATO
        if ($primer_periodo_elegido < $contrato_desde || $primer_periodo_elegido > $contrato_hasta)
            //$this->addError('mes', utf8_encode("Primer per�odo definido fuera del lapso de vigencia de la operaci�n".$this->mes."/".$this->anio));
            $_errores .= "Período definido fuera del lapso de vigencia de la operación " .
                $this->mes . "/" . $this->anio . ". (" . $desde_mes . "/" . $desde_anio . " - " . $hasta_mes . "/" . $hasta_anio . ")";

        /*echo $this->mes;echo "<br/>";
        echo $this->mes+$this->cantidad_meses;echo "<br/>";*/
        echo "ingreso".$this->mes."+". $this->cantidad_meses;
        if ((($this->mes + $this->cantidad_meses) - 1) > 12) { 
            $nuevo_mes = (($this->mes + $this->cantidad_meses) - 1) - 12;
            $nuevo_anio = $this->anio + 1;
        } else {
            $nuevo_mes = (($this->mes + $this->cantidad_meses)-1); // PARA QUE PUEDA DEFINIR EL ULTIMO PERIODO DEL CONTRATO CON VIGENCIA POR UN MES
            $nuevo_anio = $this->anio;
        }

        //CHEQUE QUE EL ULTIMO PERIODO EST� DENTRO DE LA VIGENCIA
        $ultimo_periodo_elegido    = ($nuevo_anio * 100) + $nuevo_mes;

        //CHEQUEO QUE EL ULTIMO PERIODO EST� DENTRO DE LA VIGENCIA
        if ($ultimo_periodo_elegido < $contrato_desde || $ultimo_periodo_elegido > $contrato_hasta)
            $_errores .= "El último período definido está fuera del lapso de vigencia " . $nuevo_mes . '/' . $nuevo_anio . ".";
        //$this->addError('mes', utf8_encode("El ultimo per�odo definido est� fuera del lapso de vigencia ".$nuevo_mes.'/'.$nuevo_anio));

        if ($_errores) {
            $this->addError('mes', $_errores);
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_item' => 'Id Item',
            'id_operacion' => 'Id Operacion',
            'anio' => 'Año',
            'mes' => 'Mes',
            'id_factura' => 'Factura',
            'monto' => 'Monto',
        ];
    }

    public function getTipoFactura()
    {
        return $this->hasOne(A2TiposFacturacion::className(), ['id_facturacion' => 'id_factura']);
    }

    public function getOperacionInmobiliaria()
    {
        return $this->hasOne(A2OperacionesInmobiliarias::className(), ['id_operacion_inmobiliaria' => 'id_operacion']);
    }

    public function obtener_items_pendientes($operacion_id)
    {
        $query = "SELECT a2_operaciones_items.*,a2_tipos_facturacion.descripcion FROM a2_operaciones_items 
		LEFT JOIN a2_tipos_facturacion ON a2_tipos_facturacion.id_facturacion=a2_operaciones_items.id_factura WHERE 
		id_operacion={$operacion_id} AND estado='PENDIENTE' ORDER BY anio ASC, mes ASC";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
}
