<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gastos".
 *
 * @property integer $id
 * @property integer $inmueble_id
 * @property string $fecha
 * @property string $importe
 * @property string $estado
 * @property string $observacion
 */
class Gastos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gastos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['inmueble_id','operacion_id','fecha','importe'], 'required'],
            [['inmueble_id','operacion_id'], 'integer'],
            [['fecha'], 'safe'],
            [['importe'], 'number'],
            [['estado', 'observacion'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inmueble_id' => 'Inmueble ID',
            'fecha' => 'Fecha',
            'importe' => 'Importe',
            'estado' => 'Estado',
            'observacion' => 'Observacion',
        ];
    }
	
	public function beforeSave($insert) {
        $arreglo = explode('/', $this->fecha);
        $this->fecha = $arreglo[2] . "-" . $arreglo[1] . "-" . $arreglo[0];
        return TRUE;
    }
	
	public function getInmueble() {
        return $this->hasOne(A2Noticias::className(), ['id_noticia' => 'inmueble_id']);
    }
	
	public function getOperacionInmobiliaria() {
        return $this->hasOne(A2OperacionesInmobiliarias::className(), ['id_operacion_inmobiliaria' => 'operacion_id']);
    }
	
	public function obtener_gastos_by_propietario($propietario_id){
        $condiciones = " AND inmuebles_propietarios.propietario_id=" . $propietario_id;
        
        $query="SELECT 
				gastos.id,
				gastos.inmueble_id,
				gastos.fecha,
				gastos.importe,
				gastos.estado,
				gastos.observacion
				
				
				FROM gastos 				
				LEFT JOIN a2_noticias ON a2_noticias.id_noticia=gastos.inmueble_id
                LEFT JOIN inmuebles_propietarios ON a2_noticias.id_noticia=inmuebles_propietarios.inmueble_id
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				
				WHERE 1	" . $condiciones . " AND gastos.estado='PENDIENTE'"
                . " ORDER BY gastos.fecha DESC";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
}
