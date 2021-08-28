<?php

namespace app\controllers;

use Yii;
use app\models\Liquidacionpagadas;
use app\models\LiquidacionpagadasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LiqpagadasDetalle;
use app\models\A2Liquidaciones;
use app\models\A2Movimientos;
use app\models\A2ParametrosGlobales;
use app\models\A2OperacionesInmobiliarias;
use app\models\A2Caja;
use app\models\OperacionesMovimientos;
use app\models\Gastos;
use app\models\LiqpagadasGastos;
use app\models\LiqpagadasExpensas;
use app\models\OperacionesExpensas;
use HTML2PDF;
use yii\filters\AccessControl;

/**
 * LiquidacionpagadasController implements the CRUD actions for Liquidacionpagadas model.
 */
class LiquidacionpagadasController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
			'access' => [
                'class' => AccessControl::className(),
                //'only' => ['index', 'logout', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete','form-liquidaciones-ordenes',
						'eliminar-todas-liquidaciones','eliminar-todos-gastos','eliminar-todas-expensas','eliminar-item-gasto','eliminar-item-detalle',
						'cerrar-orden','anular-orden','pagar-orden','otros-gastos','agregar-todas-liquidaciones',
                        'agregar-todos-gastos','agregar-todas-expensas','mostrar-items-orden','mostrar-items-gastos','mostrar-items-expensas',
                        'comprobante-propietario','comprobante-propietario-excel','pagadas','para-pagar'],
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

    /**
     * Lists all Liquidacionpagadas models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new LiquidacionpagadasSearch();
        $searchModel->estado="Nuevo";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Liquidacionpagadas models.
     * @return mixed
     */
    public function actionParaPagar() {
        $searchModel = new LiquidacionpagadasSearch();
        $searchModel->estado="Cerrado";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('para_pagar', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Liquidacionpagadas models.
     * @return mixed
     */
    public function actionPagadas() {
        //var_dump(Yii::$app->request->queryParams["LiquidacionpagadasSearch"]);
        $searchModel = new LiquidacionpagadasSearch();
        $searchModel->estado="Pagado";
        $param=Yii::$app->request->queryParams;
        if(!isset($param['LiquidacionpagadasSearch']) || !isset($param['LiquidacionpagadasSearch']['estado'])){
            $param['LiquidacionpagadasSearch']['estado'] = 'Pagado';
        }
        
        Yii::$app->request->queryParams=$param;
        //var_dump(Yii::$app->request->queryParams['LiquidacionpagadasSearch[estado]']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('pagadas', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Liquidacionpagadas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionFormLiquidacionesOrdenes($orden_id) {
        $model = $this->findModel($orden_id);
        return $this->render('form_liquidaciones_ordenes', ['model' => $model]);
    }

    /**
     * Creates a new Liquidacionpagadas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Liquidacionpagadas();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->estado = "NUEVO";
			$model->fecha = date('d/m/Y');
            if ($model->save()) {
                return $this->redirect(['form-liquidaciones-ordenes', 'orden_id' => $model->id]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Liquidacionpagadas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Liquidacionpagadas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model=$this->findModel($id);
        $model->estado='Anulado';
        if($model->save()){

        }
        //var_dump($model->getErrors());
        //->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Liquidacionpagadas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Liquidacionpagadas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Liquidacionpagadas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEliminarTodasLiquidaciones() {
        set_time_limit(0);
        $orden_id = Yii::$app->request->post('orden_id');
        LiqpagadasDetalle::deleteAll('liquidacionpagadas_id=:orden_id', [':orden_id' => $orden_id]);
        exit;
    }

    public function actionEliminarTodosGastos() {
        set_time_limit(0);
        $orden_id = Yii::$app->request->post('orden_id');
        LiqpagadasGastos::deleteAll('liquidacionpagadas_id=:orden_id', [':orden_id' => $orden_id]);
        exit;
    }
	
	public function actionEliminarTodasExpensas() {
        set_time_limit(0);
        $orden_id = Yii::$app->request->post('orden_id');
        LiqpagadasExpensas::deleteAll('liquidacionpagadas_id=:orden_id', [':orden_id' => $orden_id]);
        exit;
    }



    public function actionCerrarOrden() {
        $orden_id = Yii::$app->request->post('orden_id');
        $model = $this->findModel($orden_id);
        $model->estado='Cerrado';
        $model->save();
        exit;
    }
	
	public function actionAnularOrden() {
        $orden_id = Yii::$app->request->post('orden_id');
        $model = $this->findModel($orden_id);
        $model->estado='Cerrado';
        if($model->save()){
			$model_detalle_orden = LiqpagadasDetalle::find()->where(['liquidacionpagadas_id' => $orden_id])->all();
			$dinero_cobrado=0;
			$total_pagar=0;
			$total_comision=0;
			foreach ($model_detalle_orden as $row) {
				$dinero_cobrado+=$row->dinero_cobrado;
				//$interes_mora+=$row->interes_por_mora;

				$comision = 0;
				$direccion = "";            
				if ($row->liquidacion) {                
					if ($row->liquidacion->operacionInmobiliaria) {
						if ($row->liquidacion->operacionInmobiliaria->inmueble) {                        
							$model_inmueble_prop = \app\models\InmueblesPropietarios::find()->where(['inmueble_id' => $row->liquidacion->operacionInmobiliaria->cod_propiedad])->one();
							$comision = $model_inmueble_prop->comision;
						}
					}
				}
				$total_comision+=($row->dinero_cobrado * $comision) / 100;
				
			}		
		
			$expensas_total = 0;
			$model_detalle_expensas = LiqpagadasExpensas::find()->where(['liquidacionpagadas_id' => $orden_id])->all();
			foreach ($model_detalle_expensas as $row) {
				$expensas_total+=$row->importe;    
			}
			
			$gasto_total = 0;    
			$model_detalle_gastos = LiqpagadasGastos::find()->where(['liquidacionpagadas_id' => $orden_id])->all();
			foreach ($model_detalle_gastos as $row) {
				
				$gasto_total+=$row->importe;   
			}
			$total_pagar=($dinero_cobrado+$expensas_total)-($total_comision+$gasto_total);
			//-----AGREGAR GASTOS EXPENSAS Y GASTOS INMUEBLE-----------
			
			$obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);

            //REGISTRA MOVIMIENTO
            $obj_movimiento = new A2Movimientos();
            $obj_movimiento->fecha_movimiento = date('Y-m-d H:i:s');
            $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
            $obj_movimiento->id_liquidacion = 0;
            $obj_movimiento->observaciones = "ANULACION DE ORDEN DE PAGO DE PROPIETARIO N° #".$model->id." Propietario:". $model->propietario->apellido . 
            ", " . $model->propietario->nombre;
            $obj_movimiento->comprobante_pago_unico = 0;
            $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
            $obj_movimiento->caja_id = $obj_caja->id_caja;            
            $obj_movimiento->tipo_registro = 'pago';
            $obj_movimiento->estado_pago_pendiente = '';
            $obj_movimiento->movimiento_tipo = 15;
            $obj_movimiento->monto=$total_pagar;
            if ($obj_movimiento->save()) {
			}
			$arreglo['estado'] = 1;
            $arreglo['mensaje'] = "Orden Anulada correctamente.";
		}else{
            $arreglo['estado'] = 0;
            $arreglo['mensaje_error'] = "No se pudo anular la orden de pago.";
        }
        echo json_encode($arreglo);        
        exit;
    }

    public function actionOtrosGastos() {
        $orden_id = Yii::$app->request->post('orden_id');
        $otros_gastos = Yii::$app->request->post('otros_gastos');
        $model = $this->findModel($orden_id);
        $model->gastos=$otros_gastos;
        $model->save();
        exit;
    }

    public function actionAgregarTodasLiquidaciones() {
        set_time_limit(0);
        $orden_id = Yii::$app->request->post('orden_id');
        $fecha_pago_liq = "";
        $fecha_pago_liq = explode("/", Yii::$app->request->post('fecha_pago_liq'));
        if (!empty($fecha_pago_liq)) {
            
            $fecha_pago_liq = $fecha_pago_liq[2] . "-" . $fecha_pago_liq[1] . "-" . $fecha_pago_liq[0];
        }

        $model = $this->findModel($orden_id);

        $model_liq = new A2Liquidaciones();
        $model_liquidaciones = $model_liq->obtener_liquidacion_by_propietario($model->propietario_id, $fecha_pago_liq);
        foreach ($model_liquidaciones as $row) {

            if (LiqpagadasDetalle::existe_liquidacion($row['id_liquidacion']) == 0) {
                $bandera_depositos = TRUE;
                if (Yii::$app->request->post('incluir_depositos') == 1 || Yii::$app->request->post('incluir_depositos') == 2) {
                    $tiene_depositos = FALSE;
                    if (A2Liquidaciones::tiene_movimiento_tipo_by_liquidacion_id($row['id_liquidacion'], 3)) {
                        $tiene_depositos = TRUE;
                    }
                    if (Yii::$app->request->post('incluir_depositos') == 1 && $tiene_depositos) {
                        $bandera_depositos = FALSE;
                    }
                    if (Yii::$app->request->post('incluir_depositos') == 2 && !$tiene_depositos) {//SOLO DEPOSITOS
                        $bandera_depositos = FALSE;
                    }
                }
                if ($bandera_depositos) {

                    $model_liq_pagadas_detalle = new LiqpagadasDetalle();
                    $model_liq_pagadas_detalle->liquidacionpagadas_id = $orden_id;
                    $model_liq_pagadas_detalle->liquidaciones_id = $row['id_liquidacion'];
                    $model_liq_pagadas_detalle->dinero_cobrado = A2Liquidaciones::obtener_importe_pagado_by_liq($row['id_liquidacion']);
                    $model_liq_pagadas_detalle->interes_por_mora = A2Liquidaciones::obtener_mora_pagada($row['id_liquidacion']);

                    if ($model_liq_pagadas_detalle->save()) {
                        
                    }
                }
            }
        }
        exit;
    }
	
	public function actionAgregarTodosGastos(){
		set_time_limit(0);
        $orden_id = Yii::$app->request->post('orden_id');
		$model = $this->findModel($orden_id);
		
		
       
        $model_mov=new A2Movimientos;
        $model_mov=$model_mov->obtener_ingresos_gastos_por_propietario($model->propietario_id);

		foreach ($model_mov as $row) {				
				if (LiqpagadasGastos::existe_movimiento($row['id_movimiento']) == 0) {
					$model_liq_pagadas_gastos = new LiqpagadasGastos();
                    $model_liq_pagadas_gastos->liquidacionpagadas_id = $orden_id;
                    $model_liq_pagadas_gastos->movimientos_id = $row['id_movimiento'];
                    if($row['movimiento_tipo']==8){
                        $model_liq_pagadas_gastos->importe = $row['monto'];
                    }else{
                        $model_liq_pagadas_gastos->importe = $row['monto'];
                    }
                    $model_liq_pagadas_gastos->fecha_carga = date('Y-m-d H:i:s');

                    if ($model_liq_pagadas_gastos->save()) {
                        
                    }
				}
        }
        exit;
	}
	
	public function actionAgregarTodasExpensas(){
		set_time_limit(0);
        $orden_id = Yii::$app->request->post('orden_id');
		$model = $this->findModel($orden_id);
		
		$model_expensas = new OperacionesExpensas();
        $model_expensas = $model_expensas->obtener_expensas_by_propietario($model->propietario_id);
       
		foreach ($model_expensas as $row) {				
				if (LiqpagadasExpensas::existe_expensa($row['id']) == 0) {
					$model_liq_pagadas_expensas = new LiqpagadasExpensas();
                    $model_liq_pagadas_expensas->liquidacionpagadas_id = $orden_id;
                    $model_liq_pagadas_expensas->expensa_id = $row['id'];
                    $model_liq_pagadas_expensas->importe = $row['importe'];
                    $model_liq_pagadas_expensas->fecha_carga = date('Y-m-d H:i:s');

                    if ($model_liq_pagadas_expensas->save()) {
                        
                    }
				}
        }
        exit;
	}

    public function actionEliminarItemDetalle() {
        $liq_detalle_id = Yii::$app->request->post('liq_detalle_id');
        $model = LiqpagadasDetalle::findOne($liq_detalle_id);
        $model->delete();
        exit;
    }

    public function actionEliminarItemGasto() {
        $liq_gasto_id = Yii::$app->request->post('liq_gasto_id');
        $model = LiqpagadasGastos::findOne($liq_gasto_id);
        $model->delete();
        exit;
    }
	
	public function actionEliminarItemExpensas() {
        $liq_expensa_id = Yii::$app->request->post('liq_expensa_id');
        $model = LiqpagadasExpensas::findOne($liq_expensa_id);
        $model->delete();
        exit;
    }
	
	public function actionMostrarItemsExpensas() {

        $orden_id = Yii::$app->request->post('orden_id');

        $model = $this->findModel($orden_id);

        $model_detalle_expensas = LiqpagadasExpensas::find()->where(['liquidacionpagadas_id' => $orden_id])->all();

        return $this->renderPartial('mostrar_items_expensas', [
                    'model_detalle_expensas' => $model_detalle_expensas, 'orden_id' => $orden_id, 'model' => $model
        ]);
    }
	
	public function actionMostrarItemsGastos() {

        $orden_id = Yii::$app->request->post('orden_id');

        $model = $this->findModel($orden_id);

        $model_detalle_gastos = LiqpagadasGastos::find()->where(['liquidacionpagadas_id' => $orden_id])->all();

        return $this->renderPartial('mostrar_items_gastos', [
                    'model_detalle_gastos' => $model_detalle_gastos, 'orden_id' => $orden_id, 'model' => $model
        ]);
    }

    public function actionMostrarItemsOrden() {

        $orden_id = Yii::$app->request->post('orden_id');

        $model = $this->findModel($orden_id);

        $model_detalle_orden = LiqpagadasDetalle::find()->where(['liquidacionpagadas_id' => $orden_id])->all();

        return $this->renderPartial('mostrar_items_orden', [
                    'model_detalle_orden' => $model_detalle_orden, 'orden_id' => $orden_id, 'model' => $model
        ]);
    }

    public function actionPagarOrden(){
        $orden_id = Yii::$app->request->post('orden_id');
        $model = $this->findModel($orden_id);

        //se inicializan las variables para calcular totales.
        $dinero_cobrado = 0;
        $interes_mora = 0;
        $total_comision = 0;

        $model_detalle_orden = LiqpagadasDetalle::find()->where(['liquidacionpagadas_id' => $orden_id])->all();
        foreach ($model_detalle_orden as $row) {
            $dinero_cobrado+=$row->dinero_cobrado;
            $interes_mora+=$row->interes_por_mora;

            $comision = 0;
            $direccion = "";            
            if ($row->liquidacion) {                
                if ($row->liquidacion->operacionInmobiliaria) {
                    if ($row->liquidacion->operacionInmobiliaria->inmueble) {                        
                        $model_inmueble_prop = \app\models\InmueblesPropietarios::find()->where(['inmueble_id' => $row->liquidacion->operacionInmobiliaria->cod_propiedad])->one();
                        $comision = $model_inmueble_prop->comision;
                    }
                }
            }
            $total_comision+=($row->dinero_cobrado * $comision) / 100;
            
        }
		$expensas_total = 0;
		$model_detalle_expensas = LiqpagadasExpensas::find()->where(['liquidacionpagadas_id' => $orden_id])->all();
		foreach ($model_detalle_expensas as $row) {
			$expensas_total+=$row->importe;    
		}
		
		$gasto_total = 0;    
		$model_detalle_gastos = LiqpagadasGastos::find()->where(['liquidacionpagadas_id' => $orden_id])->all();
		foreach ($model_detalle_gastos as $row) {
			
			$gasto_total+=$row->importe;   
		}
        //------PREPARA LOS DATOS PARA GUARDAR LOS MONTOS TOTALES CALCULADOS EN LA ORDEN DE PAGO-----        
        $model->estado = 'Pagado';
        $model->total_cobrado = $dinero_cobrado + $expensas_total;
        $model->interes_mora = $interes_mora;
        $model->comision = $total_comision;
        $arreglo=null;
        if($model->save()){
            //---------SE PREPARAN LOS DATOS PARA GENERAR UN MOVIMIENTO EN LA CAJA.
            $total_pagar = ($dinero_cobrado + $expensas_total) - ($total_comision +$gasto_total);

            $obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);

            //REGISTRA MOVIMIENTO
            $obj_movimiento = new A2Movimientos();
            $obj_movimiento->fecha_movimiento = date('Y-m-d H:i:s');
            $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
            $obj_movimiento->id_liquidacion = 0;
            $obj_movimiento->observaciones = "Orden de Pago N° ".$model->id." Propietario:". $model->propietario->apellido . 
            ", " . $model->propietario->nombre;
            $obj_movimiento->comprobante_pago_unico = 0;
            $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
            $obj_movimiento->caja_id = $obj_caja->id_caja;            
            $obj_movimiento->tipo_registro = 'pago';
            $obj_movimiento->estado_pago_pendiente = '';
            $obj_movimiento->movimiento_tipo = 14;
            $obj_movimiento->monto=$total_pagar;
            if ($obj_movimiento->save()) {
                $obj_operacion_mov= new OperacionesMovimientos();
                $obj_operacion_mov->operacion_id=$model->id;
                $obj_operacion_mov->movimiento_id=$obj_movimiento->id_movimiento;
                $obj_operacion_mov->tipo_operacion="orden_pago";
                if($obj_operacion_mov->save()){
                    $arreglo['estado'] = 1;
                    $arreglo['mensaje'] = "Se guardo correctamente";
                    $linea = '<b>Se guardo correctamente</b><br/>';                    

                    $linea.=\yii\helpers\Html::a("<span style='font-size:20px' class='glyphicon glyphicon-print'></span>", 
                    ['liquidacionpagadas/comprobante-propietario',
                    'orden_id' =>$model->id],['title' => 'Imprimir comprobante de pago','target'=>'_blank']);
                    $arreglo['imprimir_comprobante'] = $linea;
                }else{
                    $arreglo['estado'] = 0;
                    $arreglo['mensaje_error'] = "No se guardo la operacion por movimiento.";
                }
            }else{
                print_r($obj_movimiento->getErrors());
                $arreglo['estado'] = 0;
                $arreglo['mensaje_error'] = "No se guardo el movimiento.";
            }

        }else{
            $arreglo['estado'] = 0;
            $arreglo['mensaje_error'] = "No se pudo actualizar la orden de pago.";
        }
        echo json_encode($arreglo);
        exit;

    }

    public function actionComprobantePropietario() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");
        require_once("../models/reportes/LiquidacionPropietario.php");
        require_once("../models/reportes/Propietario.php");
        require_once("../models/reportes/Propiedad.php");
        $orden_id = Yii::$app->request->get('orden_id');
		$model_detalle_gastos = LiqpagadasGastos::find()->where(['liquidacionpagadas_id' => $orden_id])->all();
        ob_start();
        require_once('../models/reportes/template_comprobante_liquidacion_propietario.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');    
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('historial_propiedad.pdf');
            exit;
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionComprobantePropietarioExcel() {
        ob_start();
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");
        require_once("../models/reportes/LiquidacionPropietario.php");
        require_once("../models/reportes/Propietario.php");
        require_once("../models/reportes/Propiedad.php");
        $orden_id = Yii::$app->request->get('orden_id');        
		$model_detalle_gastos = LiqpagadasGastos::find()->where(['liquidacionpagadas_id' => $orden_id])->all();
        ob_start();
        require_once('../models/reportes/excel/liquidacion_propietario.php');
        $content = ob_get_clean();
        echo $content;
        exit;        
    }

}
