<?php

namespace app\controllers;

set_time_limit(0);


use Yii;
use app\models\A2Liquidaciones;
use app\models\A2Caja;
use app\models\A2LiquidacionesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\A2Movimientos;
use app\models\A2ParametrosGlobales;
use app\models\A2OperacionesInmobiliarias;
use app\models\A2Facturacion;
use app\models\ImpresoraFiscal;
use app\models\PagosParciales;
use yii\data\SqlDataProvider;
use HTML2PDF;

/**
 * A2LiquidacionesController implements the CRUD actions for A2Liquidaciones model.
 */
class A2LiquidacionesController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index', 'index-mejorado', 'create', 'view', 'update', 'delete', 'create-by-inmueble',
                            'registrar-pago', 'calcular-intereses', 'calcular-intereses-expensas', 'liquidaciones-pagadas', 'borrar-pago-expensa',
                            'expensas-deuda', 'borrar-pago-liquidacion',
                            'registrar-pago-expensa', 'detalle-deuda-expensa', 'expensas-pagadas', 'imprimir-comprobante',
                            'listado-reimprimir-comprobante', 'reimprimir-comprobante', 'imprimir-expensas',
                            'morosos', 'morosos-liq-expensas', 'registrar-pago-parcial', 'imprimir-pago-parcial',
                            'imprimir-comprobante-expensas'
                        ],
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

    public function beforeAction($event)
    {
        if (($event->id == 'registrar-pago' || $event->id == 'registrar-pago-expensa' ||
            $event->id == 'borrar-pago-expensa' || $event->id == 'reimprimir-comprobante') && !A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja)) {
            throw new \yii\web\ForbiddenHttpException("No existe caja abierta para el usuario.");
        }
        if (isset(Yii::$app->user->identity)) {
            if (Yii::$app->user->identity->permisos == "intermedio" && ($event->id == 'liquidaciones-pagadas' ||
                $event->id == 'expensas-pagadas')) {
                throw new \yii\web\ForbiddenHttpException("No tiene permisos para acceder.");
            }
        }

        return parent::beforeAction($event);
    }

    /**
     * Lists all A2Liquidaciones models.
     * @return mixed
     */
    /*public function actionIndex() {
        $searchModel = new A2LiquidacionesSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        $param['A2LiquidacionesSearch']['estado'] = 'ACTIVO';

        $dataProvider = $searchModel->search($param);


        $param['A2LiquidacionesSearch']['estado'] = 'PENDIENTE';

        $dataProvider_pendientes = $searchModel->search($param);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProvider_pendientes' => $dataProvider_pendientes,
        ]);
    }*/

    /**
     * Lists all A2Liquidaciones models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new A2LiquidacionesSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';        
        $param['A2LiquidacionesSearch']['estado'] = 'ACTIVO';
        $searchModel->tipo_filtro = (isset($param['A2LiquidacionesSearch']['tipo_filtro'])) ? $param['A2LiquidacionesSearch']['tipo_filtro'] : '';
        $dataProvider = $searchModel->searchLiquidacionesExpensas($param);


        //$param['A2LiquidacionesSearch']['estado'] = 'PREIMPRESO';

        //$dataProvider_pendientes = $searchModel->search($param);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'dataProvider_pendientes' => $dataProvider_pendientes,
        ]);
    }

    public function actionListadoReimprimirComprobante()
    {
        $searchModel = new A2LiquidacionesSearch();
        $param = Yii::$app->request->queryParams;
        $param['A2LiquidacionesSearch']['estado'] = 'PAGADO';

        $dataProvider = $searchModel->searchLiquidacionesExpensasPagadas($param);

        return $this->render('listado-reimprimir-comprobante', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionImprimirComprobanteExpensas()
    {
        $plantilla = Yii::$app->request->get('plantilla');
        $id_liquidacion = 0;
        $id_operacion = Yii::$app->request->get('id_operacion');
        $monto = Yii::$app->request->get('monto');
        $monto_sin_iva = Yii::$app->request->get('monto_sin_iva');

        //$model_liq = $this->findModel($id_liquidacion);
        $model_operacion = A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => $id_operacion])->one();

        $this->layout = FALSE;
        if ($plantilla == "recibo_comun") {

            $numero_recibo = A2ParametrosGlobales::get_recibo_c();
            $concepto = " pago por período: Código: " .
                $model_operacion->cod_propiedad;


            A2Facturacion::grabar_respuesta_impresora($concepto, "recibo común", $monto, $id_liquidacion, $numero_recibo, "C");

            return $this->render('recibo_comun_expensas', [
                'monto' => $monto,
                //'model_liq' => $model_liq,
                'model_operacion' => $model_operacion,
                'numero_recibo' => $numero_recibo
            ]);
        }
    }

    public function actionImprimirComprobante()
    {
        $plantilla = Yii::$app->request->get('plantilla');
        $id_liquidacion = Yii::$app->request->get('id_liquidacion');
        $id_operacion = Yii::$app->request->get('id_operacion');
        $monto = Yii::$app->request->get('monto');
        $monto_sin_iva = Yii::$app->request->get('monto_sin_iva');

        $model_liq = $this->findModel($id_liquidacion);
        $model_operacion = A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => $id_operacion])->one();

        $this->layout = FALSE;
        if ($plantilla == "recibo_comun") {

            $numero_recibo = A2ParametrosGlobales::get_recibo_c();
            $concepto = " pago por período: " . $model_liq->liq_mes . "/" . $model_liq->liq_anio . " Código: " .
                $model_operacion->cod_propiedad;


            A2Facturacion::grabar_respuesta_impresora($concepto, "recibo común", $monto, $id_liquidacion, $numero_recibo, "C");

            return $this->render('recibo_comun', [
                'monto' => $monto,
                'model_liq' => $model_liq,
                'model_operacion' => $model_operacion,
                'numero_recibo' => $numero_recibo
            ]);
        }

        if ($plantilla == "factura_a") {

            $param['nombre'] = $model_operacion->cliente->NOMBRE;
            $param['direccion_cliente'] = $model_operacion->cliente->DIRECCION;
            $param['direccion_inmueble'] = $model_operacion->inmueble->direccion;
            $param['cuil'] = $model_operacion->cliente->CUIL;
            $param['periodo'] = $model_liq->liq_mes . "/" . $model_liq->liq_anio;
            $param['monto'] = $monto;

            $resultado = ImpresoraFiscal::factura_a($param);
            //A2Facturacion::grabar_respuesta_impresora(json_encode($resultado),"factura A", $monto, $id_liquidacion);
            A2Facturacion::grabar_respuesta_impresora($resultado, "factura A", $monto, $id_liquidacion);
            $this->layout = TRUE;
            return $this->render('factura-a');
        }

        if ($plantilla == "factura_b") {

            $param['nombre'] = $model_operacion->cliente->NOMBRE;
            $param['direccion_cliente'] = $model_operacion->cliente->DIRECCION;
            $param['direccion_inmueble'] = $model_operacion->inmueble->direccion;
            $param['dni'] = $model_operacion->cliente->DNI;
            $param['periodo'] = $model_liq->liq_mes . "/" . $model_liq->liq_anio;
            $param['monto'] = $monto;

            $resultado = ImpresoraFiscal::factura_b($param);
            //A2Facturacion::grabar_respuesta_impresora(json_encode($resultado),"factura B", $monto, $id_liquidacion);
            A2Facturacion::grabar_respuesta_impresora($resultado, "factura B", $monto, $id_liquidacion);
            $this->layout = TRUE;
            return $this->render('factura-b');
        }

        if ($plantilla == "plantilla_documento") {

            $numero_recibo = A2ParametrosGlobales::get_recibo_d();
            $concepto = " pago por período: " . $model_liq->liq_mes . "/" . $model_liq->liq_anio . " Código: " . $model_operacion->cod_propiedad;

            A2Facturacion::grabar_respuesta_impresora($concepto, "recibo común", $monto, $id_liquidacion, $numero_recibo, "D");

            return $this->render('plantilla_documento', [
                'monto' => $monto,
                'model_liq' => $model_liq,
                'model_operacion' => $model_operacion,
                'numero_recibo' => $numero_recibo
            ]);
        }
    }

    /**
     * Lists all A2Liquidaciones models.
     * @return mixed
     */
    public function actionExpensasDeuda()
    {
        $searchModel = new A2LiquidacionesSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        $param['A2LiquidacionesSearch']['estado'] = 'ACTIVO';

        $dataProvider = $searchModel->expensas_search($param);

        return $this->render('expensas_deuda', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all A2Liquidaciones models.
     * @return mixed
     */
    public function actionExpensasPagadas()
    {
        $searchModel = new A2LiquidacionesSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        //$param['A2LiquidacionesSearch']['estado'] = 'ACTIVO';

        $dataProvider = $searchModel->expensas_pagadas_search($param);

        return $this->render('expensas_pagadas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all A2Liquidaciones models.
     * @return mixed
     */
    public function actionLiquidacionesPagadas()
    {
        $searchModel = new A2LiquidacionesSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        $param['A2LiquidacionesSearch']['estado'] = 'PAGADO';

        $dataProvider = $searchModel->searchLiquidacionesExpensasPagadas($param);

        return $this->render('liquidacionespagadas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single A2Liquidaciones model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new A2Liquidaciones model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new A2Liquidaciones();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->crear_liquidaciones_periodo();
                return $this->render('create', [
                    'model' => $model,
                    'mensaje' => "Se guardo correctamente.",
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            //return $this->redirect(['view', 'id' => $model->id_liquidacion]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new A2Liquidaciones model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateByInmueble()
    {
        $model = new A2Liquidaciones();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->crear_liquidaciones_periodo();
                return $this->render('create_by_inmueble', [
                    'model' => $model,
                    'mensaje' => "Se guardo correctamente.",
                ]);
            } else {
                return $this->render('create_by_inmueble', [
                    'model' => $model,
                ]);
            }
            //return $this->redirect(['view', 'id' => $model->id_liquidacion]);
        } else {
            return $this->render('create_by_inmueble', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing A2Liquidaciones model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_liquidacion]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionBorrarPagoLiquidacion($id_liquidacion)
    {
        $model = $this->findModel($id_liquidacion);

        $mensaje_error = "";
        $mensaje_ok = "";
        if (Yii::$app->request->post()) {

            try {
                A2Movimientos::borrar_movimientos_pagos(Yii::$app->request->post('id_liquidacion'));
                A2Liquidaciones::borrar_pago_liquidacion(Yii::$app->request->post('id_liquidacion'));
                $bandera = false;
            } catch (Exception $ex) {
                $bandera = true;
            }
            if ($bandera) {
                $mensaje_error = "Ocurrio un error al borrar el pago";
                $mensaje_ok = "";
            } else {
                $mensaje_ok = "Se borro correctamente los pagos.";
                $mensaje_error = "";
            }
        }
        return $this->render('borrar_pago_liquidacion', [
            'model' => $model, 'mensaje_error' => $mensaje_error, "mensaje_ok" => $mensaje_ok
        ]);
    }

    public function actionBorrarPagoExpensa()
    {

        //$model = $this->findModel($id_liquidacion);
        $model = A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => Yii::$app->request->get('id_operacion')])->one();

        $mes = Yii::$app->request->get('mes');
        $year = Yii::$app->request->get('year');
        $monto = Yii::$app->request->get('monto');


        if (Yii::$app->request->post()) {
            $resultado_exp = A2Liquidaciones::obtener_expensas_pagadas($model->cliente->NOMBRE, $model->inmueble->direccion, $mes, $year);
            $bandera = FALSE;
            $mensaje = "";
            foreach ($resultado_exp as $row) {


                $fecha_hora = date("Y-m-d H:i:s");


                //$id_unico = A2ParametrosGlobales::get_comprobante_unico();
                $obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);

                //REGISTRA MOVIMIENTO
                $obj_movimiento = new A2Movimientos();
                $obj_movimiento->fecha_movimiento = $fecha_hora;
                $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
                $obj_movimiento->id_liquidacion = 0;
                $obj_movimiento->observaciones = "<b>Mov. Generado por borrado:</b><br/>" . $row['observaciones'];
                //$obj_movimiento->comprobante_pago_unico = $id_unico;
                $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
                $obj_movimiento->caja_id = $obj_caja->id_caja;
                $obj_movimiento->movimiento_tipo = 9;
                $obj_movimiento->monto = $row['monto'];
                $obj_movimiento->tipo_registro = 'pago';
                $obj_movimiento->estado_pago_pendiente = '';

                if ($obj_movimiento->save()) {
                    A2Movimientos::anular_movimiento($row['id_movimiento']);
                    A2Liquidaciones::actualizar_expensa_impago(Yii::$app->request->get('id_operacion'), $mes, $year);
                } else {
                    $bandera = TRUE;
                }
            }
            if ($bandera) {
                $mensaje_error = "Ocurrio un error al borrar el pago";
                $mensaje_ok = "";
            } else {
                $mensaje_ok = "Se borro correctamente los pagos.";
                $mensaje_error = "";
            }
            return $this->render('borrar_pago', [
                'model' => $model, 'mes' => $mes, 'year' => $year, 'monto' => $monto, 'mensaje_error' => $mensaje_error, "mensaje_ok" => $mensaje_ok
            ]);
        } else {
            return $this->render('borrar_pago', [
                'model' => $model, 'mes' => $mes, 'year' => $year, 'monto' => $monto
            ]);
        }
    }

    public function actionRegistrarPago($id_liquidacion)
    {
        $model = $this->findModel($id_liquidacion);
        //$model->scenario = "update";

        if (Yii::$app->request->post()) {
            $transaction = \Yii::$app->db->beginTransaction();
            $cantidad = A2Movimientos::existen_operacion_periodo(Yii::$app->user->identity->nikname, $id_liquidacion);
            if ($cantidad == 0) {
                $fecha_hora = date("Y-m-d H:i:s");
                $model->monto_pagado = Yii::$app->request->post('monto');
                $model->fecha_pago = $fecha_hora;
                $model->usuario = Yii::$app->user->identity->nikname;
                if (Yii::$app->request->post('registrar_pago_pendiente') == 'pendiente') {
                    $model->estado = "PREIMPRESO";
                } else {
                    $model->estado = "PAGADO";
                }
                if ($model->save()) {
                    $observaciones = "Cliente: " . $model->operacionInmobiliaria->cliente->NOMBRE . " <br> Inmueble: " .
                        $model->operacionInmobiliaria->inmueble->direccion . "<br> Período:" .
                        $model->liq_mes . "/" . $model->liq_anio;
                    $id_unico = A2ParametrosGlobales::get_comprobante_unico();
                    $obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);

                    //REGISTRA MOVIMIENTO
                    $obj_movimiento = new A2Movimientos();
                    $obj_movimiento->fecha_movimiento = $fecha_hora;
                    $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
                    $obj_movimiento->id_liquidacion = $id_liquidacion;
                    $obj_movimiento->observaciones = $observaciones . " -comprob. unico: " . $id_unico;
                    $obj_movimiento->comprobante_pago_unico = $id_unico;
                    $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
                    $obj_movimiento->caja_id = $obj_caja->id_caja;
                    if (Yii::$app->request->post('registrar_pago_pendiente') == 'pendiente') {
                        $obj_movimiento->tipo_registro = 'pago_pendiente';
                        $obj_movimiento->estado_pago_pendiente = 'pendiente';
                    } else {
                        $obj_movimiento->tipo_registro = 'pago';
                        $obj_movimiento->estado_pago_pendiente = '';
                    }

                    $bandera = true;
                    $obj_movimiento1 = clone $obj_movimiento;
                    $obj_movimiento2 = clone $obj_movimiento;
                    $obj_movimiento3 = clone $obj_movimiento;
                    $obj_movimiento4 = clone $obj_movimiento;
                    $obj_movimiento5 = clone $obj_movimiento;
                    if (trim(Yii::$app->request->post('efectivo_monto')) != "") {

                        $obj_movimiento1->movimiento_tipo = 1;
                        $obj_movimiento1->monto = Yii::$app->request->post('efectivo_monto');
                        if (!$obj_movimiento1->save()) {
                            $bandera = false;
                        }
                    }
                    if (trim(Yii::$app->request->post('cheque_monto')) != "") {

                        $obj_movimiento2->movimiento_tipo = 2;
                        $obj_movimiento2->monto = Yii::$app->request->post('cheque_monto');
                        $obj_movimiento2->observaciones = $observaciones . " -comprob. unico: {$id_unico}";
                        if (!$obj_movimiento2->save()) {
                            $bandera = false;
                        }
                    }
                    if (trim(Yii::$app->request->post('deposito_monto')) != "") {

                        $obj_movimiento3->movimiento_tipo = 3;
                        $obj_movimiento3->monto = Yii::$app->request->post('deposito_monto');
                        $obj_movimiento3->observaciones = $observaciones . " <br/>comprob. unico: {$id_unico}" .
                            " <b> Fecha de Deposito:" . Yii::$app->request->post('fecha_deposito') . "</b>";
                        if (!$obj_movimiento3->save()) {
                            $bandera = false;
                        }
                    }
                    if (trim(Yii::$app->request->post('retencion_monto')) != "") {

                        $obj_movimiento4->movimiento_tipo = 4;
                        $obj_movimiento4->monto = Yii::$app->request->post('retencion_monto');
                        if (!$obj_movimiento4->save()) {
                            $bandera = false;
                        }
                    }
                    if (trim(Yii::$app->request->post('mora_monto')) != "") {

                        $obj_movimiento5->movimiento_tipo = 7;
                        $obj_movimiento5->monto = Yii::$app->request->post('mora_monto');
                        if (!$obj_movimiento5->save()) {
                            $bandera = false;
                        }
                    }
                    if ($bandera) {
                        A2ParametrosGlobales::actualizar_id_unico();
                        $transaction->commit();
                        $mensaje = "Se registro correctamente el pago.";
                        //return $this->redirect(['index']);
                        return $this->render('pago_satisfactorio_registrado', [
                            'model' => $model, 'mensaje' => $mensaje
                        ]);
                    } else {
                        $mensaje_error = "Ocurrio un error al registrar el movimiento.";
                        return $this->render('registrar_pago', [
                            'model' => $model, 'mensaje_error' => $mensaje_error
                        ]);
                    }
                } else {
                    $transaction->rollBack();
                    $mensaje_error = "Ocurrio un error al actualizar la liquidación.";
                    return $this->render('registrar_pago', [
                        'model' => $model, 'mensaje_error' => $mensaje_error
                    ]);
                }
            } else {
                $transaction->rollBack();
                $mensaje_error = utf8_decode("La liquidación ya se encuentra pagada, en una operación anterior.");
                return $this->render('registrar_pago', [
                    'model' => $model, 'mensaje_error' => $mensaje_error
                ]);
            }
        } else {
            return $this->render('registrar_pago', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegistrarPagoParcial()
    {

        header('Content-Type: application/json');
        $arreglo['estado'] = 1;
        $liquidacion_id = Yii::$app->request->post('id_liquidacion');
        $model = $this->findModel($liquidacion_id);
        $monto = Yii::$app->request->post('monto');
        $mora_monto = Yii::$app->request->post('mora_monto');
        $retencion_monto = Yii::$app->request->post('retencion_monto');
        $deposito_monto = Yii::$app->request->post('deposito_monto');
        $fecha_deposito = Yii::$app->request->post('fecha_deposito');
        $efectivo_monto = Yii::$app->request->post('efectivo_monto');
        $cheque_monto = Yii::$app->request->post('cheque_monto');


        $total_pagado = \app\models\PagosParciales::total_pagado_by_liquidacion_id($liquidacion_id);
        $total_parcial = $efectivo_monto + $deposito_monto + $cheque_monto;
        $total_pagado_parcial = $total_pagado + ($efectivo_monto + $deposito_monto + $cheque_monto);
        if ($total_pagado_parcial <= $monto) {
            $model_pago_parcial = new \app\models\PagosParciales();
            $model_pago_parcial->liquidacion_id = $liquidacion_id;
            $model_pago_parcial->monto = $total_parcial;
            $model_pago_parcial->estado = 'pagado';
            $arreglo_forma_pago['efectivo_monto'] = $efectivo_monto;
            $arreglo_forma_pago['deposito_monto'] = $deposito_monto;
            $arreglo_forma_pago['cheque_monto'] = $cheque_monto;
            $arreglo_forma_pago['monto_total'] = $monto;
            $arreglo_forma_pago['mora_monto'] = $mora_monto;
            $arreglo_forma_pago['fecha_deposito'] = $fecha_deposito;
            $model_pago_parcial->data = json_encode($arreglo_forma_pago);
            if ($model_pago_parcial->save()) {
                $fecha_hora = date("Y-m-d H:i:s");

                $observaciones = "Cliente: " . $model->operacionInmobiliaria->cliente->NOMBRE . " <br> Inmueble: " .
                    $model->operacionInmobiliaria->inmueble->direccion . "<br> Período:" .
                    $model->liq_mes . "/" . $model->liq_anio;
                $id_unico = A2ParametrosGlobales::get_comprobante_unico();
                $obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);

                //REGISTRA MOVIMIENTO
                $obj_movimiento = new A2Movimientos();
                $obj_movimiento->fecha_movimiento = $fecha_hora;
                $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
                $obj_movimiento->id_liquidacion = $liquidacion_id;
                $obj_movimiento->observaciones = $observaciones . " -comprob. unico: " . $id_unico;
                $obj_movimiento->comprobante_pago_unico = $id_unico;
                $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
                $obj_movimiento->caja_id = $obj_caja->id_caja;
                $obj_movimiento->movimiento_tipo = 16;
                $obj_movimiento->monto = $total_parcial;
                $obj_movimiento->tipo_registro = 'pago';
                $obj_movimiento->estado_pago_pendiente = '';
                if ($obj_movimiento->save()) {
                    $arreglo['liq_cancelado'] = 0;
                    if ($total_pagado_parcial == $monto) {
                        $fecha_hora = date("Y-m-d H:i:s");
                        $model->monto_pagado = Yii::$app->request->post('monto');
                        $model->fecha_pago = $fecha_hora;
                        $model->usuario = Yii::$app->user->identity->nikname;
                        $model->estado = "PAGADO";
                        if ($model->save()) {
                            $arreglo['liq_cancelado'] = 1;
                        }
                    }
                    $arreglo['pago_parcial_id'] = $model_pago_parcial->id;
                    $arreglo['estado'] = 1;
                    $arreglo['mensaje_error'] = 'El pago fue realizado correctamente.';
                } else {
                    print_r($obj_movimiento->getErrors());
                }
            } else {
                $arreglo['estado'] = 0;
                $arreglo['mensaje_error'] = 'No se pudo guardar el pago parcial.';
            }
        } else {
            $arreglo['estado'] = 0;
            $arreglo['mensaje_error'] = 'El monto ingresado supera el total a cobrar.';
        }
        echo json_encode($arreglo);
        exit;
    }

    public function actionImprimirPagoParcial()
    {
        $liquidacion_id = Yii::$app->request->get('id_liquidacion');
        $pago_parcial_id = Yii::$app->request->get('pago_parcial_id');
        $liq_cancelado = Yii::$app->request->get('liq_cancelado');
        $model = $this->findModel($liquidacion_id);
        $model_pago_parcial = PagosParciales::find()->where(['id' => $pago_parcial_id])->one();
        $mensaje = "Se registro correctamente el pago.";
        //return $this->redirect(['index']);
        return $this->render('pago_satisfactorio_registrado', [
            'model' => $model, 'model_pago_parcial' => $model_pago_parcial, 'liq_cancelado' => $liq_cancelado,
            'mensaje' => $mensaje
        ]);
    }

    public function actionReimprimirComprobante($id_liquidacion)
    {
        $model = $this->findModel($id_liquidacion);
        //$model->scenario = "update";

        if (Yii::$app->request->post()) {
            $transaction = \Yii::$app->db->beginTransaction();
            $cantidad = A2Movimientos::existen_operacion_periodo(Yii::$app->user->identity->nikname, $id_liquidacion);
            if ($cantidad == 0) {
                $fecha_hora = date("Y-m-d H:i:s");
                $model->monto_pagado = Yii::$app->request->post('monto');
                $model->fecha_pago = $fecha_hora;
                $model->usuario = Yii::$app->user->identity->nikname;
                if (Yii::$app->request->post('registrar_pago_pendiente') == 'pendiente') {
                    $model->estado = "PENDIENTE";
                } else {
                    $model->estado = "PAGADO";
                }
                if ($model->save()) {
                    $observaciones = "Cliente: " . $model->operacionInmobiliaria->cliente->NOMBRE . " <br> Inmueble: " .
                        $model->operacionInmobiliaria->inmueble->direccion . "<br> Período:" .
                        $model->liq_mes . "/" . $model->liq_anio;
                    $id_unico = A2ParametrosGlobales::get_comprobante_unico();
                    $obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);

                    //REGISTRA MOVIMIENTO
                    $obj_movimiento = new A2Movimientos();
                    $obj_movimiento->fecha_movimiento = $fecha_hora;
                    $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
                    $obj_movimiento->id_liquidacion = $id_liquidacion;
                    $obj_movimiento->observaciones = $observaciones . " -comprob. unico: " . $id_unico;
                    $obj_movimiento->comprobante_pago_unico = $id_unico;
                    $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
                    $obj_movimiento->caja_id = $obj_caja->id_caja;
                    if (Yii::$app->request->post('registrar_pago_pendiente') == 'pendiente') {
                        $obj_movimiento->tipo_registro = 'pago_pendiente';
                        $obj_movimiento->estado_pago_pendiente = 'pendiente';
                    } else {
                        $obj_movimiento->tipo_registro = 'pago';
                        $obj_movimiento->estado_pago_pendiente = '';
                    }

                    $bandera = true;
                    $obj_movimiento1 = clone $obj_movimiento;
                    $obj_movimiento2 = clone $obj_movimiento;
                    $obj_movimiento3 = clone $obj_movimiento;
                    $obj_movimiento4 = clone $obj_movimiento;
                    $obj_movimiento5 = clone $obj_movimiento;
                    if (trim(Yii::$app->request->post('efectivo_monto')) != "") {

                        $obj_movimiento1->movimiento_tipo = 1;
                        $obj_movimiento1->monto = Yii::$app->request->post('efectivo_monto');
                        if (!$obj_movimiento1->save()) {
                            $bandera = false;
                        }
                    }
                    if (trim(Yii::$app->request->post('cheque_monto')) != "") {

                        $obj_movimiento2->movimiento_tipo = 2;
                        $obj_movimiento2->monto = Yii::$app->request->post('cheque_monto');
                        $obj_movimiento2->observaciones = $observaciones . " -comprob. unico: {$id_unico}";
                        if (!$obj_movimiento2->save()) {
                            $bandera = false;
                        }
                    }
                    if (trim(Yii::$app->request->post('deposito_monto')) != "") {

                        $obj_movimiento3->movimiento_tipo = 3;
                        $obj_movimiento3->monto = Yii::$app->request->post('deposito_monto');
                        $obj_movimiento3->observaciones = $observaciones . " <br/>comprob. unico: {$id_unico}" .
                            " <b> Fecha de Deposito:" . Yii::$app->request->post('fecha_deposito') . "</b>";
                        if (!$obj_movimiento3->save()) {
                            $bandera = false;
                        }
                    }
                    if (trim(Yii::$app->request->post('retencion_monto')) != "") {

                        $obj_movimiento4->movimiento_tipo = 4;
                        $obj_movimiento4->monto = Yii::$app->request->post('retencion_monto');
                        if (!$obj_movimiento4->save()) {
                            $bandera = false;
                        }
                    }
                    if (trim(Yii::$app->request->post('mora_monto')) != "") {

                        $obj_movimiento5->movimiento_tipo = 7;
                        $obj_movimiento5->monto = Yii::$app->request->post('mora_monto');
                        if (!$obj_movimiento5->save()) {
                            $bandera = false;
                        }
                    }
                    if ($bandera) {
                        A2ParametrosGlobales::actualizar_id_unico();
                        $transaction->commit();
                        $mensaje = "Se registro correctamente el pago.";
                        //return $this->redirect(['index']);
                        return $this->render('pago_satisfactorio_registrado', [
                            'model' => $model, 'mensaje' => $mensaje
                        ]);
                    } else {
                        $mensaje_error = "Ocurrio un error al registrar el movimiento.";
                        return $this->render('registrar_pago', [
                            'model' => $model, 'mensaje_error' => $mensaje_error
                        ]);
                    }
                } else {
                    $transaction->rollBack();
                    $mensaje_error = "Ocurrio un error al actualizar la liquidación.";
                    return $this->render('registrar_pago', [
                        'model' => $model, 'mensaje_error' => $mensaje_error
                    ]);
                }
            } else {
                $transaction->rollBack();
                $mensaje_error = "La liquidación ya se encuentra pagada, en una operación anterior.";
                return $this->render('registrar_pago', [
                    'model' => $model, 'mensaje_error' => $mensaje_error
                ]);
            }
        } else {
            return $this->render('reimprimir-comprobante', [
                'model' => $model,
            ]);
        }
    }

    public function actionDetalleDeudaExpensa()
    {
        $this->layout = FALSE;
        $arreglo_periodo = explode("/", Yii::$app->request->post('periodo'));
        $operacion_id = Yii::$app->request->post('operacion_id');

        $model = A2Liquidaciones::detalle_deuda_expensa($operacion_id, $arreglo_periodo[0], $arreglo_periodo[1]);
        return $this->render('detalle-deuda-expensas', [
            'model' => $model, 'operacion_id' => $operacion_id, 'arreglo_periodo' => $arreglo_periodo
        ]);
    }

    public function actionRegistrarPagoExpensa($id_operacion)
    {
        //$model = $this->findModel($id_liquidacion);

        $model = A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => $id_operacion])->one();

        //$model->scenario = "update";

        if (Yii::$app->request->post()) {
            $transaction = \Yii::$app->db->beginTransaction();
            //$cantidad = A2Movimientos::existen_operacion_periodo(Yii::$app->user->identity->nikname, $id_liquidacion);
            if (TRUE) {
                $fecha_hora = date("Y-m-d H:i:s");
                $arreglo_periodo = explode("/", Yii::$app->request->post('periodos_deuda'));

                $observaciones = "Cliente: " . $model->cliente->NOMBRE . " <br> Inmueble: " .
                    $model->inmueble->direccion . "<br> Período:" .
                    $arreglo_periodo[0] . "/" . $arreglo_periodo[1];

                $bonificado = Yii::$app->request->post('bonificado');
                if (isset($bonificado)) {
                    $observaciones .= " - Bonificado (" . $bonificado . ")";
                }

                $id_unico = A2ParametrosGlobales::get_comprobante_unico();
                $obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);

                //REGISTRA MOVIMIENTO
                $obj_movimiento = new A2Movimientos();
                $obj_movimiento->fecha_movimiento = $fecha_hora;
                $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
                $obj_movimiento->id_liquidacion = 0;
                $obj_movimiento->observaciones = $observaciones . " -comprob. unico: " . $id_unico;
                $obj_movimiento->comprobante_pago_unico = $id_unico;
                $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
                $obj_movimiento->caja_id = $obj_caja->id_caja;
                $obj_movimiento->movimiento_tipo = 11;
                $obj_movimiento->monto = Yii::$app->request->post('monto_total');
                $obj_movimiento->tipo_registro = 'pago';
                $obj_movimiento->estado_pago_pendiente = '';

                $data = null;

                if (trim(Yii::$app->request->post('efectivo_monto')) != "") {
                    $data[] = ['movimiento_tipo' => 1, 'monto' => Yii::$app->request->post('efectivo_monto')];
                }
                if (trim(Yii::$app->request->post('cheque_monto')) != "") {
                    $data[] = ['movimiento_tipo' => 2, 'monto' => Yii::$app->request->post('cheque_monto')];
                }
                if (trim(Yii::$app->request->post('deposito_monto')) != "") {
                    $data[] = [
                        'movimiento_tipo' => 3, 'monto' => Yii::$app->request->post('deposito_monto'),
                        'fecha_deposito' => Yii::$app->request->post('fecha_deposito')
                    ];
                }
                if (trim(Yii::$app->request->post('retencion_monto')) != "") {
                    $data[] = ['movimiento_tipo' => 4, 'monto' => Yii::$app->request->post('retencion_monto')];
                }
                if (trim(Yii::$app->request->post('mora_monto')) != "") {
                    $data[] = ['movimiento_tipo' => 7, 'monto' => Yii::$app->request->post('mora_monto')];
                }
                if (count($data) > 0) {
                    $obj_movimiento->data = json_encode($data);
                }
                //print_r($obj_movimiento);
                //exit;
                if ($obj_movimiento->save()) {
                    //REGISTRA MOVIMIENTO
                    $obj_movimiento = new A2Movimientos();
                    $obj_movimiento->fecha_movimiento = $fecha_hora;
                    $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
                    $obj_movimiento->id_liquidacion = 0;
                    $obj_movimiento->observaciones = "Interés por Mora: " . $observaciones . " -comprob. unico: " . $id_unico;
                    $obj_movimiento->comprobante_pago_unico = $id_unico;
                    $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
                    $obj_movimiento->caja_id = $obj_caja->id_caja;
                    $obj_movimiento->movimiento_tipo = 7;
                    $obj_movimiento->monto = Yii::$app->request->post('monto_interes');
                    $obj_movimiento->tipo_registro = 'pago';
                    $obj_movimiento->estado_pago_pendiente = '';

                    if ($obj_movimiento->save()) {
                    }

                    A2Liquidaciones::actualizar_deuda_expensa($id_operacion, $arreglo_periodo[0], $arreglo_periodo[1], $id_unico);
                    A2ParametrosGlobales::actualizar_id_unico();
                    $transaction->commit();
                    $mensaje_ok = "Se guardo correctamente el pago";
                    return $this->render('registrar_pago_deuda', [
                        'model' => $model, 'pago_expensa' => 1, 'mensaje_ok' => $mensaje_ok, 
                        'monto_total'=>Yii::$app->request->post('monto_total'),
                        'fecha_pago'=>$fecha_hora,
                        'id_operacion'=>$id_operacion,
                        'mensaje_error' => ""
                    ]);
                } else {
                    $transaction->rollBack();
                    $mensaje_error = "Ocurrio un error al registrar el movimiento.";
                    print_r($obj_movimiento->getErrors());
                    return $this->render('registrar_pago_deuda', [
                        'model' => $model, 'mensaje_error' => $mensaje_error, 'mensaje_ok' => ""
                    ]);
                }
            } else {
                $transaction->rollBack();
                $mensaje_error = "La liquidación ya se encuentra pagada, en una operación anterior.";
                return $this->render('registrar_pago_deuda', [
                    'model' => $model, 'mensaje_error' => $mensaje_error
                ]);
            }
        } else {
            $mes = Yii::$app->request->get('mes');
            $year = Yii::$app->request->get('year');
            return $this->render('registrar_pago_deuda', [
                'model' => $model, 'mes' => $mes, 'year' => $year
            ]);
        }
    }

    public function actionCalcularIntereses()
    {
        //Yii::$app->response->format = Response::FORMAT_JSON;      

        header('Content-Type: application/json');

        $model_liq = $this->findModel(Yii::$app->request->post('id_liquidacion'));
        $arreglo_montos['estado'] = 0;
        if ($model_liq) {

            $fecha = Yii::$app->request->post('fecha_deposito');
            $fecha = explode("/", $fecha);
            $fecha = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];

            $dias_mora = A2Liquidaciones::obtener_dias_mora($model_liq->id_liquidacion, $fecha);
            $arreglo_montos = A2Liquidaciones::Calcular_Monto_Periodo($model_liq->id_operacion, $model_liq->liq_anio, $model_liq->liq_mes, $dias_mora);
            $arreglo_montos['formateado_interes'] = \Yii::$app->formatter->asCurrency($arreglo_montos['interes']);
            $arreglo_montos['formateado_monto'] = \Yii::$app->formatter->asCurrency($arreglo_montos['monto']);
            $arreglo_montos['dias_mora'] = $dias_mora;
            $arreglo_montos['estado'] = 1;
        }
        echo json_encode($arreglo_montos);
        exit;
    }

    public function actionCalcularInteresesExpensas()
    {
        //Yii::$app->response->format = Response::FORMAT_JSON;      

        header('Content-Type: application/json');

        $model_liq = \app\models\A2Liquidaciones::find()->where([
            'id_operacion' =>
            Yii::$app->request->post('operacion_id'), 'liq_anio' => Yii::$app->request->post('anio'),
            'liq_mes' => Yii::$app->request->post('mes')
        ])->one();
        $model_operacion = \app\models\A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => Yii::$app->request->post('operacion_id')])->one();
        $arreglo_montos['estado'] = 0;
        if ($model_liq) {
            $fecha = Yii::$app->request->post('fecha_deposito');
            $fecha = explode("/", $fecha);
            $fecha = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];

            $dias_atraso = \app\models\A2Liquidaciones::obtener_dias_mora($model_liq->id_liquidacion, $fecha, TRUE);

            $interes_por_dia = $model_operacion->interes_dia_mora;

            $model = A2Liquidaciones::detalle_deuda_expensa(
                Yii::$app->request->post('operacion_id'),
                Yii::$app->request->post('mes'),
                Yii::$app->request->post('anio')
            );

            $total = 0;
            foreach ($model as $row) {
                $total += $row['importe'];
            }

            $interes_por_mora = (($dias_atraso * $interes_por_dia) * $total) / 100;

            $bonificado_interes_por_mora = 0;
            $bonificado_total = 0;
            if (!$model_operacion->tiene_expensas) {
                $bonificado_interes_por_mora = $interes_por_mora;
                $bonificado_total = $total;
            }
            $arreglo_montos['bonificado'] = $bonificado_total + $bonificado_interes_por_mora;
            $arreglo_montos['monto_interes'] = $interes_por_mora;
            $arreglo_montos['monto_total'] = ($total + $interes_por_mora) - ($bonificado_total + $bonificado_interes_por_mora);
            $arreglo_montos['monto_total_sin_interes'] = $total;
            $arreglo_montos['dias_atraso'] = $dias_atraso;
            $arreglo_montos['formateado_total_sin_interes'] = \Yii::$app->formatter->asCurrency($total);
            $arreglo_montos['formateado_bonificado'] = \Yii::$app->formatter->asCurrency($arreglo_montos['bonificado']);
            $arreglo_montos['formateado_monto_interes'] = \Yii::$app->formatter->asCurrency($arreglo_montos['monto_interes']);
            $arreglo_montos['formateado_monto_total'] = \Yii::$app->formatter->asCurrency($arreglo_montos['monto_total']);
            $arreglo_montos['estado'] = 1;
        }
        echo json_encode($arreglo_montos);
        exit;
    }

    /**
     * Deletes an existing A2Liquidaciones model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the A2Liquidaciones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return A2Liquidaciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = A2Liquidaciones::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImprimirExpensass()
    {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        //require_once('../models/reportes/template_clientes.php');
        //require_once('../models/reportes/template_liq_expensas1.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');
            //    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML("prueba", isset($_GET['vuehtml']));
            $html2pdf->Output('impresion_clientes.pdf');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionImprimirExpensas()
    {

        $operacion_id = Yii::$app->request->get('operacion_id');
        $mes = Yii::$app->request->get('mes');
        $year = Yii::$app->request->get('year');
        $model = A2Liquidaciones::detalle_deuda_expensa($operacion_id, $mes, $year);


        ob_start();
        require_once('../models/reportes/template_liq_expensas.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';



        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');
            //    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->output('estado_cuenta.pdf');
            exit;
            //var_dump(headers_list());
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionMorosos()
    {
        $count = Yii::$app->db->createCommand(
            "SELECT count(*) FROM (SELECT t1.id_liquidacion,t1.id_operacion,liq_anio,liq_mes,
        a2_noticias.direccion,a2_clientes.NOMBRE,'ALQUILER' AS tipo,0 as deuda FROM(SELECT * FROM a2_liquidaciones WHERE a2_liquidaciones.fecha_pago='0000-00-00 00:00:00'
        UNION
        SELECT DISTINCT a2_liquidaciones.* FROM a2_liquidaciones LEFT JOIN  a2_movimientos ON a2_liquidaciones.id_liquidacion=a2_movimientos.id_liquidacion 
        WHERE (tipo_registro='pago_pendiente'AND estado_pago_pendiente='pendiente'))
        AS t1 
        LEFT JOIN a2_operaciones_inmobiliarias ON 
                        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=t1.id_operacion
        LEFT JOIN a2_noticias ON
                        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_clientes ON
                        a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        WHERE a2_operaciones_inmobiliarias.estado='ACTIVO' 
        
        UNION 
        
        SELECT 0,deuda_expensas.operacion_id,deuda_expensas.`year`,deuda_expensas.`mes`,a2_noticias.direccion,a2_clientes.NOMBRE,
        'EXPENSAS' AS tipo, deuda_expensas.deuda FROM a2_noticias LEFT JOIN a2_operaciones_inmobiliarias ON
                        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                        LEFT JOIN a2_clientes ON
                         a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                        LEFT JOIN deuda_expensas ON
                         a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
        WHERE fecha_ope<>'' AND deuda_expensas.deuda>0 GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,deuda_expensas.year 
        ) AS T2 WHERE T2.tipo LIKE :tipo AND direccion LIKE :direccion AND NOMBRE LIKE :cliente ORDER BY direccion,liq_anio,liq_mes",
            [
                ':tipo' => '%' . Yii::$app->request->get('concepto', '') . '%',
                ':direccion' => '%' . Yii::$app->request->get('direccion', '') . '%',
                ':cliente' => '%' . Yii::$app->request->get('cliente', '') . '%'
            ]
        )->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT * FROM (SELECT t1.id_liquidacion,t1.id_operacion,liq_anio,liq_mes,a2_noticias.direccion,
            a2_clientes.NOMBRE,'ALQUILER' AS tipo,0 AS deuda FROM(SELECT * FROM a2_liquidaciones WHERE a2_liquidaciones.fecha_pago='0000-00-00 00:00:00'
            UNION
            SELECT DISTINCT a2_liquidaciones.* FROM a2_liquidaciones LEFT JOIN  a2_movimientos ON a2_liquidaciones.id_liquidacion=a2_movimientos.id_liquidacion 
            WHERE (tipo_registro='pago_pendiente'AND estado_pago_pendiente='pendiente'))
            AS t1 
            LEFT JOIN a2_operaciones_inmobiliarias ON 
                            a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=t1.id_operacion
            LEFT JOIN a2_noticias ON
                            a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
            LEFT JOIN a2_clientes ON
                            a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
            WHERE a2_operaciones_inmobiliarias.estado='ACTIVO' 
            
            UNION 
            
            SELECT 0,deuda_expensas.operacion_id,deuda_expensas.`year`,deuda_expensas.`mes`,a2_noticias.direccion,
            a2_clientes.NOMBRE,'EXPENSAS' AS tipo, deuda_expensas.deuda FROM a2_noticias LEFT JOIN a2_operaciones_inmobiliarias ON
                            a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                            LEFT JOIN a2_clientes ON
                             a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                            LEFT JOIN deuda_expensas ON
                             a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
            WHERE fecha_ope<>'' AND deuda_expensas.deuda>0 GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,deuda_expensas.year 
            ) AS T2 WHERE T2.tipo LIKE :tipo AND direccion LIKE :direccion AND NOMBRE LIKE :cliente ORDER BY direccion,liq_anio,liq_mes",
            'params' => [
                ':tipo' => '%' . Yii::$app->request->get('concepto') . '%',
                ':direccion' => '%' . Yii::$app->request->get('direccion', '') . '%',
                ':cliente' => '%' . Yii::$app->request->get('cliente', '') . '%'
            ],
            'totalCount' => $count,
            //            'sort' => [
            //                'attributes' => [
            //                    'age',
            //                    'name' => [
            //                        'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
            //                        'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
            //                        'default' => SORT_DESC,
            //                        'label' => 'Name',
            //                    ],
            //                ],
            //            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('morosos', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMorososLiqExpensas()
    {
        ob_start();
        $query = "SELECT * FROM (SELECT t1.id_liquidacion,t1.id_operacion,liq_anio,liq_mes,
        a2_noticias.direccion,a2_clientes.NOMBRE,'ALQUILER' AS tipo,0 as deuda FROM(SELECT * FROM a2_liquidaciones WHERE a2_liquidaciones.fecha_pago='0000-00-00 00:00:00'
        UNION
        SELECT DISTINCT a2_liquidaciones.* FROM a2_liquidaciones LEFT JOIN  a2_movimientos ON a2_liquidaciones.id_liquidacion=a2_movimientos.id_liquidacion 
        WHERE (tipo_registro='pago_pendiente'AND estado_pago_pendiente='pendiente'))
        AS t1 
        LEFT JOIN a2_operaciones_inmobiliarias ON 
                        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=t1.id_operacion
        LEFT JOIN a2_noticias ON
                        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_clientes ON
                        a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        WHERE a2_operaciones_inmobiliarias.estado='ACTIVO' 
        
        UNION 
        
        SELECT 0,deuda_expensas.operacion_id,deuda_expensas.`year`,deuda_expensas.`mes`,a2_noticias.direccion,a2_clientes.NOMBRE,
        'EXPENSAS' AS tipo, deuda_expensas.deuda FROM a2_noticias LEFT JOIN a2_operaciones_inmobiliarias ON
                        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                        LEFT JOIN a2_clientes ON
                         a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                        LEFT JOIN deuda_expensas ON
                         a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
        WHERE fecha_ope<>'' AND deuda_expensas.deuda>0 GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,deuda_expensas.year 
        ) AS T2 ORDER BY direccion,liq_anio,liq_mes";

        $resultado = Yii::$app->db->createCommand($query)->queryAll();

        require_once('../models/reportes/excel/morosos_liq_expensas.php');
        $content = ob_get_clean();
        echo $content;
        exit;
    }
}
