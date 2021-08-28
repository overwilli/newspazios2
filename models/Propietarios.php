<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "propietarios".
 *
 * @property integer $id
 * @property string $apellido
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 * @property string $observacion
 * @property integer $estado
 * @property string $cuit
 */
class Propietarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'propietarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apellido', 'nombre', 'direccion',], 'required'],
            [['observacion'], 'string'],
            [['estado'], 'integer'],
            [['apellido', 'nombre'], 'string', 'max' => 100],
            [['direccion','localidad','provincia'], 'string', 'max' => 255],
            [['telefono'], 'string', 'max' => 45],
            [['cuit'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'direccion' => 'Direccion',
            'telefono' => 'Telefono',
            'observacion' => 'Observacion',
            'estado' => 'Estado',
            'cuit' => 'Cuit',
        ];
    }
	
	public function getApellidoNombre(){
		return $this->apellido." ".$this->nombre;
    }
    
    public function obtener_cuit(){
		try{
			$parte1=substr($this->cuit,0,2);
			$parte2=substr($this->cuit,2,8);
			$parte3=substr($this->cuit,10,1);
			return $parte1."-".$parte2."-".$parte3;
		}catch(Exception $ex){
			return $this->cuit;
		}
	}
}
