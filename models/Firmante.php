<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_firmante".
 *
 * @property integer $id
 * @property string $nombre
 * @property integer $id_cliente
 * @property integer $id_prop
 * @property integer $id_ope
 */
class Firmante extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_firmante';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_prop', 'id_ope'], 'integer'],
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
            'id_cliente' => 'Id Cliente',
            'id_prop' => 'Id Prop',
            'id_ope' => 'Id Ope',
        ];
    }
}
