<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clientes_hash".
 *
 * @property integer $id
 * @property string $dni
 * @property string $hash
 * @property string $fecha_create
 * @property string $fecha_update
 */
class ClientesHash extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clientes_hash';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_create', 'fecha_update'], 'safe'],
            [['dni'], 'string', 'max' => 20],
            [['hash'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dni' => 'Dni',
            'hash' => 'Hash',
            'fecha_create' => 'Fecha Create',
            'fecha_update' => 'Fecha Update',
        ];
    }
}
