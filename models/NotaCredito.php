<?php

namespace app\models;

use Yii;

class NotaCredito extends \yii\base\Model
{
    public $liquidacion_id;
    public $cliente_id;
	public $apellido_nombre;
    public $dni;
    public $cuil;
    public $direccion;
    public $monto;
    public $descripcion;

    public function rules()
    {
        return [
			[['liquidacion_id','cliente_id','dni','cuil','direccion','monto'], 'required'],
            [['liquidacion_id','cliente_id',], 'integer'],            
            [['monto'], 'number'],
            [['descripcion','cuil','direccion','documento_vinculado'], 'string'],
        ];
    }
	
	public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'liquidacion_id' => 'Cliente-Liquidacion',
            'dni' => 'DNI',
            'cuil' => 'CUIL',
            'direccion' => 'DIRECCION',
            'descripcion' => 'DESCRIPCION',
            'monto' => 'Importe',            
        ];
    }


}