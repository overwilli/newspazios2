<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liqpagadas_expensas".
 *
 * @property integer $id
 * @property integer $liquidacionpagadas_id
 * @property integer $expensa_id
 * @property string $importe
 * @property string $fecha_carga
 */
class LiqpagadasExpensas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'liqpagadas_expensas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['liquidacionpagadas_id', 'expensa_id'], 'integer'],
            [['importe'], 'number'],
            [['fecha_carga'], 'safe'],
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
            'expensa_id' => 'Expensa ID',
            'importe' => 'Importe',
            'fecha_carga' => 'Fecha Carga',
        ];
    }
	
	public function existe_expensa($expensa_id) {

        $query = "SELECT COUNT(*)as cantidad FROM liqpagadas_expensas INNER JOIN liquidacionpagadas ON liquidacionpagadas.id=liqpagadas_expensas.liquidacionpagadas_id "
                . " WHERE (liquidacionpagadas.estado<>'Anulado' AND expensa_id=" . $expensa_id.")";
        
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }
}
