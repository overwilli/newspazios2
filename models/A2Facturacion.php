<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_facturacion".
 *
 * @property integer $id_facturacion
 * @property string $tipo_movimiento
 * @property string $monto
 * @property string $usuario
 * @property string $fecha
 * @property string $hora
 * @property string $respuesta
 * @property integer $numero_caja
 * @property integer $id_liquidacion
 * @property string $nro_comprobante_fiscal
 * @property string $nro_cai
 * @property string $tipo_documento
 */
class A2Facturacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_facturacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo_movimiento', 'monto', 'usuario', 'fecha', 'hora', 'respuesta', 'numero_caja', 'id_liquidacion', 'nro_comprobante_fiscal', 'nro_cai', 'tipo_documento'], 'required'],
            [['monto'], 'number'],
            [['fecha', 'hora'], 'safe'],
            [['respuesta'], 'string'],
            [['numero_caja', 'id_liquidacion'], 'integer'],
            [['tipo_movimiento', 'usuario'], 'string', 'max' => 250],
            [['nro_comprobante_fiscal', 'nro_cai'], 'string', 'max' => 100],
            [['tipo_documento'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_facturacion' => 'Id Facturacion',
            'tipo_movimiento' => 'Tipo Movimiento',
            'monto' => 'Monto',
            'usuario' => 'Usuario',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'respuesta' => 'Respuesta',
            'numero_caja' => 'Numero Caja',
            'id_liquidacion' => 'Id Liquidacion',
            'nro_comprobante_fiscal' => 'Nro Comprobante Fiscal',
            'nro_cai' => 'Nro Cai',
            'tipo_documento' => 'Tipo Documento',
        ];
    }
	
	public function grabar_respuesta_impresora($resultado, $tipo_movimiento, $monto, $id_liquidacion = 0, 
		$nro_documento = "", $tipo_documento = "") {
	
		$today = date("Y-m-d");
		$hora = date("H:i:s");
		$usuario = Yii::$app->user->identity->nikname;
		if ($monto == "")
			$monto = 0;

		$nro_comprobante_fiscal = "";
		$nro_cai = "";

			
		if ($tipo_movimiento != "recibo común") {
			$respuesta = json_decode($resultado, TRUE);
			
			for ($i = 0; $i < count($respuesta); $i++) { 
				
				if ($respuesta[$i][0] == 'CloseFiscalReceipt') {
					$nro_comprobante_fiscal = $respuesta[$i][3];
					$nro_cai = $respuesta[$i][5];
				}
				
				if ($tipo_movimiento == "Nota de Crédito") {					
					if ($respuesta[$i][0] == 'CloseDNFH') {
						$nro_comprobante_fiscal = $respuesta[$i][3];
						$nro_cai = $respuesta[$i][5];
					}
				}
				
			}
			if ($tipo_movimiento == "recibo X") {
				$nro_comprobante_fiscal = $nro_documento;
			}
		} else {
			$nro_comprobante_fiscal = $nro_documento;
		}
		$query = "INSERT INTO a2_facturacion (
				tipo_movimiento,
				monto,
				usuario,
				fecha,
				hora,
				respuesta,
							id_liquidacion,
							nro_comprobante_fiscal,
							nro_cai,
							tipo_documento
				)
				VALUES(
				'$tipo_movimiento',
				$monto,
				'$usuario',
				'$today',
				'$hora',
				'$resultado',
							$id_liquidacion,
							'$nro_comprobante_fiscal',
							'$nro_cai',
							'$tipo_documento'
				)";		
        

        $connection = Yii::$app->getDb();
        $connection->createCommand($query)->execute();        
    }
}
