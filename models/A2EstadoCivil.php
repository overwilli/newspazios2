<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_estado_civil".
 *
 * @property integer $id
 * @property string $denominacion
 */
class A2EstadoCivil extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_estado_civil';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['denominacion'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'denominacion' => 'Denominacion',
        ];
    }
}
