<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "plantillas".
 *
 * @property integer $id
 * @property string $titulo
 * @property string $texto
 * @property string $estado
 */
class Plantillas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plantillas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['texto', 'estado'], 'string'],
            [['titulo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'texto' => 'Texto',
            'estado' => 'Estado',
        ];
    }
}
