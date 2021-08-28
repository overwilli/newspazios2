<?php

namespace app\controllers;

use Yii;
use app\models\A2Caja;
use app\models\A2CajaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\NotaCredito;
use app\models\A2Liquidaciones;
use app\models\ImpresoraFiscal;
use app\models\A2Facturacion;
/**
 * A2CajaController implements the CRUD actions for A2Caja model.
 */
class NotaCreditoController extends Controller {


    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['index', 'logout', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','get-liquidacion','get-liquidacion-by-id','cierre-x','cierre-z'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	
	public function actionCierreX() {
		$model = new ImpresoraFiscal();
		$resultado=$model->cierre_x();
        A2Facturacion::grabar_respuesta_impresora(json_encode($resultado),"Cierre X", 0);
        return $this->render('cierre-x');		
	}
	
	public function actionCierreZ() {
		$model = new ImpresoraFiscal();		
		$resultado=$model->cierre_z();
        A2Facturacion::grabar_respuesta_impresora(json_encode($resultado),"Cierre Z", 0);
        return $this->render('cierre-z');			
	}

    public function actionIndex() {
        $model = new NotaCredito();
        
        if ($model->load(Yii::$app->request->post())) {
			$entrada=Yii::$app->request->post('NotaCredito');
			
            $param['nombre']=$entrada['apellido_nombre'];
			$param['direccion_cliente']=$entrada['direccion'];
			$param['cuil']=$entrada['cuil'];
			$param['descripcion']=$entrada['descripcion'];
			$param['monto']=$entrada['monto'];
			$param['documento_vinculado']="";
			
			$resultado=ImpresoraFiscal::nota_credito($param);
			if($resultado){
				A2Facturacion::grabar_respuesta_impresora(json_encode($resultado),"Nota de Crédito", $param['monto'],
					$entrada['liquidacion_id']);
				
				$id_unico = A2ParametrosGlobales::get_comprobante_unico();
                $obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);
				
				//REGISTRA MOVIMIENTO
				$obj_movimiento = new A2Movimientos();
				$obj_movimiento->fecha_movimiento = date("Y-m-d H:i:s");
				$obj_movimiento->usuario = Yii::$app->user->identity->nikname;
				$obj_movimiento->id_liquidacion = $entrada['liquidacion_id'];
				$obj_movimiento->observaciones = $param['documento_vinculado']." ".$param['descripcion'];
				$obj_movimiento->comprobante_pago_unico = $id_unico;
				$obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
				$obj_movimiento->caja_id = $obj_caja->id_caja;
				
				$obj_movimiento->tipo_registro = 'pago';
				$obj_movimiento->estado_pago_pendiente = '';
				
				$obj_movimiento->movimiento_tipo = 9;
				$obj_movimiento->monto = $param['monto'];
				if (!$obj_movimiento->save()) {
					$bandera = false;
				}
				
			}
			
			
			
            //return $this->redirect(['view', 'id' => $model->id_caja]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionGetLiquidacion($q = null, $id = null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query="SELECT 
            a2_liquidaciones.id_liquidacion,
            a2_liquidaciones.liq_anio,
            a2_liquidaciones.liq_mes,
            a2_liquidaciones.fecha_pago,
            a2_liquidaciones.monto_pagado,
                            a2_clientes.NOMBRE as nombrecliente,
                            a2_clientes.DNI,
                            a2_clientes.CUIL,
                            a2_noticias.direccion
            
            FROM a2_liquidaciones 
            LEFT JOIN a2_operaciones_inmobiliarias ON 
            a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
            LEFT JOIN a2_noticias ON
            a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
            LEFT JOIN a2_secciones	ON			
            a2_noticias.seccion=a2_secciones.id_seccion
            LEFT JOIN a2_objeto_de_propiedad ON
            a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
            LEFT JOIN a2_clientes ON
            a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
            WHERE 1	AND	a2_clientes.NOMBRE LIKE '%" . $q . "%' AND a2_liquidaciones.fecha_pago<>'0000-00-00 00:00:00' ORDER BY a2_liquidaciones.fecha_pago DESC";
            $resultado = Yii::$app->db->createCommand($query)->queryAll();
            $data = array();
            foreach($resultado as $fila){
                $direccion=str_replace("-","|",$fila["direccion"]);
                array_push($data,array("id"=>$fila["id_liquidacion"], "text"=>$fila["nombrecliente"]."-".
                        $fila["liq_mes"]."/".$fila["liq_anio"]."-".$fila["monto_pagado"], "value" => 
                    $fila["nombrecliente"]."-".
                        $fila["liq_anio"]."/".$fila["liq_mes"]."-".$fila["monto_pagado"]."-".$direccion.
                            "-".$fila["DNI"]."-".$fila["CUIL"]));
            }
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => '', 'text' =>''];
        }
        return $out;
    }

    public function actionGetLiquidacionById(){
        $liquidacion_id=Yii::$app->request->get('liquidacion_id');
        $model_liq=A2Liquidaciones::find()->where(['id_liquidacion'=>$liquidacion_id])->one();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($model_liq){
			$data['cliente_id']=$model_liq->operacionInmobiliaria->cliente->id_cliente;
			$data['apellido_nombre']=$model_liq->operacionInmobiliaria->cliente->NOMBRE;
            $data['dni']=$model_liq->operacionInmobiliaria->cliente->DNI;
            $data['cuil']=$model_liq->operacionInmobiliaria->cliente->CUIL;
            $data['direccion']=$model_liq->operacionInmobiliaria->inmueble->direccion;
        }
        $out['results']=$data;
        return $out;
    }

}