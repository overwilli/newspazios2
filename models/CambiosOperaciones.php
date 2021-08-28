<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cambios_operaciones".
 *
 * @property integer $id
 * @property integer $operacion_id
 * @property integer $desde_anio
 * @property integer $desde_mes
 * @property integer $hasta_anio
 * @property integer $hasta_mes
 * @property string $estado
 * @property string $fecha
 * @property string $usuario
 */
class CambiosOperaciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cambios_operaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operacion_id', 'desde_anio', 'desde_mes', 'hasta_anio', 'hasta_mes'], 'integer'],
            [['estado'], 'string'],
            [['fecha'], 'safe'],
            [['usuario'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operacion_id' => 'Operacion ID',
            'desde_anio' => 'Desde Año',
            'desde_mes' => 'Desde Mes',
            'hasta_anio' => 'Hasta Año',
            'hasta_mes' => 'Hasta Mes',
            'estado' => 'Estado',
            'fecha' => 'Fecha',
            'usuario' => 'Usuario',
        ];
    }
	
	public function beforeSave($insert)
	{
		/*if (!parent::beforeSave($insert)) {
			return false;
		}*/
		if($this->isNewRecord){
			$query = "UPDATE cambios_operaciones SET estado='anulado' WHERE operacion_id={$this->operacion_id} AND estado='pendiente'";
			$connection = Yii::$app->getDb();
			$connection->createCommand($query)->execute(); 
		}
		
		return true;
	}
}
