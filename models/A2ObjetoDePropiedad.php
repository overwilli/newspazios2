<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_objeto_de_propiedad".
 *
 * @property integer $id_operacion
 * @property string $operacion
 */
class A2ObjetoDePropiedad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_objeto_de_propiedad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operacion'], 'required'],
            [['operacion'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_operacion' => 'Id Operacion',
            'operacion' => 'Operacion',
        ];
    }
}
