<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "localidades".
 *
 * @property integer $id
 * @property integer $id_provincia
 * @property string $localidad
 */
class Localidades extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'localidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_provincia', 'localidad'], 'required'],
            [['id_provincia'], 'integer'],
            [['localidad'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_provincia' => 'Id Provincia',
            'localidad' => 'Localidad',
        ];
    }
}
