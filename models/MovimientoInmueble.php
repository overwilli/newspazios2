<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "movimiento_inmueble".
 *
 * @property integer $id
 * @property integer $movimiento_id
 * @property integer $propiedad_id
 * @property string $fecha_hora
 * @property string $usuario
 */
class MovimientoInmueble extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movimiento_inmueble';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['movimiento_id', 'propiedad_id'], 'integer'],
            [['fecha_hora'], 'safe'],
            [['usuario'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'movimiento_id' => 'Movimiento ID',
            'propiedad_id' => 'Propiedad ID',
            'fecha_hora' => 'Fecha Hora',
            'usuario' => 'Usuario',
        ];
    }
}
