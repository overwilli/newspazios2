<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_parametros_globales".
 *
 * @property integer $id_parametro
 * @property string $empresa
 * @property integer $ultimo_recibo_x
 * @property integer $ultimo_recibo_c
 * @property integer $ultimo_recibo_d
 * @property string $impresora_host
 * @property string $impresora_puerto
 * @property string $impresora_modelo
 * @property string $impresora_firmware
 * @property string $impresora_directorio
 * @property integer $comprobante_pago_unico
 */
class A2ParametrosGlobales extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'a2_parametros_globales';
    }
	
	public static function primaryKey()
	{
			return ['id_parametro'];
	}


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_parametro', 'empresa', 'ultimo_recibo_x', 'ultimo_recibo_c', 'ultimo_recibo_d', 'impresora_host', 'impresora_puerto', 'impresora_modelo', 'impresora_firmware', 'impresora_directorio'], 'required'],
            [['id_parametro', 'ultimo_recibo_x', 'ultimo_recibo_c', 'ultimo_recibo_d', 'comprobante_pago_unico'], 'integer'],
            [['empresa', 'impresora_host', 'impresora_puerto', 'impresora_modelo', 'impresora_firmware', 'impresora_directorio'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_parametro' => 'Id Parametro',
            'empresa' => 'Empresa',
            'ultimo_recibo_x' => 'Ultimo Recibo X',
            'ultimo_recibo_c' => 'Ultimo Recibo C',
            'ultimo_recibo_d' => 'Ultimo Recibo D',
            'impresora_host' => 'Impresora Host',
            'impresora_puerto' => 'Impresora Puerto',
            'impresora_modelo' => 'Impresora Modelo',
            'impresora_firmware' => 'Impresora Firmware',
            'impresora_directorio' => 'Impresora Directorio',
            'comprobante_pago_unico' => 'Comprobante Pago Unico',
        ];
    }

    public function get_comprobante_unico() {
        $query = "SELECT comprobante_pago_unico+1 as comprobante_unico FROM a2_parametros_globales WHERE id_parametro=1";

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryOne();
        return $model['comprobante_unico'];
    }

    public function actualizar_id_unico() {
        $query = "UPDATE a2_parametros_globales SET comprobante_pago_unico=comprobante_pago_unico+1 WHERE id_parametro=1";

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->execute();
        return $model['comprobante_unico'];
    }

    public function get_recibo_c() {
        $query = "SELECT ultimo_recibo_c+1 as ultimo_recibo_c FROM a2_parametros_globales WHERE id_parametro=1";

        $connection = Yii::$app->getDb();
        $recibo = $connection->createCommand($query)->queryOne();
        if ($recibo) {
            $query = "UPDATE a2_parametros_globales SET ultimo_recibo_c=ultimo_recibo_c+1 WHERE id_parametro=1";

            $connection = Yii::$app->getDb();
            $model = $connection->createCommand($query)->execute();
        }        
        return $recibo['ultimo_recibo_c'];
    }

    public function get_recibo_d() {
        $query = "SELECT ultimo_recibo_d+1 as ultimo_recibo_d FROM a2_parametros_globales WHERE id_parametro=1";

        $connection = Yii::$app->getDb();
        $recibo = $connection->createCommand($query)->queryOne();
        if ($recibo) {
            $query = "UPDATE a2_parametros_globales SET ultimo_recibo_d=ultimo_recibo_d+1 WHERE id_parametro=1";

            $connection = Yii::$app->getDb();
            $model = $connection->createCommand($query)->execute();
        }        
        return $recibo['ultimo_recibo_d'];
    }

}
