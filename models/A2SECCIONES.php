<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_secciones".
 *
 * @property integer $id_seccion
 * @property string $seccion
 * @property integer $sitio
 * @property integer $visible
 */
class A2Secciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_secciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seccion', 'sitio', 'visible'], 'required'],
            [['sitio', 'visible'], 'integer'],
            [['seccion'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_seccion' => 'Id Seccion',
            'seccion' => 'Seccion',
            'sitio' => 'Sitio',
            'visible' => 'Visible',
        ];
    }
}
