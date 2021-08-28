<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invitaciones".
 *
 * @property integer $dni
 * @property integer $cantidad
 * @property string $fecha_create
 * @property string $fecha_update
 */
class Invitaciones extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'invitaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dni', 'cantidad', 'fecha_create', 'fecha_update'], 'required'],
            [['dni', 'cantidad'], 'integer'],
            [['fecha_create', 'fecha_update'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dni' => 'Dni',
            'cantidad' => 'Cantidad',
            'fecha_create' => 'Fecha Create',
            'fecha_update' => 'Fecha Update',
        ];
    }

    public function total_invitaciones_distintas() {
        $query = "SELECT COUNT(*) FROM (SELECT DISTINCT(dni) FROM invitaciones)as t1;;";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

    public function total_invitaciones_por_cliente($dni) {
        $query = "SELECT cantidad FROM invitaciones WHERE dni='{$dni}'";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

    public function incrementar_invitacion($dni) {
        $model = Invitaciones::find()->where(['dni' => $dni])->one();        
        if ($model) {            
            try {
                $query = "UPDATE invitaciones SET fecha_update='".date('Y-m-d h:i:s')."',cantidad=(cantidad+1) WHERE dni='{$dni}'";
                $connection = Yii::$app->getDb();
                $model = $connection->createCommand($query)->execute();                
            } catch (Exception $ex) {
                print_r($ex->getMessage());
            }
        }
    }

}
