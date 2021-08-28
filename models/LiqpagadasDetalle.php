<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liqpagadas_detalle".
 *
 * @property integer $id
 * @property integer $liquidacionpagadas_id
 * @property integer $liquidaciones_id
 * @property string $costo
 * @property string $dinero_cobrado
 * @property string $interes_por_mora
 * @property string $fecha_carga
 * @property string $usuario
 * @property string $fecha_actualizacion
 * @property string $usuario_actualizacion
 */
class LiqpagadasDetalle extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'liqpagadas_detalle';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['liquidacionpagadas_id', 'liquidaciones_id'], 'integer'],
            [['costo', 'dinero_cobrado', 'interes_por_mora'], 'number'],
            [['fecha_carga', 'fecha_actualizacion'], 'safe'],
            [['usuario', 'usuario_actualizacion'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'liquidacionpagadas_id' => 'Liquidacionpagadas ID',
            'liquidaciones_id' => 'Liquidaciones ID',
            'costo' => 'Costo',
            'dinero_cobrado' => 'Dinero Cobrado',
            'interes_por_mora' => 'Interes Por Mora',
            'fecha_carga' => 'Fecha Carga',
            'usuario' => 'Usuario',
            'fecha_actualizacion' => 'Fecha Actualizacion',
            'usuario_actualizacion' => 'Usuario Actualizacion',
        ];
    }

    public function getLiquidacion() {
        return $this->hasOne(A2Liquidaciones::className(), ['id_liquidacion' => 'liquidaciones_id']);
    }

    public function existe_liquidacion($liquidacion_id) {

        $query = "SELECT COUNT(*)as cantidad FROM liqpagadas_detalle INNER JOIN liquidacionpagadas ON liquidacionpagadas.id=liqpagadas_detalle.liquidacionpagadas_id "
                . " WHERE (liquidacionpagadas.estado<>'Anulado' AND liquidaciones_id=" . $liquidacion_id.")";
        
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

}
