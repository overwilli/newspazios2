<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clientes_update".
 *
 * @property string $nombre
 * @property integer $dni
 * @property string $direccion
 * @property string $barrio
 * @property integer $id_localidad
 * @property string $empresa
 * @property string $empresa_direccion
 * @property string $empresa_puesto
 * @property string $empresa_antiguedad
 * @property string $telefono_pre
 * @property string $telefono
 * @property string $telefono_alternativo_pre
 * @property string $telefono_alternativo
 * @property string $celular_pre
 * @property string $celular
 * @property string $celular_alternativo_pre
 * @property string $celular_alternativo
 * @property string $email
 * @property string $email_alternativo
 * @property string $password
 * @property string $password_temp
 * @property string $hash
 * @property integer $estado
 * @property string $cumpleanios
 * @property string $fecha_actualizacion
 * @property string $updated_date
 */
class ClientesUpdate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clientes_update';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'dni'], 'required'],
            [['dni', 'id_localidad', 'estado'], 'integer'],
            [['cumpleanios', 'fecha_actualizacion', 'updated_date'], 'safe'],
            [['nombre', 'barrio', 'empresa', 'empresa_antiguedad', 'telefono', 'telefono_alternativo', 'celular', 'celular_alternativo', 'email', 'email_alternativo', 'password', 'password_temp'], 'string', 'max' => 50],
            [['direccion', 'empresa_direccion', 'empresa_puesto', 'hash'], 'string', 'max' => 100],
            [['telefono_pre', 'telefono_alternativo_pre', 'celular_pre', 'celular_alternativo_pre'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nombre' => 'Nombre',
            'dni' => 'Dni',
            'direccion' => 'Direccion',
            'barrio' => 'Barrio',
            'id_localidad' => 'Id Localidad',
            'empresa' => 'Empresa',
            'empresa_direccion' => 'Empresa Direccion',
            'empresa_puesto' => 'Empresa Puesto',
            'empresa_antiguedad' => 'Empresa Antiguedad',
            'telefono_pre' => 'Telefono Pre',
            'telefono' => 'Telefono',
            'telefono_alternativo_pre' => 'Telefono Alternativo Pre',
            'telefono_alternativo' => 'Telefono Alternativo',
            'celular_pre' => 'Celular Pre',
            'celular' => 'Celular',
            'celular_alternativo_pre' => 'Celular Alternativo Pre',
            'celular_alternativo' => 'Celular Alternativo',
            'email' => 'Email',
            'email_alternativo' => 'Email Alternativo',
            'password' => 'Password',
            'password_temp' => 'Password Temp',
            'hash' => 'Hash',
            'estado' => 'Estado',
            'cumpleanios' => 'Cumpleanios',
            'fecha_actualizacion' => 'Fecha Actualizacion',
            'updated_date' => 'Updated Date',
        ];
    }
}
