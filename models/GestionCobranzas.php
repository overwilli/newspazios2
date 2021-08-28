<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gestion_cobranzas".
 *
 * @property integer $id
 * @property string $fecha
 * @property string $hora
 * @property integer $cliente_id
 * @property integer $inmueble_id
 * @property integer $operacion_id
 * @property integer $nivel
 * @property string $observaciones
 */
class GestionCobranzas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gestion_cobranzas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['cliente_id', 'inmueble_id', 'operacion_id', 'nivel','fecha_notificacion'], 'required'],
            [['fecha', 'hora','fecha_notificacion'], 'safe'],
            [['cliente_id', 'inmueble_id', 'operacion_id', 'nivel'], 'integer'],
            [['observaciones'], 'string'],
        ];
    }
	
	public function beforeSave($insert) {
        $arreglo = explode('/', $this->fecha_notificacion);
        $this->fecha_notificacion = $arreglo[2] . "-" . $arreglo[1] . "-" . $arreglo[0];
        return TRUE;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'cliente_id' => 'Cliente',
            'inmueble_id' => 'Inmueble',
            'operacion_id' => 'Operacion',
            'nivel' => 'Nivel',
            'observaciones' => 'Observaciones',
            'fecha_notificacion'=>'Fecha de Notificación'
        ];
    }
	
	public function getCliente() {
        return $this->hasOne(A2Clientes::className(), ['id_cliente' => 'cliente_id']);
    }    
	
	public function getContrato() {
        return $this->hasOne(A2OperacionesInmobiliarias::className(), ['id_operacion_inmobiliaria' => 'operacion_id']);
    }
}
