<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auditoria_contratos".
 *
 * @property integer $id
 * @property integer $operacion_id
 * @property string $fecha_contrato
 * @property string $fecha_procesamiento
 * @property string $estado_contrato
 */
class AuditoriaContratos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auditoria_contratos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operacion_id'], 'integer'],
            [['fecha_contrato', 'fecha_procesamiento'], 'safe'],
            [['estado_contrato'], 'string'],
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
            'fecha_contrato' => 'Fecha Contrato',
            'fecha_procesamiento' => 'Fecha Procesamiento',
            'estado_contrato' => 'Estado Contrato',
        ];
    }

    public function getOperacionInmobiliaria() {
        return $this->hasOne(A2OperacionesInmobiliarias::className(), ['id_operacion_inmobiliaria' => 'operacion_id']);
    }
}
