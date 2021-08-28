<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "operaciones_movimientos".
 *
 * @property integer $id
 * @property integer $operacion_id
 * @property integer $movimiento_id
 * @property string $tipo_operacion
 */
class OperacionesMovimientos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operaciones_movimientos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operacion_id', 'movimiento_id'], 'integer'],
            [['tipo_operacion'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operacion_id' => 'Operacion ID',
            'movimiento_id' => 'Movimiento ID',
            'tipo_operacion' => 'Tipo Operacion',
        ];
    }
}
