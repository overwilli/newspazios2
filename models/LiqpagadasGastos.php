<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liqpagadas_gastos".
 *
 * @property integer $id
 * @property integer $liquidacionpagadas_id
 * @property string $gastos_id
 * @property string $importe
 * @property string $fecha_carga
 */
class LiqpagadasGastos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'liqpagadas_gastos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['liquidacionpagadas_id'], 'integer'],
            [['importe'], 'number'],
            [['fecha_carga'], 'safe'],
            [['gastos_id'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'liquidacionpagadas_id' => 'Liquidacionpagadas ID',
            'gastos_id' => 'Gastos ID',
            'importe' => 'Importe',
            'fecha_carga' => 'Fecha Carga',
        ];
    }
	
	public function existe_gasto($gasto_id) {

        $query = "SELECT COUNT(*)as cantidad FROM liqpagadas_gastos INNER JOIN liquidacionpagadas ON liquidacionpagadas.id=liqpagadas_gastos.liquidacionpagadas_id "
                . " WHERE (liquidacionpagadas.estado<>'Anulado' AND gastos_id=" . $gasto_id.")";
        
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

    public function existe_movimiento($movimiento_id) {

        $query = "SELECT COUNT(*)as cantidad FROM liqpagadas_gastos INNER JOIN liquidacionpagadas ON 
        liquidacionpagadas.id=liqpagadas_gastos.liquidacionpagadas_id "
                . " WHERE (liquidacionpagadas.estado<>'Anulado' AND movimientos_id=" . $movimiento_id.")";
        
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }
}
