<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_estados_inmuebles".
 *
 * @property integer $id_estado
 * @property string $descripcion
 */
class A2EstadosInmuebles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_estados_inmuebles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_estado'], 'integer'],
            [['descripcion'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_estado' => 'Id Estado',
            'descripcion' => 'Descripcion',
        ];
    }
}
