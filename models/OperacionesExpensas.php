<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "operaciones_expensas".
 *
 * @property integer $id
 * @property integer $operacion_id
 * @property integer $tipo_expensas_id
 * @property integer $inmuebles_id
 * @property integer $mes
 * @property integer $year
 * @property string $importe
 * @property string $estado
 * @property integer $comprobante_id
 */
class OperacionesExpensas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operaciones_expensas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operacion_id', 'tipo_expensas_id', 'inmuebles_id', 'mes', 'year', 'importe','estado_reg'], 'required'],
            [['operacion_id', 'tipo_expensas_id', 'inmuebles_id', 'mes', 'year', 'comprobante_id'], 'integer'],
            [['importe'], 'number'],
            [['estado'], 'string'],
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
            'tipo_expensas_id' => 'Tipo de Expensa',
            'inmuebles_id' => 'Inmuebles ID',
            'mes' => 'Mes',
            'year' => 'Año',
            'importe' => 'Importe',
            'estado' => 'Estado',
            'comprobante_id' => 'Comprobante ID',
            'estado_reg' => 'Estado',
        ];
    }
    
    public function getTipoExpensas() {
        return $this->hasOne(TipoExpensas::className(), ['id' => 'tipo_expensas_id']);
    }
	
	public function getInmueble() {
        return $this->hasOne(A2Noticias::className(), ['id_noticia' => 'inmuebles_id']);
    }
    
    public function getContrato() {
        return $this->hasOne(A2OperacionesInmobiliarias::className(), ['id_operacion_inmobiliaria' => 'operacion_id']);
    }
	
	public function obtener_expensas_by_propietario($propietario_id){
        $condiciones = " AND inmuebles_propietarios.propietario_id=" . $propietario_id;
        
        $query="SELECT operaciones_expensas.* FROM operaciones_expensas 				
				LEFT JOIN a2_noticias ON a2_noticias.id_noticia=operaciones_expensas.inmuebles_id
                LEFT JOIN inmuebles_propietarios ON a2_noticias.id_noticia=inmuebles_propietarios.inmueble_id
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				
				WHERE 1	" . $condiciones . " AND operaciones_expensas.estado='PAGADO'"
                . " ORDER BY operaciones_expensas.year,operaciones_expensas.mes";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function obtener_expensas_by_grupo($grupo_id,$arreglo_periodo){    
        $condicion="";
        if(count($arreglo_periodo)>0){
            if(is_numeric($arreglo_periodo[0])){
                $condicion.=" AND operaciones_expensas.mes=".$arreglo_periodo[0];
            }
            if(is_numeric($arreglo_periodo[1])){
                $condicion.=" AND operaciones_expensas.year=".$arreglo_periodo[1];
            }
        }
        

        $query="SELECT operaciones_expensas.*,tipo_expensas.descripcion,a2_noticias.direccion,a2_clientes.NOMBRE,a2_noticias.id_grupo FROM operaciones_expensas INNER JOIN a2_noticias ON a2_noticias.id_noticia=operaciones_expensas.inmuebles_id
LEFT JOIN a2_secciones	ON	a2_noticias.seccion=a2_secciones.id_seccion
LEFT JOIN a2_objeto_de_propiedad ON	a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
INNER JOIN a2_operaciones_inmobiliarias ON operaciones_expensas.operacion_id=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria
INNER JOIN a2_clientes ON a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente
INNER JOIN tipo_expensas ON operaciones_expensas.tipo_expensas_id=tipo_expensas.id
where operaciones_expensas.estado='impago' AND a2_noticias.id_grupo={$grupo_id} {$condicion}
ORDER BY a2_clientes.NOMBRE,a2_noticias.direccion,operaciones_expensas.`year`,operaciones_expensas.`mes`";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function obtener_expensas_by_grupo_tipo($grupo_id,$arreglo_periodo,$tipo_string){    
        $condicion="";
        if(count($arreglo_periodo)>0){
            if(is_numeric($arreglo_periodo[0])){
                $condicion.=" AND operaciones_expensas.mes=".$arreglo_periodo[0];
            }
            if(is_numeric($arreglo_periodo[1])){
                $condicion.=" AND operaciones_expensas.year=".$arreglo_periodo[1];
            }
        }
        
        $tipo="";
        if(!empty($tipo_string)){
            $tipo.=" AND operaciones_expensas.tipo_expensas_id IN ({$tipo_string})";
        }

        $query="SELECT operaciones_expensas.*,tipo_expensas.descripcion,a2_noticias.direccion,a2_clientes.NOMBRE,a2_noticias.id_grupo FROM operaciones_expensas INNER JOIN a2_noticias ON a2_noticias.id_noticia=operaciones_expensas.inmuebles_id
LEFT JOIN a2_secciones	ON	a2_noticias.seccion=a2_secciones.id_seccion
LEFT JOIN a2_objeto_de_propiedad ON	a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
INNER JOIN a2_operaciones_inmobiliarias ON operaciones_expensas.operacion_id=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria
INNER JOIN a2_clientes ON a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente
INNER JOIN tipo_expensas ON operaciones_expensas.tipo_expensas_id=tipo_expensas.id
where operaciones_expensas.estado_reg='ACTIVO' AND a2_noticias.id_grupo={$grupo_id} {$condicion} {$tipo}
ORDER BY a2_clientes.NOMBRE,a2_noticias.direccion,operaciones_expensas.`year`,operaciones_expensas.`mes`";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
	
	public function obtener_expensas_pendientes($operacion_id){
		$query="SELECT operaciones_expensas.*,tipo_expensas.descripcion FROM operaciones_expensas INNER JOIN tipo_expensas ON 
            (operaciones_expensas.tipo_expensas_id=tipo_expensas.id) WHERE operaciones_expensas.operacion_id=" . $operacion_id . " AND estado_reg='PENDIENTE' ORDER BY
                YEAR DESC,MES DESC,descripcion ASC";
		$connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
    
    public function obtener_expensas_pendientes_aprobar(){
		$query="SELECT operaciones_expensas.*,tipo_expensas.descripcion FROM operaciones_expensas INNER JOIN tipo_expensas ON 
            (operaciones_expensas.tipo_expensas_id=tipo_expensas.id) WHERE  estado_reg='PENDIENTE' AND 
            grupo_expensas_id IS NULL ORDER BY
                YEAR DESC,MES DESC,descripcion ASC";
		$connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
    
    public function obtener_expensas_pendientes_aprobar_grupo(){
        //operaciones_expensas.inmuebles_id,
		$query="SELECT grupo_expensas.*,tipo_expensas.descripcion,a2_grupos.descripcion as descripcion_grupo FROM grupo_expensas 
        INNER JOIN operaciones_expensas ON operaciones_expensas.grupo_expensas_id=grupo_expensas.id 
        INNER JOIN tipo_expensas ON (operaciones_expensas.tipo_expensas_id=tipo_expensas.id)
        INNER JOIN a2_grupos ON (grupo_expensas.grupo_id=a2_grupos.id_grupo)
         WHERE  estado_reg='PENDIENTE' 
        GROUP BY grupo_expensas.id  
        ORDER BY YEAR DESC,MES DESC,descripcion ASC";
		$connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
	}
}
