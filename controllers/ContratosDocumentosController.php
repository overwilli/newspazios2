<?php

namespace app\controllers;

use Yii;
use app\models\ContratosDocumentos;
use app\models\ContratosDocumentosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plantillas;
use app\models\A2OperacionesInmobiliarias;
use app\models\TipoExpensas;
use app\models\CifrasEnLetras;
use HTML2PDF;
use yii\filters\AccessControl;


/**
 * ContratosDocumentosController implements the CRUD actions for ContratosDocumentos model.
 */
class ContratosDocumentosController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                //'only' => ['index', 'logout', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete','get-template-by-id',
						'imprimir-documento'],
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
    
    public function actionGetTemplateById() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;        
        $out=[];
        $id=Yii::$app->request->post('id');
        if (!is_null($id)) {
            $obj_cifras=new CifrasEnLetras();
            $row=Plantillas::find()->where(['id' => $id])->one();            
            $model_operacion=A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => Yii::$app->request->post('id_operacion')])->one();          
            $texto = $row['texto'];
            //-------CONTRATO
            $texto=str_replace("@dia_contrato", $model_operacion->dia_venc_mensual, $texto);
            $texto=str_replace("@mes_desde", $model_operacion->desde_mes, $texto);
            $texto=str_replace("@anio_desde", $model_operacion->desde_anio, $texto);
            $texto=str_replace("@mes_hasta", $model_operacion->hasta_mes, $texto);
            $texto=str_replace("@anio_hasta", $model_operacion->hasta_anio, $texto);
            $texto=str_replace("@anio_hasta", $model_operacion->hasta_anio, $texto);
            $texto=str_replace("@destino_contrato", $model_operacion->destino_contrato, $texto);
            if( $model_operacion->deposito_garantia==1){
                $texto=str_replace("@deposito_garantia_contrato", $obj_cifras->ValorEnLetras(
                    $model_operacion->deposito_monto,"")." ($ ".$model_operacion->deposito_monto.")", $texto);   
            }else{         
                $texto=str_replace("@deposito_garantia_contrato", 0, $texto);
            }
            $texto=str_replace("@meses_contrato", $model_operacion->obtener_plazo(), $texto);
            
            $texto=str_replace("@meses_letras_contrato", $obj_cifras->ValorEnLetras($model_operacion->obtener_plazo(),""), $texto);

            $fecha=new \Datetime($model_operacion->desde_anio."-".$model_operacion->desde_mes."-".$model_operacion->dia_venc_mensual);
            $fecha->add(new \DateInterval('P15D'));
            $ob_utils=new \app\models\Utils;

            $texto=str_replace("@dia_letras_contrato", $obj_cifras->ValorEnLetras($model_operacion->dia_venc_mensual,""), $texto);
            $texto=str_replace("@mes_letras_contrato", $ob_utils->mes_en_letras($fecha->format("d/m/Y")), $texto);
            $texto=str_replace("@anio_letras_contrato", $obj_cifras->ValorEnLetras($fecha->format("Y"),""), $texto);
            //-------CLIENTE
            $texto=str_replace("@apellido_nombre_cliente", $model_operacion->cliente->NOMBRE, $texto);
            $texto=str_replace("@dni_cliente", $model_operacion->cliente->DNI, $texto);
            $texto=str_replace("@direccion_cliente", $model_operacion->cliente->DIRECCION, $texto);
            $texto=str_replace("@telefono_cliente", $model_operacion->cliente->TELEFONO, $texto);
            $texto=str_replace("@barrio_cliente", $model_operacion->cliente->BARRIO, $texto);
            //-------LOCADOR
            if($model_operacion->locadorPropietario){
                $texto=str_replace("@apellido_nombre_locador", empty($model_operacion->locadorPropietario) ? '' 
                    : $model_operacion->locadorPropietario->apellido.", ".$model_operacion->locadorPropietario->nombre, $texto);
                $texto=str_replace("@dni_locador", $model_operacion->locadorPropietario->cuit, $texto);
                $texto=str_replace("@direccion_locador", $model_operacion->locadorPropietario->direccion, $texto);
                $texto=str_replace("@provincia_locador", $model_operacion->locadorPropietario->provincia, $texto);
            }          

            //-------INMUEBLE
            $texto=str_replace("@direccion_inmueble", $model_operacion->inmueble->direccion, $texto);
            //-------EXPENSAS
            if($model_operacion->tiene_expensas==0){
                $clausula="El Locatario toma a su cargo la instalación y el pago de los Servicios Eléctricos, Gas  y de los 
                servicios e impuestos de toda naturaleza relacionados con la explotación comercial que ejercerá. 
                El Locador se hará cargo del importe mensual correspondiente al pago del Servicio de Agua y 
                cloacas (Aguas de Santiago), de la Tasa Municipal por Retribución de Servicios y expensas.";
                $texto=str_replace("@clausula_expensa", $clausula, $texto);
            }else{
                if($model_operacion->tiene_expensas==1){
                    $total_expensas=TipoExpensas::find()->count();
                    $arreglo_expensas=explode(';',$model_operacion->expensas);
                    if(count($arreglo_expensas)==$total_expensas){
                        //TIENE TODAS LAS 
                        $clausula="El Locatario abonará al Locador el importe mensual correspondiente a los 
                        fines de que este último efectúe las gestiones de pago del Servicio de Agua y cloacas 
                        (Aguas de Santiago), de la Tasa Municipal por Retribución de Servicios, Impuesto 
                        Inmobiliario y Expensas. El Locatario además tomará su cargo la instalación y el 
                        pago de los Servicios Eléctricos, Gas  y de los servicios e impuestos de toda 
                        naturaleza relacionados con la explotación comercial que ejercerá.";
                        $texto=str_replace("@clausula_expensa", $clausula, $texto);
                    }else{
                        if(count($arreglo_expensas)==1){
                            if($arreglo_expensas[0]==2){//SI SOLO PAGA AGUA
                                $clausula="El Locatario abonará al Locador el importe mensual correspondiente 
                                a los fines de que este último efectúe las gestiones de pago del Servicio de Agua y 
                                cloacas (Aguas de Santiago). El Locatario además toma a su cargo la instalación y 
                                el pago de los Servicios Eléctricos, Gas  y de los servicios e impuestos de toda 
                                naturaleza relacionados con la explotación comercial que ejercerá.  El locador 
                                abonará la Tasa Municipal por Retribución de Servicios y expensas restantes.";
                                $texto=str_replace("@clausula_expensa", $clausula, $texto);
                            }
                        }else{
                            if(count($arreglo_expensas)==2){ //SI PAGA INMOBILIARIO Y MUNICIPAL                               
                                if($arreglo_expensas[0]==6 && $arreglo_expensas[1]==7 || 
                                    $arreglo_expensas[0]==7 && $arreglo_expensas[1]==6){
                                    
                                    $clausula="El Locatario abonará al Locador el importe mensual 
                                    correspondiente al impuesto inmobiliario provincial y la tasa municipal 
                                    por retribución de servicios. El Locatario además toma a su cargo la 
                                    instalación y el pago de los Servicios Eléctricos, Gas  y de los 
                                    servicios e impuestos de toda naturaleza relacionados con la explotación 
                                    comercial que ejercerá.  El locador abonará el servicio de Agua y Cloacas 
                                    (Aguas de Santiago).";
                                    $texto=str_replace("@clausula_expensa", $clausula, $texto);
                                }
                            }
                        }
                    }
                }
            }
            //IMPORTES A PAGAR SEGUN SEMESTRE
            $connection = Yii::$app->getDb();
            $model_pagos = $connection->createCommand("SELECT id_operacion,anio,mes,(SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
            AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=1) as A,
            (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
            AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=2) as B,
            (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
            AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=3) as X,
            (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
            AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=4) as D,
            (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
            AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=5) as C FROM a2_operaciones_items AS op_items
            WHERE op_items.id_operacion=".$model_operacion->id_operacion_inmobiliaria."
            GROUP BY anio,mes
            ORDER BY anio, mes")->queryAll();
            $formatter = \Yii::$app->formatter;
            $total_parcial=0;
            $i=0;
            $fecha_inicio="";
            $periodo_texto=null;
            foreach($model_pagos as $row){
                $total_parcial+=$row['A'] + $row['B'] + $row['X'] + $row['D'] + $row['C'];
                if($fecha_inicio==""){
                    $fecha_inicio=$row["mes"]."/".$row["anio"];
                }
                if($i==5){
                    $fecha_fin=$row["mes"]."/".$row["anio"];
                    $periodo_texto[]="____/{$fecha_inicio} al ____/{$fecha_fin} se pagará la suma de PESOS {$obj_cifras->ValorEnLetras($total_parcial, "")} ({$formatter->asCurrency($total_parcial)})";
                    $fecha_inicio="";
                    $fecha_fin="";
                    $total_parcial=0;
                }                
                $i++;
            }
            $i=1;
            foreach($periodo_texto as $item){
                $texto=str_replace("@periodo_importe_".$i, $item, $texto);
                $i++;
            }
            $out['texto']=$texto;
        }
        return $out;
    }

    /**
     * Lists all ContratosDocumentos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContratosDocumentosSearch();
        $param=Yii::$app->request->queryParams;        
        $param['ContratosDocumentosSearch']['operacion_inmobiliaria_id']=Yii::$app->request->get('id'); 
        $dataProvider = $searchModel->search($param);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContratosDocumentos model.
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
     * Creates a new ContratosDocumentos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContratosDocumentos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->operacion_inmobiliaria_id=Yii::$app->request->get('id');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ContratosDocumentos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
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
     * Deletes an existing ContratosDocumentos model.
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
     * Finds the ContratosDocumentos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContratosDocumentos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContratosDocumentos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionImprimirDocumento() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        //require_once('../models/reportes/template_clientes.php');
        $content = ob_get_clean();
        $model=$this->findModel(Yii::$app->request->get('id'));
        $content="<div style='padding-left:260px'><b>".$model->plantillas->titulo."</b></div>";
        $content.=$model->texto;
        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';
		$content="<style>
        page{
            padding:100px;
            text-align:justify;
        }
        </style><page><div style='margin:35px;margin-top:70px;'>{$content}</div></page>";
        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es', array(30,30,30,30));
//    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('impresion_clientes.pdf');
            exit;
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }
}
