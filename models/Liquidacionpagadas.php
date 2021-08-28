<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquidacionpagadas".
 *
 * @property integer $id
 * @property string $fecha
 * @property integer $propietario_id
 * @property string $total_cobrado
 * @property string $interes_mora
 * @property string $comision
 * @property string $iva
 * @property string $gastos
 * @property string $estado
 * @property string $fecha_creacion
 * @property string $usuario
 */
class Liquidacionpagadas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'liquidacionpagadas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['propietario_id','required'],
            [['fecha', 'fecha_creacion'], 'safe'],
            [['propietario_id'], 'integer'],
            [['total_cobrado', 'interes_mora', 'comision', 'iva', 'gastos'], 'number'],
            [['estado'], 'string'],
            [['usuario'], 'string', 'max' => 45],
            [['propietario_id'], 'existen_orden_nueva'],
        ];
    }

    public function existen_orden_nueva() {
        if ($this->getIsNewRecord()) {
            $model=Liquidacionpagadas::find()->where(['propietario_id'=>$this->propietario_id,'estado'=>'Nuevo'])->one();
            if($model){
                $this->addError('propietario_id', "Ya existe una orden en estado Nuevo para el propietario seleccionado.");
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha',
            'propietario_id' => 'Propietario',
            'total_cobrado' => 'Total Cobrado',
            'interes_mora' => 'Interes Mora',
            'comision' => 'Comision',
            'iva' => 'Iva',
            'gastos' => 'Gastos',
            'estado' => 'Estado',
            'fecha_creacion' => 'Fecha Creacion',
            'usuario' => 'Usuario',
        ];
    }
    
    public function getPropietario() {
        return $this->hasOne(Propietarios::className(), ['id' => 'propietario_id']);
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            
            if ($this->getIsNewRecord()) {
                $array_fechas = explode('/', $this->fecha);
                $this->fecha = $array_fechas[2] . '-' . $array_fechas[1] . '-' . $array_fechas[0];
                $this->fecha_creacion=date('Y-m-d H:i:s');
            } 
//            else {
//                $array_fechas = explode('/', $this->fecha);
//                $this->fecha = $array_fechas[2] . '-' . $array_fechas[1] . '-' . $array_fechas[0];
//            }
            return true;
            
        } else {
            return false;
        }
    }

    public function cantidad_alquileres($orden_id){
        $query="SELECT COUNT(*) FROM liqpagadas_detalle INNER JOIN a2_liquidaciones ON 
        liqpagadas_detalle.liquidaciones_id=a2_liquidaciones.id_liquidacion INNER JOIN a2_operaciones_inmobiliarias ON
        (a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion) INNER JOIN a2_noticias ON
        (a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad) INNER JOIN a2_clientes ON 
        (a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente)
        WHERE liquidacionpagadas_id={$orden_id}";
        $connection = Yii::$app->getDb();
        $cantidad = $connection->createCommand($query)->queryScalar();
        return $cantidad;
    }
    
}
