<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "envio_mails".
 *
 * @property integer $id
 * @property string $dni
 * @property string $email
 * @property integer $tipo
 * @property string $fecha
 * @property integer $promocion_id
 * @property string $estado
 */
class EnvioMails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'envio_mails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo', 'promocion_id'], 'integer'],
            [['fecha'], 'safe'],
            [['estado'], 'string'],
            [['dni'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dni' => 'Dni',
            'email' => 'Email',
            'tipo' => 'Tipo',
            'fecha' => 'Fecha',
            'promocion_id' => 'Promocion ID',
            'estado' => 'Estado',
        ];
    }
    
    public function enviar_mail($asunto,$email,$body_mail){
        Yii::$app->mailer->compose()
                            ->setFrom(['no-replay@midinamica.com.ar' => utf8_encode('DINÁMICA')])
                            ->setTo($email)
                            ->setSubject($asunto)
                            //->setTextBody('Plain text content')
                            ->setHtmlBody($body_mail)
                            ->send();
    }
}
