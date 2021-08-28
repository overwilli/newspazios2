<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contratos_documentos".
 *
 * @property integer $id
 * @property integer $operacion_inmobiliaria_id
 * @property string $texto
 * @property string $estado
 * @property string $usuario_create
 * @property string $time_create
 * @property string $usuario_update
 * @property string $time_update
 * @property integer $plantilla_id
 */
class ContratosDocumentos extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'contratos_documentos';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['operacion_inmobiliaria_id', 'plantilla_id'], 'integer'],
            [['texto', 'estado'], 'string'],
            [['time_create', 'time_update'], 'safe'],
            [['usuario_create', 'usuario_update'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'operacion_inmobiliaria_id' => 'Operacion Inmobiliaria ID',
            'texto' => 'Texto',
            'estado' => 'Estado',
            'usuario_create' => 'Usuario Create',
            'time_create' => 'Time Create',
            'usuario_update' => 'Usuario Update',
            'time_update' => 'Time Update',
            'plantilla_id' => 'Tipo de Plantilla',
        ];
    }

    public function getOperacionInmobiliaria() {
        return $this->hasOne(A2OperacionesInmobiliarias::className(), ['id_operacion_inmobiliaria' => 'operacion_inmobiliaria_id']);
    }

    public function getPlantillas() {
        return $this->hasOne(Plantillas::className(), ['id' => 'plantilla_id']);
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->usuario_create=Yii::$app->user->identity->nikname;
            $this->time_create = date("Y-m-d H:i:s");
        } else {
            $this->usuario_update=Yii::$app->user->identity->nikname;
            $this->time_update = date("Y-m-d H:i:s");
        }

        return TRUE;
    }

}
