<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_operaciones_inmobiliarias".
 *
 * @property integer $id_operacion_inmobiliaria
 * @property string $nro_ope
 * @property integer $desde_anio
 * @property integer $desde_mes
 * @property integer $hasta_anio
 * @property integer $hasta_mes
 * @property string $fecha_ope
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property integer $cod_propiedad
 * @property integer $cod_cliente
 * @property integer $cod_garante
 * @property string $observaciones
 * @property integer $confirmada
 * @property string $fecha_confirmacion
 * @property integer $conv_desocup
 * @property integer $dia_venc_mensual
 * @property integer $id_inmobiliaria
 * @property string $interes_dia_mora
 * @property string $editor
 * @property string $fechatimestamp
 * @property integer $permite_pagos_pendientes
 * @property integer $tiene_expensas
 * @property integer $plazo
 * @property string $tipo_contrato
 * @property integer $firma_representante
 * @property integer $inquilino_1
 * @property integer $inquilino_2
 * @property integer $deposito_garantia
 * @property string $deposito_monto
 * @property integer $deposito_cuotas
 * @property string $deposito_contrato_monto
 * @property integer $excento
 * @property integer $honorarios
 * @property string $excento_monto
 * @property string $excento_cuotas
 * @property integer $contrato_firmado
 */
class A2OperacionesInmobiliarias extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'a2_operaciones_inmobiliarias';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['desde_anio', 'desde_mes', 'hasta_anio', 'hasta_mes', 'cod_propiedad', 
				 'conv_desocup', 'id_inmobiliaria', 'interes_dia_mora','estado','tipo_contrato','destino_contrato','cod_cliente','dia_venc_mensual', 'fecha_firma_contrato', 'fecha_firma_convenio'], 'required'],

            [['desde_anio', 'desde_mes', 'hasta_anio', 'hasta_mes', 'cod_propiedad', 'cod_cliente', 'cod_garante', 'confirmada', 'conv_desocup', 'dia_venc_mensual', 
				'id_inmobiliaria', 'permite_pagos_pendientes', 'tiene_expensas', 'plazo', 'firma_representante', 'inquilino_1', 'inquilino_2','contrato_firmado','locador_1','locador_2'], 'integer'],
            [['fecha_ope', 'fecha_desde', 'fecha_hasta', 'fecha_confirmacion', 'fechatimestamp','fecha_firma_contrato',
            'fecha_firma_convenio','expensas','nota','honorarios'], 'safe'],
            [['observaciones', 'tipo_contrato','nota'], 'string'],
            [['interes_dia_mora', 'deposito_monto', 'deposito_contrato_monto',], 'number'],
            [['nro_ope'], 'string', 'max' => 256],
            [['editor'], 'string', 'max' => 250],
            [['interes_dia_mora'],'number','min'=>0],
            [['dia_venc_mensual'],'number','min'=>1,'max'=>31],
            [['deposito_monto','deposito_contrato_monto','excento_monto'],'number','min'=>0,],
            //[['excento_cuotas,deposito_cuotas'],'integer','min'=>1,'max'=>12],
            [['firma_representante'],'validar_firma_representante'],
            [['hasta_mes'],'validar_hasta'],
            //[['deposito_garantia'],'validar_deposito'],
            //[['honorarios'],'validar_honorarios_monto'],
            [['estado'],'validar_estado_contrato'],            
        ];
    }
