<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "config_morosidad".
 *
 * @property integer $id
 * @property integer $dias_deuda
 * @property string $mensaje
 */
class ConfigMorosidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config_morosidad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dias_deuda', 'mensaje'], 'required'],
            [['dias_deuda'], 'integer'],
            [['mensaje'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dias_deuda' => 'Dias Deuda',
            'mensaje' => 'Mensaje',
        ];
    }
}
