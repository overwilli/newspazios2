<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_grupos".
 *
 * @property integer $id_grupo
 * @property string $descripcion
 */
class A2Grupos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_grupos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_grupo' => 'Id Grupo',
            'descripcion' => 'Descripcion',
        ];
    }
}
