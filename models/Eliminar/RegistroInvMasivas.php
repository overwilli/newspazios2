<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_inv_masivas".
 *
 * @property string $dni
 * @property string $fecha_create
 * @property string $fecha_update
 * @property integer $invitacion_masiva_id
 */
class RegistroInvMasivas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registro_inv_masivas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dni'], 'required'],
            [['fecha_create', 'fecha_update'], 'safe'],
            [['invitacion_masiva_id'], 'integer'],
            [['dni'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dni' => 'Dni',
            'fecha_create' => 'Fecha Create',
            'fecha_update' => 'Fecha Update',
            'invitacion_masiva_id' => 'Invitacion Masiva ID',
        ];
    }
}
