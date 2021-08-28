<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provincias".
 *
 * @property integer $id
 * @property string $nombre
 */
class Provincias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provincias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nombre'], 'required'],
            [['id'], 'integer'],
            [['nombre'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }
}
