<?php

namespace app\controllers;

set_time_limit(0);

use Yii;
use app\models\A2Clientes;
use app\models\A2ClientesSearch;
use app\models\A2OperacionesInmobiliarias;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use HTML2PDF;
use yii\filters\AccessControl;

/**
 * A2ClientesController implements the CRUD actions for A2Clientes model.
 */
class A2ClientesController extends Controller {

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
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'get-cliente-by-id',
                            'get-clientes', 'imp-estado-cuenta','listado','imprimir-expensas'],
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
        if (Yii::$app->user->identity->permisos == "operador"   && ($event->id != 'create' && $event->id != 'index')) {
            throw new \yii\web\ForbiddenHttpException("No tiene permisos para acceder.");
        }

        return parent::beforeAction($event);
    }

    public function actionGetClientes($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $data = Yii::$app->db->createCommand('SELECT id_cliente as id, NOMBRE as text FROM a2_clientes WHERE estado=\'ACTIVO\' AND NOMBRE LIKE \'%'.$q.'%\'')->queryAll();
            
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => City::find($id)->name];
        }
        return $out;
    }
    
    public function actionGetClienteById() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        //$out=null;
        $id=Yii::$app->request->get('id_cliente');
        if (!is_null($id)) {
            $row=A2Clientes::find()->where(['id_cliente' => $id])->one();
            //$data = Yii::$app->db->createCommand('SELECT id_cliente as id, NOMBRE as text FROM a2_clientes WHERE NOMBRE LIKE \'%'.$q.'%\'')->queryAll();
            //print_r($row);
            $out['results'] = $row;
            //echo json_encode($row);
        }
        return $out;
    }

    /**
     * Lists all A2Clientes models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new A2ClientesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single A2Clientes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new A2Clientes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new A2Clientes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_cliente]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing A2Clientes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) { 
            return $this->redirect(['view', 'id' => $model->id_cliente]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
            
        }
    }

    /**
     * Deletes an existing A2Clientes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    

        
    public function actionDelete($id) {


        $model = new A2OperacionesInmobiliarias();
        $model=A2OperacionesInmobiliarias::find()->where(['cod_cliente' => $id, 'estado'=>'ACTIVO'])->one();
        //$model=A2OperacionesInmobiliarias::find()->where(["cod_cliente=:$id AND estado=:'ACTIVO'"]);

        if($model){
            throw new \yii\web\ForbiddenHttpException("No se puede borrar al cliente, tiene un contrato vigente.");
        }else{
            $this->findModel($id)->delete();
        }

             return $this->redirect(['index']);
    }

    /**
     * Finds the A2Clientes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return A2Clientes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {

        if (($model = A2Clientes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListado() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        require_once('../models/reportes/template_clientes.php');
		//require_once('../models/reportes/template_liq_expensas1.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');
//    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('impresion_clientes.pdf');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionImpEstadoCuenta($cliente_id, $nombre, $dni) {

        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");
		$model_cliente=$this->findModel($cliente_id);
        ob_start();
        require_once('../models/reportes/template_estado_cuenta_clientes.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');
//    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('estado_cuenta.pdf');
			exit;
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }
	
	public function actionImprimirExpensas() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        //require_once('../models/reportes/template_clientes.php');
		//require_once('../models/reportes/template_liq_expensas1.php');
        //$content = ob_get_clean();
		$content="expensas";
        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');
//    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('impresion_clientes.pdf');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }
	
	

}
