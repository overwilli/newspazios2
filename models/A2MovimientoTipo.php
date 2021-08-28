<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_movimiento_tipo".
 *
 * @property integer $id_tipo
 * @property string $denominacion
 * @property integer $tipo_movimiento
 */
class A2MovimientoTipo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_movimiento_tipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['denominacion', 'tipo_movimiento'], 'required'],
            [['tipo_movimiento'], 'integer'],
            [['denominacion'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo' => 'Id Tipo',
            'denominacion' => 'Denominacion',
            'tipo_movimiento' => 'Tipo Movimiento',
        ];
    }
}
