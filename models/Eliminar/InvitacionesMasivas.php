<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invitaciones_masivas".
 *
 * @property integer $id
 * @property string $fecha_creacion
 * @property string $fecha_envio
 * @property string $estado
 */
class InvitacionesMasivas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invitaciones_masivas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_creacion', 'fecha_envio'], 'required'],
            [['id'], 'integer'],
            [['fecha_envio'], 'validar_fecha'],
            [['fecha_creacion', 'fecha_envio'], 'safe'],
            [['estado'], 'string'],
        ];
    }
    
    public function validar_fecha() {
        if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $this->fecha_envio)) {
            $this->addError('fecha_envio', 'La fecha debe tener un formato correcto');
        }
        
    }
    
    public function beforeSave($insert) {
        $arreglo=explode('/',$this->fecha_envio);
        $this->fecha_envio=$arreglo[2]."-".$arreglo[1]."-".$arreglo[0];
        $arreglo=explode('/',$this->fecha_creacion);
        $this->fecha_creacion=$arreglo[2]."-".$arreglo[1]."-".$arreglo[0];
        return TRUE;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_envio' => 'Fecha Envio',
            'estado' => 'Estado',
        ];
    }
}
