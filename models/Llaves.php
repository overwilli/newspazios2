<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "llaves".
 *
 * @property integer $id
 * @property integer $inmueble_id
 * @property string $numero_llave
 * @property integer $inmobiliaria_id
 * @property string $fecha_solicitud
 * @property string $tipo_solicitud
 * @property string $persona
 * @property string $observacion
 */
class Llaves extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'llaves';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inmueble_id', 'inmobiliaria_id','numero_llave','fecha_solicitud','persona','tipo_solicitud'], 'required'],
            [['inmueble_id', 'inmobiliaria_id'], 'integer'],
            [['fecha_solicitud'], 'safe'],
            [['tipo_solicitud', 'observacion'], 'string'],
            [['numero_llave'], 'string', 'max' => 45],
            [['persona'], 'string', 'max' => 255],
        ];
    }
    
    /*public function beforeSave($insert) {
        $arreglo = explode('/', $this->fecha_solicitud);
        $this->fecha_solicitud = $arreglo[2] . "-" . $arreglo[1] . "-" . $arreglo[0];
        return TRUE;
    }*/

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inmueble_id' => 'Inmueble',
            'numero_llave' => 'Numero de Llave',
            'inmobiliaria_id' => 'Inmobiliaria',
            'fecha_solicitud' => 'Fecha Solicitud',
            'tipo_solicitud' => 'Tipo de Solicitud',
            'persona' => 'Usuario  que prestó',
            'observacion' => 'Observacion',
            'fecha_devolucion' => 'Fecha de Devolución',
            'usuario_devolucion' => 'Usuario que Recibio',
        ];
    }
    
    public function getInmueble() {
        return $this->hasOne(A2Noticias::className(), ['id_noticia' => 'inmueble_id']);
    }
    
    public function getInmobiliaras() {
        return $this->hasOne(A2Inmobiliarias::className(), ['id_inmobiliaria' => 'inmobiliaria_id']);
    }
}
