<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "localidades".
 *
 * @property integer $id
 * @property integer $departamentos_id
 * @property string $nombre
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
            [['departamentos_id', 'nombre'], 'required'],
            [['departamentos_id'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'departamentos_id' => 'Departamentos ID',
            'nombre' => 'Nombre',
        ];
    }
}
