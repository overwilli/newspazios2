<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_inmobiliarias".
 *
 * @property integer $id_inmobiliaria
 * @property string $nombre_inmobiliaria
 */
class A2Inmobiliarias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_inmobiliarias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_inmobiliaria'], 'required'],
            [['nombre_inmobiliaria'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_inmobiliaria' => 'Id Inmobiliaria',
            'nombre_inmobiliaria' => 'Nombre Inmobiliaria',
        ];
    }
}
