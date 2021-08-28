<?php

namespace app\models;

use Yii;
use yii\base\Exception;
/**
 * This is the model class for table "clientes".
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
class Clientes extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'clientes';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
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
    public function attributeLabels() {
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
            'cumpleanios' => utf8_encode('Cumpleaños'),
            'fecha_actualizacion' => 'Fecha Actualizacion',
            'updated_date' => 'Fecha de Actualizacion',
        ];
    }

    public function get_cliente_sin_email_masivo($email_masivo_id) {
        $query = "SELECT dni,email FROM clientes WHERE dni NOT IN(SELECT dni FROM registro_inv_masivas WHERE invitacion_masiva_id={$email_masivo_id})";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function get_cliente_sin_email_promo($promocion_id) {
        $query = "SELECT dni,email,cumpleanios,id_localidad FROM clientes WHERE dni NOT IN(SELECT dni FROM envio_mails WHERE promocion_id={$promocion_id})";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function tiene_saludo_birthday($dni, $year) {
        $query = "SELECT count(*) as cantidad FROM envio_mails WHERE dni='{$dni}' AND tipo=4 AND YEAR(fecha)={$year}";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

    public function total_clientes_web() {
        $query = "SELECT COUNT(*) as total FROM clientes ";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

    public function total_clientes_con_acceso() {
        $query = "SELECT COUNT(*) as total FROM clientes_update ; ";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

    public function clientes_autenticados() {
        $query = "SELECT DISTINCT logs_autenticacion.dni,DATE(logs_autenticacion.fecha_hora) as fecha_hora,clientes.nombre FROM logs_autenticacion INNER JOIN clientes ON 
logs_autenticacion.dni=clientes.dni WHERE DATE(fecha_hora)='" . date('Y-m-d') . "'";
        /* $query = "SELECT * FROM autenticacion_clientes WHERE fecha_hora='".date('Y-m-d')."';"; */
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function total_clientes_autenticados_en_periodo($fecha_inicio, $fecha_fin) {
        $query = "SELECT COUNT(dni) as total FROM (SELECT DISTINCT logs_autenticacion.dni FROM logs_autenticacion INNER JOIN clientes ON 
logs_autenticacion.dni=clientes.dni WHERE DATE(fecha_hora) BETWEEN '{$fecha_inicio}' AND '{$fecha_fin}') AS t1";
        /* $query = "SELECT * FROM autenticacion_clientes WHERE fecha_hora='".date('Y-m-d')."';"; */
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

    public function cantidad_clientes_autenticados_por_dia($fecha_inicio, $fecha_fin) {
        $query = "SELECT COUNT(dni) as cantidad,fecha FROM (SELECT DISTINCT logs_autenticacion.dni,DATE(logs_autenticacion.fecha_hora) as fecha FROM logs_autenticacion INNER JOIN clientes ON 
logs_autenticacion.dni=clientes.dni WHERE tipo_accion='INICIAR' AND DATE(fecha_hora) BETWEEN '{$fecha_inicio}' AND '{$fecha_fin}'
ORDER BY dni,fecha_hora)as t1
GROUP BY t1.fecha
";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    //--------------RELACIONES------------
    public function getClientesUpdate() {
        return $this->hasOne(ClientesUpdate::className(), ['dni' => 'dni']);
    }

    public function getLocalidades() {
        return $this->hasOne(Localidades::className(), ['id' => 'id_localidad']);
    }

    public function gernerar_hash($dni) {
        $model = Clientes::find()->where(['dni' => $dni])->one();
        if ($model) {
            $hash = md5(uniqid());
            $model_clientes_hash = ClientesHash::find()->where(['dni' => $dni])->one();
            if ($model_clientes_hash) {
                $query = "UPDATE clientes_hash SET hash='{$hash}',fecha_update='" . date('Y-m-d H:i:s') . "' WHERE dni='{$model->dni}'";
                $connection = Yii::$app->getDb();
                $model = $connection->createCommand($query)->execute();
            } else {
                $query = "INSERT INTO clientes_hash (dni,hash,fecha_create,fecha_update) VALUES ('{$hash}','{$model->dni}','" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "'); ";
                $connection = Yii::$app->getDb();
                $model = $connection->createCommand($query)->execute();
            }
//            $query = "UPDATE clientes SET hash='{$hash}' WHERE dni='{$model->dni}'";
//            $connection = Yii::$app->getDb();
//            $model = $connection->createCommand($query)->execute();
            if ($model) {
                return $hash;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function calcular_edad($fecha) {
        try {
            if(is_null($fecha) || empty($fecha)){
                throw new Exception("Debe ser una fecha de cumpleaños");
            }
            $dias = explode("-", $fecha, 3);
            
            $dias = mktime(0, 0, 0, $dias[1], $dias[2], $dias[0]);

            $edad = (int) ((time() - $dias) / 31556926 );

            return $edad;
        } catch (Exception $ex) {
            return 0;
        }
    }
    
    public function obtener_empleos() {
        $query = "SELECT DISTINCT(codemp) AS codemp,codemp FROM clientes ORDER BY codemp;";        
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
    
    public function tiene_deuda_cliente($deuda_desde,$deuda_hasta,$dni) {
        $query = "SELECT count(*) FROM cuotas INNER JOIN operaciones ON cuotas.id_operacion=operaciones.id WHERE 
            (vencim BETWEEN DATE('{$deuda_desde}') AND DATE('{$deuda_hasta}')) AND id_cliente={$dni}";        
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

}
