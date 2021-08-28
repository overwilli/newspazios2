<?php

namespace app\controllers;

use Yii;
use app\models\A2Noticias;
use app\models\A2NoticiasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use HTML2PDF;
use yii\filters\AccessControl;
use app\models\A2NoticiasImagenes;
use app\models\A2OperacionesInmobiliarias;
use app\models\A2OperacionesInmobiliariasSearch;
use app\models\Localidades;
use app\models\Provincias;
use app\models\InmueblesPropietarios;

/**
 * A2NoticiasController implements the CRUD actions for A2Noticias model.
 */
class A2NoticiasController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'inmuebles-disponibles','inmuebles-disponibles-excel','inmuebles-vencer',
                            'expensas', 'historial', 'historial-excel', 'localidades','renovacion-pendiente','imprimir','ver-imagenes',
                            'inmuebles-vencer-excel','inmuebles-renovar-excel','firmar-contrato','aprobar-expensas'],
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

    /*
    public function beforeAction($event) {
        if (isset(Yii::$app->user->identity)) {

            if (Yii::$app->user->identity->permisos == "operador" && ($event->id != 'create' && $event->id != 'index' && 
                $event->id != 'inmuebles-disponibles' && $event->id != 'view' && $event->id != 'imprimir')) {
                throw new \yii\web\ForbiddenHttpException("No tiene permisos para acceder.");
            }
        }
        return parent::beforeAction($event);
    }
    */

    /**
     * Lists all A2Noticias models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new A2NoticiasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(isset(Yii::$app->request->queryParams['A2NoticiasSearch']['tipo_filtro'])){
            $searchModel->tipo_filtro=Yii::$app->request->queryParams['A2NoticiasSearch']['tipo_filtro'];
        }
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRenovacionPendiente(){
        $id_operacion_inmob=Yii::$app->request->post('operacion_id');
        $model=new A2OperacionesInmobiliarias();
        $model->marcar_pendiente_renovacion($id_operacion_inmob);
        $arreglo['error']=0;
        $arreglo['mensaje']='Se actualizo correctamente el registro';
        echo json_encode($arreglo);
        exit;
    }

    public function actionFirmarContrato(){
        $id_operacion_inmob=Yii::$app->request->post('operacion_id');
        $model=new A2OperacionesInmobiliarias();
        $model->firmar_contrato($id_operacion_inmob);
        $arreglo['error']=0;
        $arreglo['mensaje']='Se marco como firmado el contrato';
        echo json_encode($arreglo);
        exit;
    }
	
	public function actionInmueblesDisponibles() {
        $searchModel = new A2NoticiasSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        ///$param['A2NoticiasSearch']['tipo_filtro'] = 'por_vencer';
        //$dataProvider_por_vencer = $searchModel->search($param); //Yii::$app->request->queryParams);
        $param['A2NoticiasSearch']['tipo_filtro'] = 'disponibles';
        $dataProvider_disponibles = $searchModel->search($param); //Yii::$app->request->queryParams);

        return $this->render('inmuebles_disponibles', [
                    'searchModel' => $searchModel,
                    'dataProvider_disponibles' => $dataProvider_disponibles,
                    //'dataProvider_por_vencer' => $dataProvider_por_vencer,
        ]);
    }

    public function actionInmueblesDisponiblesExcel() {
        $searchModel = new A2NoticiasSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        ///$param['A2NoticiasSearch']['tipo_filtro'] = 'por_vencer';
        //$dataProvider_por_vencer = $searchModel->search($param); //Yii::$app->request->queryParams);
        $param['A2NoticiasSearch']['tipo_filtro'] = 'disponibles';
        $dataProvider_disponibles = $searchModel->search($param); //Yii::$app->request->queryParams);

        $inmuebles_disponibles=$dataProvider_disponibles->query->all();
        
        require_once('../models/reportes/excel/inmuebles_disponibles.php');
        $content = ob_get_clean();
        echo $content;
        exit;
    }

    public function actionInmueblesVencer() {
        $searchModel = new A2NoticiasSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        $param['A2NoticiasSearch']['tipo_filtro'] = 'por_vencer';
        $dataProvider_por_vencer = $searchModel->search($param); //Yii::$app->request->queryParams);
        

        $searchModel_op_inmob = new A2OperacionesInmobiliariasSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        //$param['A2OperacionesInmobiliariasSearch']['tipo_filtro'] = 'por_vencer';
        $dataProvider_por_renovar = $searchModel_op_inmob->search($param); //Yii::$app->request->queryParams);

        /*$query = A2OperacionesInmobiliarias::find();
        $query->where("contrato_firmado=0 AND (a2_operaciones_inmobiliarias.estado='PENDIENTE' OR 
            a2_operaciones_inmobiliarias.estado='ACTIVO' OR a2_operaciones_inmobiliarias.estado='CONFIRMADO') ");
        $dataProvider_por_renovar = new yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    //'titulo' => SORT_ASC,
                ]
            ],
        ]);*/

        //$searchModel = new A2NoticiasSearch();
        //$param['A2NoticiasSearch']['tipo_filtro'] = 'por_renovar';
        //$dataProvider_por_renovar= $searchModel->search($param);

        //$param['A2NoticiasSearch']['tipo_filtro'] = 'disponibles';
        //$dataProvider_disponibles = $searchModel->search($param); //Yii::$app->request->queryParams);

        return $this->render('inmuebles_vencer', [
                    'searchModel' => $searchModel,
                    'searchModel_op_inmob' => $searchModel_op_inmob,
                    'dataProvider_por_renovar' => $dataProvider_por_renovar,
                    'dataProvider_por_vencer' => $dataProvider_por_vencer,
        ]);
    }

    public function actionInmueblesVencerExcel() {        
        
        ob_start();
        $searchModel = new A2NoticiasSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        $param['A2NoticiasSearch']['tipo_filtro'] = 'por_vencer';
        $dataProvider_por_vencer = $searchModel->search($param);
        $inmuebles_por_vencer=$dataProvider_por_vencer->query->all();
        
        require_once('../models/reportes/excel/inmuebles_vencer.php');
        $content = ob_get_clean();
        echo $content;
        exit;
    }

    public function actionInmueblesRenovarExcel() {                
        
        ob_start();
        $searchModel = new A2NoticiasSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        $param['A2NoticiasSearch']['tipo_filtro'] = 'por_renovar';
        $dataProvider_por_vencer = $searchModel->search($param);
        $inmuebles_por_renovar=$dataProvider_por_vencer->query->all();
        
        require_once('../models/reportes/excel/inmuebles_renovar.php');
        $content = ob_get_clean();
        echo $content;
        exit;
    }

    public function actionExpensas() {
        $searchModel = new A2NoticiasSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';
        $param['A2NoticiasSearch']['tiene_expensas'] = TRUE;
        $dataProvider = $searchModel->search($param);

        return $this->render('expensas', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAprobarExpensas() {
        $searchModel = new \app\models\OperacionesExpensasSearch();
        //$searchModel->operacion_id=$request->get('operacion_id');
        //$searchModel->inmuebles_id=$request->get('propiedad_id');
        if(Yii::$app->request->post()){
            $bandera=FALSE;
            if(isset($_POST['Guardar']) && $_POST['Guardar']=='Confirmar Pendientes'){
                if(isset($_POST['expensas_pendientes_grupo'])){
                    foreach($_POST['expensas_pendientes_grupo'] as $row){                        
                        $model_expensas=\app\models\OperacionesExpensas::find()->
                            where(['grupo_expensas_id'=>$row,'estado_reg'=>'PENDIENTE'])->all();
                        foreach($model_expensas as $row_op_exp){
                            $_POST['expensas_pendientes'][]=$row_op_exp->id;
                        }
                    }
                }
                //var_dump($_POST['expensas_pendientes']);exit;
                if(isset($_POST['expensas_pendientes'])){
                    $bandera=FALSE;
                    foreach($_POST['expensas_pendientes'] as $row){
                        
                    $model_item=\app\models\OperacionesExpensas::find()->where(['id'=>$row])->one();
                        if($model_item){
                            $model_item->estado_reg='ACTIVO';
                            if($model_item->save()){
                                $bandera=TRUE;
                            }
                        }
                    }
                }
            }
            
            if(isset($_POST['Anular']) && $_POST['Anular']=='Anular Seleccionados'){
                if(isset($_POST['expensas_pendientes_grupo'])){
                    foreach($_POST['expensas_pendientes_grupo'] as $row){                        
                        $model_expensas=\app\models\OperacionesExpensas::find()->
                            where(['grupo_expensas_id'=>$row,'estado_reg'=>'PENDIENTE'])->all();
                        foreach($model_expensas as $row_op_exp){
                            $_POST['expensas_pendientes'][]=$row_op_exp->id;
                        }
                    }
                }             
                if(isset($_POST['expensas_pendientes'])){
                    $bandera=FALSE;
                    foreach($_POST['expensas_pendientes'] as $row){
                        $model_item=\app\models\OperacionesExpensas::find()->where(['id'=>$row])->one();
                        if($model_item){
                            $model_item->estado_reg='ELIMINADO';
                            if($model_item->save()){
                                $bandera=TRUE;
                            }
                        }
                    }
                }
            }
            if($bandera){
                $mensaje_expensas="Se actualizo correctamente los datos.";
            }
        }
		$searchModel->estado_reg="PENDIENTE";
		$dataProvider_pendiente = $searchModel->search(Yii::$app->request->queryParams);

        $model_exp=new \app\models\OperacionesExpensas;
        $expensas_pendientes=$model_exp->obtener_expensas_pendientes_aprobar();
        $expensas_pendientes_grupo=$model_exp->obtener_expensas_pendientes_aprobar_grupo();

        return $this->render('aprobar_expensas', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider_pendiente,
                    'expensas_pendientes'=>$expensas_pendientes,
                    'expensas_pendientes_grupo'=>$expensas_pendientes_grupo
        ]);
    }

    /**
     * Displays a single A2Noticias model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {    
        $model_imagenes_inmuebles = A2NoticiasImagenes::find()->where(['id_noticia' => $id])->all();
        return $this->render('view', [
                    'model' => $this->findModel($id), 'model_imagenes_inmuebles' => $model_imagenes_inmuebles,
        ]);
    }

    public function actionVerImagenes($id) {
        $model_imagenes_inmuebles = A2NoticiasImagenes::find()->where(['id_noticia' => $id])->all();
        return $this->render('ver-imagenes', [
                    'model' => $this->findModel($id), 'model_imagenes_inmuebles' => $model_imagenes_inmuebles,
        ]);
    }
	
	/**
     * Displays a single A2Noticias model.
     * @param integer $id
     * @return mixed
     */
    public function actionImprimir($id) {
		//$this->layout=FALSE;
        $model_imagenes_inmuebles = A2NoticiasImagenes::find()->where(['id_noticia' => $id])->all();
        return $this->render('imprimir', [
                    'model' => $this->findModel($id), 'model_imagenes_inmuebles' => $model_imagenes_inmuebles,
        ]);
    }

    /**
     * Creates a new A2Noticias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new A2Noticias();
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirect(['view', 'id' => $model->id_noticia]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing A2Noticias model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {               
            return $this->redirect(['view', 'id' => $model->id_noticia]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing A2Noticias model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

     
    public function actionDelete($id) {
        $model=$this->findModel($id);//->delete();
		$model->estado_reg='ELIMINADO';
		if($model->save()){ //echo "se actualizo";
		}
		/*else{
			print_r($model->getErrors());exit;
		}*/
		
        return $this->redirect(['index']);
    }

    /**
     * Finds the A2Noticias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return A2Noticias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = A2Noticias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionHistorial($propiedad_id, $titulo) {

        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");
        require_once("../models/reportes/Expensas.php");
		
		$model_inmueble=$this->findModel($propiedad_id);
		
		$obj_localidad=Localidades::find()->where(['id'=>$model_inmueble->localidad_id])->one();
		$localidad="";
		if($obj_localidad){
			$localidad=$obj_localidad->nombre;
		}
		$obj_provincia=Provincias::find()->where(['id'=>$model_inmueble->provincia_id])->one();
		$provincia="";
		if($obj_provincia){
			$provincia=$obj_provincia->nombre;
		}
		$model_inmueble_prop=InmueblesPropietarios::find()->where(['inmueble_id'=>$propiedad_id])->all();
		
        ob_start();
        require_once('../models/reportes/template_historial_propiedad.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');
//    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('historial_propiedad.pdf');
            exit;
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionHistorialExcel($propiedad_id, $titulo) {

        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");
        require_once("../models/reportes/Expensas.php");
		
		$model_inmueble=$this->findModel($propiedad_id);
		
		$obj_localidad=Localidades::find()->where(['id'=>$model_inmueble->localidad_id])->one();
		$localidad="";
		if($obj_localidad){
			$localidad=$obj_localidad->nombre;
		}
		$obj_provincia=Provincias::find()->where(['id'=>$model_inmueble->provincia_id])->one();
		$provincia="";
		if($obj_provincia){
			$provincia=$obj_provincia->nombre;
		}
		$model_inmueble_prop=InmueblesPropietarios::find()->where(['inmueble_id'=>$propiedad_id])->all();
		
        ob_start();
        require_once('../models/reportes/excel/historial_propiedad.php');
        $content = ob_get_clean();
        echo $content;
        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        /* Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

          try {
          $html2pdf = new HTML2PDF('P', 'A4', 'es');
          //    $html2pdf->setModeDebug();
          $html2pdf->setDefaultFont('Arial');
          $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
          $html2pdf->Output('historial_propiedad.pdf');
          } catch (HTML2PDF_exception $e) {
          echo $e;
          exit;
          } */
    }

    public function actionLocalidades($id) {

        $object = \app\models\Localidades::find();
        $rows = $object->leftJoin('departamentos', '`departamentos`.`id` = `localidades`.`departamentos_id`')
                        ->where(['departamentos.provincias_id' => $id])->all();
        echo "<option>Selecione una localidad</option>";

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                echo "<option value='$row->id'>$row->nombre</option>";
            }
        } else {
            echo "<option>No existen localidades</option>";
        }
    }

}