/*
    function validar_deposito(){
        if($this->deposito_garantia==1){
            if(!$this->deposito_monto){
                $this->addError("deposito_monto","Debe ingresar el deposito monto.");
            }
            if(!$this->deposito_cuotas){
                $this->addError("deposito_cuotas","Debe ingresar el deposito cuotas.");
            }
            
        }
    }
*/

    function validar_honorarios_monto(){
        if($this->honorarios==1){
            if(!$this->excento_monto){
                $this->addError("excento_monto","Debe ingresar el honorario monto.");
            }
            if(!$this->excento_cuotas){
                $this->addError("excento_cuotas","Debe ingresar el honorario cuotas.");
            }
        }
    }

    function validar_estado_contrato(){
        
        if($this->estado=='ACTIVO' && $this->isNewRecord){
            $model=A2OperacionesInmobiliarias::find()->where(['cod_propiedad'=>$this->cod_propiedad,'estado'=>'ACTIVO'])->one();
            if($model){
                $this->addError("estado","Para activar el contrato, no debe existir un contrato activo.");
            }
        }
        if($this->estado=='ACTIVO' && !$this->isNewRecord){
            $model=A2OperacionesInmobiliarias::find()->where(['cod_propiedad'=>$this->cod_propiedad,'estado'=>'ACTIVO'])->one();
            if($model && $this->id_operacion_inmobiliaria!=$model->id_operacion_inmobiliaria){
                $this->addError("estado","Ya existe un contrato activo.");
            }
        }
        if($this->estado=='PENDIENTE' && $this->isNewRecord){
            $model=A2OperacionesInmobiliarias::find()->where(['cod_propiedad'=>$this->cod_propiedad,'estado'=>'PENDIENTE'])->one();
            if($model){
                $this->addError("estado","Ya existe un contrato en estado pendiente, solo un contrato puede estar en estado pendiente.");
            }
        }
        if($this->estado=='RENOVADO' && $this->isNewRecord){
            
            if(empty($this->fecha_firma_contrato)){
                $this->addError("fecha_firma_contrato","La fecha de firma del contrado debe tener un formato correcto xx/xx/xxxx.");
            }
            if(empty($this->fecha_firma_convenio)){
                $this->addError("fecha_firma_convenio","La fecha de firma de convenio debe tener un formato correcto xx/xx/xxxx.");
            }
        }
    }

    function validar_firma_representante(){
        if($this->firma_representante==1){
            if(empty($this->representante)){
                $this->addError("representante","Debe ingresar el nombre y apellido del representante");
            }
            if( empty($this->representante_cuit)){
                $this->addError("representante_cuit","Debe ingresar el cuit del representante");
            }
            if(empty($this->representante) || empty($this->representante_cuit)){
                return false;
            }
        }
        return true;
    }

    function validar_hasta(){
        if($this->hasta_anio==$this->desde_anio){
            if($this->hasta_mes<$this->desde_mes){
                $this->addError("hasta_mes","El campo Hasta mes debe ser mayor al campo Desde mes");
                return false;
            }            
        }else{
            if($this->desde_anio>$this->hasta_anio){
                $this->addError("desde_anio","El campo Desde año debe ser menor o igual al campo Hasta año");
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_operacion_inmobiliaria' => 'Id Operacion Inmobiliaria',
            'nro_ope' => 'Nro Ope',
            'desde_anio' => 'Desde Año',
            'desde_mes' => 'Desde Mes',
            'hasta_anio' => 'Hasta Año',
            'hasta_mes' => 'Hasta Mes',
            'fecha_ope' => 'Fecha de Operacion',
            'fecha_desde' => 'Fecha Desde',
            'fecha_hasta' => 'Fecha Hasta',
            'cod_propiedad' => 'Cod Propiedad',
            'locador'=>'Locador',
            'cod_cliente' => 'Inquilino Titular',
            'cod_garante' => 'Garante',
            'observaciones' => 'Observaciones',
            'confirmada' => 'Confirmada',
            'fecha_confirmacion' => 'Fecha Confirmacion',
            'conv_desocup' => 'Convenio de Desocupación',
            'dia_venc_mensual' => 'Dia Vencimiento Mensual',
            'id_inmobiliaria' => 'Inmobiliaria',
            'interes_dia_mora' => 'Interes por Dia de Mora',
            'editor' => 'Editor',
            'fechatimestamp' => 'Fechatimestamp',
            'permite_pagos_pendientes' => 'Permite Pagos Pendientes',
            'tiene_expensas' => 'Paga Expensas',
            //'expensas'=>'Expensas',
            'plazo' => 'Plazo',
            'tipo_contrato' => 'Tipo Contrato',
            'firma_representante' => 'Firma Representante del Locador',
            'representante'=>'Representante',
            'representante_cuit'=>'Representante Cuit',
            'inquilino_1' => 'Inquilino 1',
            'inquilino_2' => 'Inquilino Cotitular',
            //'deposito_garantia' => 'Deposito Garantia',
            'deposito_monto' => 'Deposito Monto',
            //'deposito_cuotas' => 'Deposito Cuotas',
            //'deposito_contrato_monto' => 'Deposito Contrato Monto',
            //'excento' => 'Excento',
            //'honorarios' => 'Honorarios',
            //'excento_monto' => 'Honorarios Monto',
            //'excento_cuotas' => 'Honorarios Cuotas',
            'contrato_firmado' => 'Contrato Firmado',
			'fecha_firma_contrato'=>'Fecha de Celebracion del contrato',
			'fecha_firma_convenio'=>'Fecha de Celebracion del convenio'
        ];
    }

    public function getFirmante() {
        return $this->hasOne(Firmante::className(), ['id_cliente' => 'cod_cliente']);
    }

    public function getInmueble() {
        return $this->hasOne(A2Noticias::className(), ['id_noticia' => 'cod_propiedad']);
    }

    public function getCliente() {
        return $this->hasOne(A2Clientes::className(), ['id_cliente' => 'cod_cliente']);
    }
    
    public function getInquilino1() {
        return $this->hasOne(A2Clientes::className(), ['id_cliente' => 'inquilino_1']);
    }
    
    public function getInquilino2() {
        return $this->hasOne(A2Clientes::className(), ['id_cliente' => 'inquilino_2']);
    }
    
    public function getGarante() {
        return $this->hasOne(A2Clientes::className(), ['id_cliente' => 'cod_garante']);
    }

    public function getLocadorPropietario() {
        return $this->hasOne(Propietarios::className(), ['id' => 'locador']);
    }

    public function getLocador1Propietario() {
        return $this->hasOne(Propietarios::className(), ['id' => 'locador_1']);
    }

    public function getLocador2Propietario() {
        return $this->hasOne(Propietarios::className(), ['id' => 'locador_2']);
    }
	
	public function getInmobiliaria() {
        return $this->hasOne(A2Inmobiliarias::className(), ['id_inmobiliaria' => 'id_inmobiliaria']);
    }
    
    
    public function getOperacionesItems() {
        return $this->hasMany(A2OperacionesItems::className(), ['id_operacion' => 'id_operacion_inmobiliaria']);
    }

    public function existen_operacion_periodo($mes, $anio,$id_operacion="",$estado="ACTIVO") {
        $query = "SELECT id_operacion_inmobiliaria FROM a2_operaciones_inmobiliarias WHERE 
            ((desde_anio*100 + desde_mes) <=($anio*100+$mes)) AND "
                . " ((hasta_anio*100 + hasta_mes) >=($anio*100+$mes)) AND estado='$estado'";

        if(!empty($id_operacion)){
            $query.=" AND id_operacion_inmobiliaria=".$id_operacion;
        }
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function ObtenerInmueblesConContratosActivos() {
        $query = "SELECT a2_noticias.direccion,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,a2_noticias.id_noticia
				
				FROM a2_noticias 							
				LEFT JOIN a2_operaciones_inmobiliarias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                                WHERE a2_operaciones_inmobiliarias.estado='ACTIVO'
				ORDER BY a2_noticias.direccion ASC";

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }

    public function ObtenerInmueblesConContratosActivosPendiente() {
        $query = "SELECT * FROM (SELECT a2_noticias.direccion,
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,a2_noticias.id_noticia FROM a2_noticias 							
        LEFT JOIN a2_operaciones_inmobiliarias ON a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        WHERE a2_operaciones_inmobiliarias.estado='ACTIVO'
        UNION
        SELECT a2_noticias.direccion,a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
        a2_noticias.id_noticia FROM a2_noticias LEFT JOIN a2_operaciones_inmobiliarias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad WHERE 
        a2_operaciones_inmobiliarias.estado='PENDIENTE' AND a2_noticias.id_noticia NOT IN (
        SELECT a2_operaciones_inmobiliarias.cod_propiedad FROM a2_operaciones_inmobiliarias 
        WHERE a2_operaciones_inmobiliarias.estado='ACTIVO') ) as t1
        ORDER BY t1.direccion ASC,t1.id_noticia ASC";

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
    
    public function EsUltimoPeriodo($id_operacion, $anio, $mes) {
        $model_operacion = A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => $id_operacion,
            'hasta_anio' => $anio,'hasta_mes' => $id_operacion])->one();
        return$model_operacion;
    }

    public function marcar_pendiente_renovacion($operacion_id) {
        $query = "UPDATE a2_operaciones_inmobiliarias SET estado_renovacion='PENDIENTE', contrato_firmado=0 
                WHERE id_operacion_inmobiliaria={$operacion_id}";

        $connection = Yii::$app->getDb();
        $connection->createCommand($query)->execute();        
    }

    public function firmar_contrato($operacion_id) {
        $query = "UPDATE a2_operaciones_inmobiliarias SET contrato_firmado=1
                WHERE id_operacion_inmobiliaria={$operacion_id}";

        $connection = Yii::$app->getDb();
        $connection->createCommand($query)->execute();        
    }

    public function actualizar_estado($operacion_id,$estado) {
        $query = "UPDATE a2_operaciones_inmobiliarias SET estado='{$estado}'
                WHERE id_operacion_inmobiliaria={$operacion_id}";

        $connection = Yii::$app->getDb();
        $connection->createCommand($query)->execute();        
    }

    public function renovar_contrato($operacion_id,$desde_mes,$desde_anio,$hasta_mes,$hasta_anio) {
        $query = "UPDATE a2_operaciones_inmobiliarias SET desde_mes={$desde_mes}, desde_anio={$desde_anio}, hasta_mes={$hasta_mes},
        hasta_anio={$hasta_anio} WHERE id_operacion_inmobiliaria={$operacion_id}";

        $connection = Yii::$app->getDb();
        $connection->createCommand($query)->execute();    
    }
	
	public function beforeSave($insert) {
        if($this->isNewRecord){
            $this->fecha_ope=date('Y-m-d H:i:s');
            /*$arreglo = explode('/', $this->fecha_ope);
			$this->fecha_ope = $arreglo[2] . "-" . $arreglo[1] . "-" . $arreglo[0];*/
        }
		if(!empty($this->fecha_firma_contrato)){
			$arreglo = explode('/', $this->fecha_firma_contrato);
			$this->fecha_firma_contrato = $arreglo[2] . "-" . $arreglo[1] . "-" . $arreglo[0];
		}
		if(!empty($this->fecha_firma_convenio)){
			$arreglo = explode('/', $this->fecha_firma_convenio);
			$this->fecha_firma_convenio = $arreglo[2] . "-" . $arreglo[1] . "-" . $arreglo[0];
        }
        
        return TRUE;
    }
    
    public function obtener_plazo(){
        $f1 = new \DateTime($this->desde_anio."-".$this->desde_mes."-01 00:00:00" );
        $f2 = new \DateTime($this->hasta_anio."-".$this->hasta_mes."-01 00:00:00");                                                    
     
       // obtener la diferencia de fechas
       $d = $f2->diff($f1);
       
       $difmes =  $d->format('%m');
       $intervalAnos = $d->format("%y")*12;
        return $difmes+$intervalAnos;
    }

    public function grabar_nota($operacion_id,$nota) {
        $query = "UPDATE a2_operaciones_inmobiliarias SET nota=:nota, ultimo_contacto=:ultimo_contacto,
            usuario_contacto=:usuario_contacto WHERE 
        id_operacion_inmobiliaria=:id_operacion_inmobiliaria";

        $connection = Yii::$app->getDb();
        $connection->createCommand($query,[':nota'=>$nota,':ultimo_contacto'=>date('Y-m-d H:i:s'),
            ':id_operacion_inmobiliaria'=>$operacion_id,':usuario_contacto'=>Yii::$app->user->identity->nikname])->execute();    
    }
    

}
