<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "black_list_promo".
 *
 * @property integer $id
 * @property string $email
 * @property string $dni
 * @property string $fecha_creacion
 */
class BlackListPromo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'black_list_promo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_creacion'], 'safe'],
            [['email'], 'string', 'max' => 255],
            [['dni'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'dni' => 'Dni',
            'fecha_creacion' => 'Fecha Creacion',
        ];
    }
    
    public function total_clientes_distintos(){
        $query = "SELECT COUNT(*) FROM (SELECT DISTINCT(dni) FROM black_list_promo)as t1;";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }
    
    public function total_promociones_recibidas($dni){
        echo $query = "SELECT COUNT(*) FROM black_list_promo WHERE dni='{$dni}'";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }
    
    public function obtener_ultima_baja($dni){
        $query = "SELECT MAX(fecha_creacion) FROM black_list_promo WHERE dni='{$dni}'";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }
    
    public function clientes_en_blacklist() {
        $query = "SELECT * FROM clientes WHERE dni IN (SELECT DISTINCT(dni) FROM black_list_promo)";        
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
    
    public function clientes_en_blacklist_by_fecha($fecha_desde,$fecha_hasta) {
        $filtro="";
        if(!empty($fecha_desde) && !empty($fecha_hasta)){
            $filtro="WHERE fecha_creacion BETWEEN DATE '$fecha_desde' AND DATE '$fecha_hasta'";
        }
        $query = "SELECT * FROM clientes WHERE dni IN (SELECT DISTINCT(dni) FROM black_list_promo "
                . " {$filtro}) ORDER BY nombre";        
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
}
