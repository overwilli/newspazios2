<?php

namespace app\controllers;

use Yii;
use app\models\A2Caja;
use app\models\A2CajaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\A2Movimientos;
use app\models\MovimientoInmueble;

/**
 * A2CajaController implements the CRUD actions for A2Caja model.
 */
class A2CajaController extends Controller {

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
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'inicializar-caja', 'arqueo-caja',
                            'cerrar-caja', 'registrar-movimiento', 'imprimir-gasto'],
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

    public function beforeAction($event) {

        if (empty(Yii::$app->user->identity->numero_caja) || !is_numeric(Yii::$app->user->identity->numero_caja)) {
            throw new \yii\web\ForbiddenHttpException("El usuario no tiene asignado un numero de caja. Debe realizarlo en la opcion del menú mantenimiento,
            y en la sub-opción asignar caja. Si ya le asigno una caja al usuario, cierre sesion y vuelva a iniciar.");
        }

        if (($event->id == 'registrar-pago' || $event->id == 'cerrar-caja' || 
			$event->id =='registrar-movimiento') && !A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja)) {
            throw new \yii\web\ForbiddenHttpException("No existe caja abierta para el usuario.");
        }

        return parent::beforeAction($event);
    }

    public function actionArqueoCaja() {
        $model_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);
        if (!$model_caja) {
            $model_caja_cerrada = A2Caja::obtener_caja_cerrada(Yii::$app->user->identity->numero_caja, date('Y-m-d'));
//            var_dump($model_caja);exit;

            if ($model_caja_cerrada) {
                throw new \yii\web\ForbiddenHttpException("La caja fue cerrada en el día de la fecha " . date('d/m/Y') . ",hora " . $model_caja_cerrada->cierre_hora . " por el usuario " . $model_caja_cerrada->cierre_usuario . " y no puede ser inicializada.");
            } else {
                return $this->redirect(['inicializar-caja']);
            }
        }
        $param[':numero_caja'] = Yii::$app->user->identity->numero_caja;
        $param[':fecha_movimiento'] = $model_caja->inicio_fecha;

        if($model_caja->inicio_fecha!=date("Y-m-d")){
            $ultimo_movimiento = A2Movimientos::find()->where("numero_caja=:numero_caja AND "
                        . "(fecha_movimiento>=:fecha_movimiento)", $param)->
                        orderBy(['fecha_movimiento' => SORT_DESC, 'id_movimiento' => SORT_DESC])->one();
            
            $param[':fecha_movimiento'] = $ultimo_movimiento->fecha_movimiento;
            $movimiento_ultima_caja = A2Movimientos::find()->where("numero_caja=:numero_caja AND "
                        . "(fecha_movimiento=:fecha_movimiento)", $param)->
                        orderBy(['fecha_movimiento' => SORT_DESC, 'id_movimiento' => SORT_DESC])->all();
            $indice_vector = 0;
            while ($indice_vector <= 17) {
                $vector1[$indice_vector] = 0;
                $indice_vector++;
            }
            foreach ($movimiento_ultima_caja as $row) {
                if ($row['tipo_registro'] != 'pago_pendiente') {
                    $vector1[$row["movimiento_tipo"]]+=$row["monto"];
                }
            }
            $efectivo_en_caja_temp = ($model_caja->inicio + $vector1[1] + $vector1[2] + $vector1[4] + $vector1[3] + $vector1[8] +
                            $vector1[12] + $vector1[11] + $vector1[13]) - ($vector1[6] + $vector1[5] + $vector1[9] +
                            $vector1[10] );
            $model_caja->inicio=$efectivo_en_caja_temp;
            
        }
        $fecha_arqueo = Yii::$app->request->get('fecha_arqueo');
        $param[':fecha_movimiento'] = $model_caja->inicio_fecha;
        
        $query = "";
        if (!empty($model_caja->cierre_fecha)) {
            $param[':fecha_movimiento_hasta'] = $model_caja->cierre_fecha;
            $query = "fecha_movimiento<=:fecha_movimiento_hasta";
        }
        $model_movimiento = A2Movimientos::find()->where("numero_caja=:numero_caja AND "
                        . "(fecha_movimiento>=:fecha_movimiento {$query})", $param)->orderBy(['fecha_movimiento' => SORT_DESC, 'id_movimiento' => SORT_DESC])->all();

        return $this->render('arqueo-caja', [
                    'model_movimiento' => $model_movimiento, 'model_caja' => $model_caja
        ]);
    }

    public function actionImprimirGasto() {
        $this->layout = false;
        $arreglo['fecha_movimiento'] = Yii::$app->request->get("fecha_movimiento");
        $arreglo['importe_movimiento'] = Yii::$app->request->get("importe_movimiento");
        $arreglo['tipo_movimiento'] = Yii::$app->request->get("tipo_movimiento");
        $arreglo['observaciones_ingreso_egreso'] = Yii::$app->request->get("observaciones");
        $arreglo['propiedad_id'] = Yii::$app->request->get("propiedad_id");
        $arreglo['usuario'] = Yii::$app->user->identity->nikname;
        return $this->render('imprimir-gasto', [
                    'datos' => $arreglo
        ]);
    }

    public function actionRegistrarMovimiento() {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $arreglo = [];
        $mensaje_error = "";
        if (Yii::$app->request->post()) {
            $bandera = true;
            if (Yii::$app->request->post('selectmovimientos') == 10) {
                $cajas_abiertas = Yii::$app->request->post('cajas_abiertas');
                if (empty($cajas_abiertas)) {
                    $mensaje_error = "Debe seleccionar una caja para realizar la transferencia.<br/>";
                    $bandera = false;
                }
            }
            if (!is_numeric(Yii::$app->request->post('importe_movimiento'))) {
                $mensaje_error.="Debe ingresar un importe.<br/>";
                $bandera = false;
            }
            $observaciones = Yii::$app->request->post('observaciones_ingreso_egreso');
            if (empty($observaciones)) {
                $mensaje_error.="Debe ingresar una observación para el movimiento.<br/>";
                $bandera = false;
            }
            if (Yii::$app->request->post('selectmovimientos') == 10) {
                $model_caja_abierta = A2Caja::obtener_caja_abierta(Yii::$app->request->post('cajas_abiertas'));
                if(!$model_caja_abierta){
                    $mensaje_error.=utf8_encode("Debe estar abierta la caja destinataria para poder realizar la transferencia.<br/>");
                    $bandera = false;
                }
            }
            $propiedad_id="";
            $operacion_id="";
			if (Yii::$app->request->post('selectmovimientos') == 8 || Yii::$app->request->post('selectmovimientos') == 6) {//INGRESOS VARIOS o GASTOS
				$operacion_id = Yii::$app->request->post('propiedad_id');
				if (!is_numeric($operacion_id)) {
					$operacion_id="";
				}else{
                    $model_op=\app\models\A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria'=>$operacion_id])->one();
                    if($model_op){
                        $propiedad_id=$model_op->cod_propiedad;
                    }
                }
			}
            if ($bandera) {
                $observaciones_ingreso_egreso = Yii::$app->request->post('observaciones_ingreso_egreso');

                if (Yii::$app->request->post('selectmovimientos') == 10) {
                    $model_caja_abierta = A2Caja::obtener_caja_abierta(Yii::$app->request->post('cajas_abiertas'));
                    $observaciones_ingreso_egreso.=" Transferencia de caja N&deg;:" .
                            Yii::$app->user->identity->numero_caja . "(" . Yii::$app->user->identity->nikname . ") a caja N&deg; " .
                            $cajas_abiertas . "({$model_caja_abierta->inicio_usuario})";
                }
                $transaction = \Yii::$app->db->beginTransaction();

                $obj_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);

                $obj_movimiento = new A2Movimientos();
                $obj_movimiento->fecha_movimiento = date('Y-m-d');
                $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
                $obj_movimiento->id_liquidacion = 0;
                $obj_movimiento->observaciones = $observaciones_ingreso_egreso;
                //$obj_movimiento->comprobante_pago_unico = $id_unico;
                $obj_movimiento->numero_caja = Yii::$app->user->identity->numero_caja;
                $obj_movimiento->caja_id = $obj_caja->id_caja;
                $obj_movimiento->movimiento_tipo = Yii::$app->request->post('selectmovimientos');
                $obj_movimiento->monto = Yii::$app->request->post('importe_movimiento');
                $obj_movimiento->tipo_registro = 'pago';
                $obj_movimiento->estado_pago_pendiente = '';
                if (Yii::$app->request->post('selectmovimientos') == 8 || Yii::$app->request->post('selectmovimientos') == 6) {//INGRESOS VARIOS
                    $obj_movimiento->propiedad_id = $propiedad_id;  
                    $obj_movimiento->operacion_id = $operacion_id;             
                }

                if ($obj_movimiento->save()) {										
			
                    if (Yii::$app->request->post('selectmovimientos') == 10) {
                        $model_caja_abierta = A2Caja::obtener_caja_abierta(Yii::$app->request->post('cajas_abiertas'));

                        $obj_movimiento = new A2Movimientos();
                        $obj_movimiento->fecha_movimiento = date('Y-m-d');
                        $obj_movimiento->usuario = Yii::$app->user->identity->nikname;
                        $obj_movimiento->id_liquidacion = 0;
                        $obj_movimiento->observaciones = $observaciones_ingreso_egreso;
                        //$obj_movimiento->comprobante_pago_unico = $id_unico;
                        $obj_movimiento->numero_caja = Yii::$app->request->post('cajas_abiertas');
                        $obj_movimiento->caja_id = $model_caja_abierta->id_caja;
                        $obj_movimiento->movimiento_tipo = 13;
                        $obj_movimiento->monto = Yii::$app->request->post('importe_movimiento');
                        $obj_movimiento->tipo_registro = 'pago';
                        $obj_movimiento->estado_pago_pendiente = '';
                        if ($obj_movimiento->save()) {
                            $transaction->commit();

                            $cadena = 'fecha_movimiento=' . date('Y-m-d') . '&usuario=' . Yii::$app->user->identity->nikname . '&tipo_movimiento=' .
                                    Yii::$app->request->post('selectmovimientos') . '&importe_movimiento=' .
                                    Yii::$app->request->post('importe_movimiento') . '&observaciones=' . $observaciones_ingreso_egreso;

                            $arreglo['mensaje'] = "<b>Se guardo correctamente el pago.</b><a href='" . Yii::getAlias('@web') . "?r=a2-caja/imprimir-gasto&{$cadena}' target='_blank'>
                            <span style='font-size:20px' class='glyphicon glyphicon-print'></span></a><br>";
                            $arreglo['estado'] = 1;
                        } else {
                            $transaction->rollBack();
                            $arreglo['mensaje_error'] = "Ocurrio un error al registrar el movimiento";
                            $arreglo['estado'] = 0;
                        }
                    } else {
                        
                        $transaction->commit();

                        $cadena = 'fecha_movimiento=' . date('Y-m-d') . '&usuario=' . Yii::$app->user->identity->nikname . '&tipo_movimiento=' .
                                Yii::$app->request->post('selectmovimientos') . '&importe_movimiento=' .
                                Yii::$app->request->post('importe_movimiento') . '&observaciones=' . 
                                $observaciones_ingreso_egreso."&propiedad_id=". $propiedad_id;

                        $arreglo['mensaje'] = "<b>Se guardo correctamente el pago.</b><a href='" . Yii::getAlias('@web') . "?r=a2-caja/imprimir-gasto&{$cadena}' target='_blank'>
                            <span style='font-size:20px' class='glyphicon glyphicon-print'></span></a><br>";
                        $arreglo['estado'] = 1;
                    }
                } else {
                    $transaction->rollBack();
                    
                    $arreglo['mensaje_error'] = "Ocurrio un error al registrar el movimiento";
                    $arreglo['estado'] = 0;
                }
            } else {
                $arreglo['mensaje_error'] = $mensaje_error;
                $arreglo['estado'] = 0;
            }
        }

        return $arreglo;
    }

    public function actionCerrarCaja() {
        $model_caja = A2Caja::find()->where("numero_caja=:numero_caja AND id_caja=:id_caja", [':id_caja' => Yii::$app->request->post("id_caja"), ':numero_caja' => Yii::$app->user->identity->numero_caja])->one();
        if ($model_caja && Yii::$app->request->post()) {


            $rendicion = Yii::$app->request->post("rendicion");

            $caja_dia_siguiente = Yii::$app->request->post("caja_dia_siguiente");
            $bandera = true;
            $mensaje_val="";
            if (!isset($rendicion)) {
                $mensaje_val = "-Debe ingresar el importe de rendicion. ";
                $bandera = FALSE;
            }
            if (!isset($caja_dia_siguiente)) {
                $mensaje_val.="-Debe ingresar el importe de la caja del dia siguiente";
                $bandera = FALSE;
            }
            if ($bandera) {


                $fecha_consulta = Yii::$app->request->post("fecha_consulta");
                $caja_inicio = Yii::$app->request->post(" caja_inicio");
                $cobranzas_efectivo = Yii::$app->request->post("cobranzas_efectivo");
                $intereses_mora = Yii::$app->request->post("intereses_mora");
                $cheques = Yii::$app->request->post("cheques");
                $depositos = Yii::$app->request->post("depositos");
                $retenciones = Yii::$app->request->post("retenciones");
                $ingresos_varios = Yii::$app->request->post("ingresos_varios");
                $gastos_varios = Yii::$app->request->post("gastos_varios");
                $supermercado = Yii::$app->request->post("supermercado");
                $caja_final = Yii::$app->request->post("caja_final");

                if ($rendicion == "")
                    $rendicion = 0;
                if ($caja_dia_siguiente == "")
                    $caja_dia_siguiente = 0;
                if ($caja_inicio == "")
                    $caja_inicio = 0;
                if ($cobranzas_efectivo == "")
                    $cobranzas_efectivo = 0;
                if ($intereses_mora == "")
                    $intereses_mora = 0;
                if ($cheques == "")
                    $cheques = 0;
                if ($depositos == "")
                    $depositos = 0;
                if ($retenciones == "")
                    $retenciones = 0;
                if ($ingresos_varios == "")
                    $ingresos_varios = 0;
                if ($gastos_varios == "")
                    $gastos_varios = 0;
                if ($supermercado == "")
                    $supermercado = 0;
                if ($caja_final == "")
                    $caja_final = 0;

                $caja_final_declarada = $caja_dia_siguiente + $rendicion;
                $sobrante_faltante = $caja_final_declarada - $caja_final;

                $model_caja->caja_final = $caja_final;
                $model_caja->caja_dia_siguiente = $caja_dia_siguiente;
                $model_caja->rendicion = $rendicion;
                $model_caja->sobrante_faltante = $sobrante_faltante;
                $model_caja->caja_cierre = $caja_dia_siguiente;
                $model_caja->cobranzas_efectivo = $cobranzas_efectivo;
                $model_caja->intereses_mora = $intereses_mora;
                $model_caja->cheques = $cheques;
                $model_caja->depositos = $depositos;
                $model_caja->retenciones = $retenciones;
                $model_caja->gastos_varios = $gastos_varios;
                $model_caja->ingresos_varios = $ingresos_varios;
                $model_caja->supermercado = $supermercado;
                $model_caja->cierre_fecha = date("Y-m-d");
                $model_caja->cierre_hora = date("H:i:s");
                $model_caja->cierre_usuario = Yii::$app->user->identity->nikname;
                if ($model_caja->save()) {
                    $caja = A2Caja::find()->where("numero_caja=:numero_caja AND id_caja=:id_caja", [':id_caja' => Yii::$app->request->post("id_caja"), ':numero_caja' => Yii::$app->user->identity->numero_caja])->one();
                    return $this->render('mensaje_cierre_caja', ['mensaje' => 'Se cerro correctamente la caja', 'caja' => $caja]);
                } else {
                    return $this->render('mensaje_cierre_caja', ['mensaje_error' => 'Ocurrio un error al cerra la caja']);
                }
            }else{                
                echo "<script type='text/javascript'>alert('".$mensaje_val."');location.href='".Yii::getAlias('@web') . "?r=a2-caja/arqueo-caja'</script>";
            }
        } else {
            return $this->render('mensaje_cierre_caja', ['mensaje_error' => 'No se pudo identificar la caja a cerrar.']);
        }
    }

    /**
     * Lists all A2Caja models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new A2CajaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single A2Caja model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new A2Caja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInicializarCaja() {
        $model = new A2Caja();
        if (Yii::$app->request->post()) {
            $model_caja = A2Caja::obtener_caja_abierta(Yii::$app->user->identity->numero_caja);
            if ($model_caja) {
                $row_ultima_caja = A2Caja::obtener_ultima_caja(Yii::$app->user->identity->numero_caja);
                $model->inicio = $row_ultima_caja->caja_cierre;
            } else {
                $model->inicio = 0;
            }
            $model->inicio_fecha = date("Y-m-d");
            $model->inicio_hora = date("H:i:s");
            $model->inicio_usuario = Yii::$app->user->identity->nikname;
            $model->numero_caja = Yii::$app->user->identity->numero_caja;
            if ($model->save()) {
                return $this->redirect(['arqueo-caja', 'fecha_arqueo' => date("d-m-Y")]);
            }
            return $this->render('inicializar', [
                        'model' => $model,
            ]);
            //return $this->redirect(['view', 'id' => $model->id_caja]);
        } else {
            return $this->render('inicializar', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new A2Caja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new A2Caja();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_caja]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing A2Caja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_caja]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing A2Caja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the A2Caja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return A2Caja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = A2Caja::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
