<?php

namespace app\controllers;

use Yii;
use app\models\A2OperacionesInmobiliarias;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;

use app\models\A2OperacionesItems;
use yii\filters\AccessControl;
use app\models\CambiosOperaciones;
use app\models\InmueblesPropietarios;
use app\models\A2Noticias;
use HTML2PDF;

/**
 * A2OperacionesInmobiliariasController implements the CRUD actions for A2OperacionesInmobiliarias model.
 */
class A2OperacionesInmobiliariasController extends Controller
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
                        'actions' => [
                            'index', 'create', 'view', 'update', 'delete', 'periodos-cobro',
                            'confirmar-periodos', 'nota', 'todos-pagare', 'pagare-mes', 'planilla-periodos-excel'
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

    /**
     * Lists all A2OperacionesInmobiliarias models.
     * @return mixed
     */
    public function actionIndex($propiedad_id)
    {

        $query = A2OperacionesInmobiliarias::find()->where(['cod_propiedad' => $propiedad_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'query' => A2OperacionesInmobiliarias::find(),
            //$query->where('cod_propiedad=:cod_propiedad'),
            //'params' => [':cod_propiedad' => $cod_propiedad],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionPeriodosCobro($propiedad_id)
    {
        $count = Yii::$app->db->createCommand('SELECT id_operacion,anio,mes,(SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=1) as A,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=2) as B,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=3) as X,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=4) as D,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=5) as C FROM a2_operaciones_items AS op_items
      WHERE op_items.id_operacion=:id_operacion
      GROUP BY anio,mes
      ORDER BY anio, mes', [':id_operacion' => Yii::$app->request->post('id_operacion')])->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => 'SELECT id_operacion,anio,mes,(SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=1) as A,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=2) as B,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=3) as X,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=4) as D,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=5) as C FROM a2_operaciones_items AS op_items
      WHERE op_items.id_operacion=:id_operacion
      GROUP BY anio,mes
      ORDER BY anio, mes',
            'params' => [':id_operacion' => Yii::$app->request->post('id_operacion')],
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

        return $this->render('periodos-cobro', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 
     */

    /**
     * Displays a single A2OperacionesInmobiliarias model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->expensas = explode(';', $model->expensas);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new A2OperacionesInmobiliarias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionCreate()
    {
        $model = new A2OperacionesInmobiliarias();
        if (Yii::$app->request->get('renovar') == 1) {
            $model = $this->findModel(Yii::$app->request->get('id'));

            $model->id_operacion_inmobiliaria = NULL;
            $model->setIsNewRecord(true);
            $model->estado_renovacion = 'RENOVADO';

        }
     
        if ($model->load(Yii::$app->request->post())) {
            $expensas = Yii::$app->request->post('A2OperacionesInmobiliarias')['expensas'];
            if (!empty($expensas) && count($expensas) > 0) {
                $model->expensas = implode(';', $expensas);

            } else {
                $model->expensas = "";
            }

            if ($model->save()) {
                Yii::$app->db->createCommand(
                    "DELETE FROM llaves WHERE inmueble_id=:inmueble_id",
                    [':inmueble_id' => $model->cod_propiedad]
                )->execute();
                return $this->redirect(['view', 'id' => $model->id_operacion_inmobiliaria]);
            }
            $model->expensas = explode(';', $model->expensas);
            print_r($model->getErrors());

        } else {
            $propetarios = InmueblesPropietarios::find()->where(
                ['inmueble_id' => Yii::$app->request->get('propiedad_id')]
            )->all();
            $i = -1;
            foreach ($propetarios as $fila) {
                $i++;
                if ($i == 0) {
                    $model->locador = $fila->propietario_id;
                }
                if ($i == 1) { 
                    $model->locador_1 = $fila->propietario_id;
                }
                if ($i == 2) {
                    $model->locador_2 = $fila->propietario_id;
                }
            }
        }

       
        
        $model->cod_propiedad = Yii::$app->request->get('propiedad_id');
        $model->desde_mes = date('m');
        $model->desde_mes = str_replace("0", "", $model->desde_mes);
        $model->desde_anio = date('Y');
        if (date('m') == 12) {
            $model->hasta_mes = 1;
            $model->hasta_anio = date('Y') + 1;
            print_r($model->getErrors());

        } else {
            $model->hasta_mes = date('m') + 1;
            $model->hasta_mes = str_replace("0", "", $model->hasta_mes);
            $model->hasta_anio = date('Y');
        }
        //$model->hasta_mes=date('m');
        //$model->hasta_mes = str_replace("0", "", $model->hasta_mes);
        //$model->hasta_anio=date('Y');
    
        return $this->render('create', [
            'model' => $model,
        ]);
    
       
     

    }

    /**
     * Updates an existing A2OperacionesInmobiliarias model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $mensaje_periodos = Yii::$app->request->get('mensaje_periodos');

        $model = $this->findModel($id);

        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM (SELECT anio,mes FROM a2_operaciones_items AS op_items
      WHERE op_items.id_operacion=:id_operacion
      GROUP BY anio,mes
      ORDER BY anio, mes) AS T1', [':id_operacion' => $id])->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => 'SELECT id_operacion,anio,mes,' . $model->excento . ' as excento,(SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=1 AND op.estado=\'ACTIVO\') as A,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=2 AND op.estado=\'ACTIVO\') as B,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=3 AND op.estado=\'ACTIVO\') as X,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=4 AND op.estado=\'ACTIVO\') as D,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=5 AND op.estado=\'ACTIVO\') as C FROM a2_operaciones_items AS op_items
      WHERE op_items.id_operacion=:id_operacion AND op_items.estado=\'ACTIVO\'
      GROUP BY anio,mes
      ORDER BY anio, mes',
            'params' => [':id_operacion' => $id],
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
            'pagination' => false,
            /*'pagination' => [
                'pageSize' => 20,
            ],*/
        ]);

        $models_items = A2OperacionesItems::obtener_items_pendientes($id);

        $model_co = CambiosOperaciones::find()->where(['operacion_id' => $model->id_operacion_inmobiliaria, 'estado' => 'pendiente'])->one();

        if ($model->load(Yii::$app->request->post())) {
            $expensas = Yii::$app->request->post('A2OperacionesInmobiliarias')['expensas'];
            if (!empty($expensas) && count($expensas) > 0) {
                $model->expensas = implode(';', $expensas);
            } else {
                $model->expensas = "";
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id_operacion_inmobiliaria]);
            }
        }
        $model->expensas = explode(';', $model->expensas);
        $tab=Yii::$app->request->get('tab');
        //print_r($model->getErrors());            
        return $this->render('update', [
            'model' => $model, "dataprovider_periodos" => $dataProvider, 'models_items' => $models_items,
            'mensaje_periodos' => $mensaje_periodos, 'model_co' => $model_co,
            'tab'=>(isset($tab)?$tab:'')
        ]);
    }

    public function actionPagareMes($id, $anio, $mes)
    {
        $model = $this->findModel($id);

        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM (SELECT anio,mes FROM a2_operaciones_items AS op_items
      WHERE op_items.id_operacion=:id_operacion AND anio=:anio AND mes=:mes
      GROUP BY anio,mes
      ORDER BY anio, mes) AS T1', [':id_operacion' => $id, ':anio' => $anio, 'mes' => $mes])->queryScalar();

        $sql_query = 'SELECT id_operacion,anio,mes,' . $model->excento . ' as excento,            
        (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
        AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=4 AND op.estado=\'ACTIVO\') as D
        FROM a2_operaciones_items AS op_items
        WHERE op_items.id_operacion=:id_operacion AND op_items.estado=\'ACTIVO\' AND anio=:anio AND mes=:mes
        GROUP BY anio,mes
        ORDER BY anio, mes';

        $resultado = Yii::$app->db->createCommand($sql_query, [':id_operacion' => $id, ':anio' => $anio, 'mes' => $mes])->queryAll();
        ob_start();
        require_once('../models/reportes/template_pagare.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(15, 15, 15, 15));
            //    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('pagares.pdf');
            exit;
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionTodosPagare($id)
    {

        $model = $this->findModel($id);

        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM (SELECT anio,mes FROM a2_operaciones_items AS op_items
      WHERE op_items.id_operacion=:id_operacion
      GROUP BY anio,mes
      ORDER BY anio, mes) AS T1', [':id_operacion' => $id])->queryScalar();

        $sql_query = 'SELECT id_operacion,anio,mes,' . $model->excento . ' as excento,            
        (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
        AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=4 AND op.estado=\'ACTIVO\') as D
        FROM a2_operaciones_items AS op_items
        WHERE op_items.id_operacion=:id_operacion AND op_items.estado=\'ACTIVO\'
        GROUP BY anio,mes
        ORDER BY anio, mes';

        $resultado = Yii::$app->db->createCommand($sql_query, [':id_operacion' => $id])->queryAll();
        ob_start();
        require_once('../models/reportes/template_pagare.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(15, 15, 15, 15));
            //    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('pagares.pdf');
            exit;
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionPlanillaPeriodosExcel($id)
    {
        $model = $this->findModel($id);

        $cantidad_meses = Yii::$app->db->createCommand(
            'SELECT COUNT(*) FROM (SELECT anio,mes FROM a2_operaciones_items 
        AS op_items WHERE op_items.id_operacion=:id_operacion GROUP BY anio,mes ORDER BY anio, mes) AS T1',
            [':id_operacion' => $id]
        )->queryScalar();

        $sql_query = 'SELECT id_operacion,anio,mes,' . $model->excento . ' as excento,(SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=1 AND op.estado=\'ACTIVO\') as A,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=2 AND op.estado=\'ACTIVO\') as B,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=3 AND op.estado=\'ACTIVO\') as X,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=4 AND op.estado=\'ACTIVO\') as D,
      (SELECT SUM(monto) FROM a2_operaciones_items as op WHERE op.id_operacion=op_items.id_operacion
      AND op.anio=op_items.anio AND op.mes=op_items.mes AND op.id_factura=5 AND op.estado=\'ACTIVO\') as C FROM a2_operaciones_items AS op_items
      WHERE op_items.id_operacion=:id_operacion AND op_items.estado=\'ACTIVO\'
      GROUP BY anio,mes
      ORDER BY anio, mes';

        $resultado = Yii::$app->db->createCommand($sql_query, [':id_operacion' => $id])->queryAll();
        ob_start();


        require_once('../models/reportes/excel/planilla_periodos.php');
        $content = ob_get_clean();
        echo $content;
        exit;
    }

    /**
     * Deletes an existing A2OperacionesInmobiliarias model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

           
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $model = $this->findModel($id);
        //$model->estado = 'ELIMINADO';
        if ($model) {
            $model->actualizar_estado($id, 'ELIMINADO');
        }
        return $this->redirect(['a2-operaciones-inmobiliarias/index', 'propiedad_id' => $model->cod_propiedad]);
    }

/*
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $model = $this->findModel($id);
        //$model->estado = 'ELIMINADO';
        if ($model->estado = 'ACTIVO') {
            //$model->actualizar_estado($id, 'ELIMINADO');
            throw new \yii\web\ForbiddenHttpException("No se puede eliminar la propiedad, tiene un contrato vigente.");

        }else{
            $model->actualizar_estado($id, 'ELIMINADO');
        }
        return $this->redirect(['a2-operaciones-inmobiliarias/index', 'propiedad_id' => $model->cod_propiedad]);
    }
    */

    /**
     * Finds the A2OperacionesInmobiliarias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return A2OperacionesInmobiliarias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = A2OperacionesInmobiliarias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionConfirmarPeriodos()
    {
        $mensaje_periodos = "";

        if (isset($_POST['Guardar']) && $_POST['Guardar'] == 'Confirmar Pendientes') {
            if (isset($_POST['cambio_operacion_id'])) {
                $model_co = CambiosOperaciones::find()->where(['id' => $_POST['cambio_operacion_id'], 'estado' => 'pendiente'])->one();
                if ($model_co) {
                    A2OperacionesInmobiliarias::renovar_contrato(
                        $_POST['operacion_id'],
                        $model_co->desde_mes,
                        $model_co->desde_anio,
                        $model_co->hasta_mes,
                        $model_co->hasta_anio
                    );
                    $model_co->estado = "aplicado";
                    if ($model_co->save()) {
                    }
                }
            }
            if (isset($_POST['periodos_pendientes_id'])) {
                $bandera = FALSE;
                foreach ($_POST['periodos_pendientes_id'] as $row) {
                    $model_item = A2OperacionesItems::find()->where(['id_item' => $row])->one();
                    if ($model_item) {
                        $model_item->estado = 'ACTIVO';
                        if ($model_item->save()) {
                            $bandera = TRUE;
                        }
                        //$arreglo_1=$model_item->getFirstError();                        
                        $mensaje_periodos .= $model_item->getFirstError('mes') . "<br/>";
                    }
                }
            }
        } elseif (isset($_POST['Anular']) && $_POST['Guardar'] == 'Anular Seleccionados') {
            if (isset($_POST['periodos_pendientes_id'])) {
                $bandera = FALSE;
                foreach ($_POST['periodos_pendientes_id'] as $row) {

                    $model_item = A2OperacionesItems::find()->where(['id_item' => $row])->one();
                    if ($model_item) {
                        $model_item->estado = 'ELIMINADO';
                        if ($model_item->save()) {
                            $bandera = TRUE;
                        }
                    }
                }
            }
        }
        if ($bandera) {
            $mensaje_periodos = "Se actualizo correctamente los datos.";
        } else {
            $mensaje_periodos .= "No se agregaron los per&iacute;odos.";
        }
        return $this->redirect([
            'update', 'propiedad_id' => $_POST['propiedad_id'], 'id' => $_POST['operacion_id'],
            'mensaje_periodos' => utf8_decode($mensaje_periodos),
            'tab'=>'periodos'
        ]);
        //[operacion_id] => 242 [periodos_pendientes_id] => Array ( [0] => 15580 [1] => 15581 [2] => 15582 ) [Guardar] => Confirmar Pendientes

    }

    /**
     * Displays a single A2OperacionesInmobiliarias model.
     * @param integer $id
     * @return mixed
     */
    public function actionNota($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->grabar_nota($model->id_operacion_inmobiliaria, $model->nota);
            $model = $this->findModel($id);
            return $this->redirect(['a2-noticias/inmuebles-vencer']);
        }
        return $this->render('nota', [
            'model' => $model,
        ]);
    }

    /*public function actionFirmante(id);
    {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
           
            $aux_id_ope = Yii::$app->request->post('A2OperacionesInmobiliarias')['id_operacion_inmobiliaria'];
            $aux_id_cli = Yii::$app->request->post('A2OperacionesInmobiliarias')['cod_cliente'];
            $aux_cod_prop = Yii::$app->request->post('A2OperacionesInmobiliarias')['cod_propiedad'];
        }    

        $aux_array = [];
        $aux_id_ope = $model->id_operacion_inmobiliaria;
        //$aux_id_cli = $model->cod_cliente;
        //$aux_cod_prop = $model->cod_propiedad;

        $aux_array = ['willy',$aux_id_ope, $aux_id_cli, $aux_cod_prop];
        print_r($aux_array);
        die();

        //Yii::$app->db->createCommand()->batchInsert('a2firmante',['nombre','id_cliente','id_prop','id_ope' ],$aux_array);

        Yii::$app->db->createCommand()->insert('a2firmante', ['nombre','id_cliente','id_prop','id_ope' ],$aux_array);


    }*/
}
