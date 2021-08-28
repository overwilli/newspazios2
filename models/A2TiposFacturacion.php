<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_tipos_facturacion".
 *
 * @property integer $id_facturacion
 * @property string $descripcion
 * @property string $plantilla
 * @property integer $iva
 */
class A2TiposFacturacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_tipos_facturacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion', 'plantilla', 'iva'], 'required'],
            [['iva'], 'integer'],
            [['descripcion', 'plantilla'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_facturacion' => 'Id Facturacion',
            'descripcion' => 'Descripcion',
            'plantilla' => 'Plantilla',
            'iva' => 'Iva',
        ];
    }
}
