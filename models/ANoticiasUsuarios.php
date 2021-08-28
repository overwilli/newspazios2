<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a_noticias_usuarios".
 *
 * @property integer $id_usuario
 * @property string $nikname
 * @property string $password
 * @property string $email
 * @property integer $sitio
 * @property string $permisos
 * @property string $timestamp
 * @property integer $numero_caja
 */
class ANoticiasUsuarios extends \yii\db\ActiveRecord
{
    public $password_repeat; 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a_noticias_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nikname', 'permisos','numero_caja'], 'required'],
            [['nikname','numero_caja'], 'unique'],
            [['sitio', 'numero_caja'], 'integer'],
            [['timestamp'], 'safe'],
            [['numero_caja'], 'required'],
            [['nikname', 'password', 'permisos'], 'string', 'max' => 250],
            [['email'], 'string', 'max' => 70],
            ['password_repeat', 'required', 'on' => 'update'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"La password no coincide", 'on' => 'update' ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'nikname' => 'Nikname',
            'password' => 'Password',
            'email' => 'Email',
            'sitio' => 'Sitio',
            'permisos' => 'Permisos',
            'timestamp' => 'Timestamp',
            'numero_caja' => 'Numero Caja',
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->timestamp = date('Y-m-d H:i:s');
            if ($this->getIsNewRecord()) {                
                
                $this->password=md5($this->password);
            } 
            return true;
            
        } else {
            return false;
        }
    }
}
