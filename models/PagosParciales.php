<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pagos_parciales".
 *
 * @property integer $id
 * @property integer $liquidacion_id
 * @property string $monto
 * @property string $estado
 * @property string $data
 * @property integer $movimiento_id
 */
class PagosParciales extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pagos_parciales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['liquidacion_id', 'movimiento_id'], 'integer'],
            [['monto'], 'number'],
            [['estado', 'data'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'liquidacion_id' => 'Liquidacion ID',
            'monto' => 'Monto',
            'estado' => 'Estado',
            'data' => 'Data',
            'movimiento_id' => 'Movimiento ID',
        ];
    }

    public function total_pagado_by_liquidacion_id($liquidacion_id){
        $query = "SELECT SUM(monto) as total FROM pagos_parciales WHERE 
        estado='pagado' AND liquidacion_id={$liquidacion_id}";
        
        $connection = Yii::$app->getDb();
        $total = $connection->createCommand($query)->queryScalar();
        return $total;
    }
}
