<?php

namespace app\controllers;

use Yii;
use app\models\AuditoriaContratos;
use app\models\AuditoriaContratosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * AuditoriaContratosController implements the CRUD actions for AuditoriaContratos model.
 */
class AuditoriaContratosController extends Controller
{
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
                        'actions' => ['index','procesar-contratos'],
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

    public function actionProcesarContratos() {
        //SE DETERMINA LA FECHA A PROCESAR
        $d = new \DateTime('first day of this month');
        $d->sub(new \DateInterval('P1D'));
        $fecha_hasta=$d->format('Y-m-d');
        /*$query="SELECT count(*) as cantidad FROM auditoria_contratos WHERE fecha_contrato='{$fecha_hasta}'";
        $connection = Yii::$app->getDb();
        $cantidad = $connection->createCommand($query)->queryScalar();
        if($cantidad==0){*/
            //SE BUSCA TODOS LOS CONTRATOS ACTIVOS CON FECHA DE VENCIMIENTO HASTA LA FECHA A PROCESAR
            $bandera=FALSE;
            $query = "SELECT id_operacion_inmobiliaria,cod_propiedad FROM a2_operaciones_inmobiliarias WHERE estado='ACTIVO' AND 
            DATE(CONCAT(hasta_anio,'-',hasta_mes,'-',dia_venc_mensual))<=DATE('{$fecha_hasta}')";

            $connection = Yii::$app->getDb();
            $model = $connection->createCommand($query)->queryAll();
            foreach($model as $row){
                $bandera=TRUE;
                $query="SELECT id_operacion_inmobiliaria,cod_propiedad FROM a2_operaciones_inmobiliarias WHERE 
                estado='PENDIENTE' AND cod_propiedad=".$row['cod_propiedad'];
                $connection = Yii::$app->getDb();
                $contrato_pendiente = $connection->createCommand($query)->queryOne();

                $transaction = \Yii::$app->db->beginTransaction();
                if($contrato_pendiente){
                    $query = "UPDATE a2_operaciones_inmobiliarias SET estado='RENOVADO'
                    WHERE id_operacion_inmobiliaria=".$row['id_operacion_inmobiliaria'];

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute(); 

                    $query = "INSERT INTO auditoria_contratos (operacion_id,fecha_contrato,fecha_procesamiento,
                    estado_contrato) VALUES (".$row['id_operacion_inmobiliaria'].",'{$fecha_hasta}','".date('Y-m-d')."','RENOVADO')";

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute(); 

                    $query = "UPDATE a2_operaciones_inmobiliarias SET estado='ACTIVO'
                    WHERE id_operacion_inmobiliaria=".$contrato_pendiente['id_operacion_inmobiliaria'];        

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute();

                    $query = "INSERT INTO auditoria_contratos (operacion_id,fecha_contrato,fecha_procesamiento,
                    estado_contrato) VALUES (".$contrato_pendiente['id_operacion_inmobiliaria'].",'{$fecha_hasta}','".date('Y-m-d')."','ACTIVO')";

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute();
                    $transaction->commit();
                }else{
                    $query = "UPDATE a2_operaciones_inmobiliarias SET estado='FINALIZADO'
                    WHERE id_operacion_inmobiliaria=".$row['id_operacion_inmobiliaria'];

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute(); 

                    $query = "INSERT INTO auditoria_contratos (operacion_id,fecha_contrato,fecha_procesamiento,
                    estado_contrato) VALUES (".$row['id_operacion_inmobiliaria'].",'{$fecha_hasta}','".date('Y-m-d')."','FINALIZADO')";

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute();
                    $transaction->commit();
                }
                
            }
            $query = "SELECT id_operacion_inmobiliaria,cod_propiedad FROM a2_operaciones_inmobiliarias WHERE 
            estado='PENDIENTE' AND DATE(CONCAT(hasta_anio,'-',hasta_mes,'-',dia_venc_mensual))>=DATE('{$fecha_hasta}')";

            $connection = Yii::$app->getDb();
            $model = $connection->createCommand($query)->queryAll();
            foreach($model as $row){
                $query="SELECT id_operacion_inmobiliaria,cod_propiedad FROM a2_operaciones_inmobiliarias WHERE 
                estado='ACTIVO' AND cod_propiedad=".$row['cod_propiedad'];
                $connection = Yii::$app->getDb();
                $contrato_activo = $connection->createCommand($query)->queryOne();

                $transaction = \Yii::$app->db->beginTransaction();
                if(!$contrato_activo){
                    $bandera=TRUE;
                    $query = "UPDATE a2_operaciones_inmobiliarias SET estado='ACTIVO'
                    WHERE id_operacion_inmobiliaria=".$row['id_operacion_inmobiliaria'];

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute(); 

                    $query = "INSERT INTO auditoria_contratos (operacion_id,fecha_contrato,fecha_procesamiento,
                    estado_contrato) VALUES (".$row['id_operacion_inmobiliaria'].",'{$fecha_hasta}','".date('Y-m-d')."','ACTIVO')";

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute();
                    $transaction->commit();
                }
            }
            if($bandera){
                $mensaje="Se procesaron contratos";
            }else{
                $mensaje="No existen contratos para procesar";
            }
            return $this->render('procesar-contratos', ["mensaje"=>$mensaje]);
        //}
    }

    /**
     * Lists all AuditoriaContratos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditoriaContratosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuditoriaContratos model.
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
     * Creates a new AuditoriaContratos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuditoriaContratos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AuditoriaContratos model.
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
     * Deletes an existing AuditoriaContratos model.
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
     * Finds the AuditoriaContratos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuditoriaContratos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuditoriaContratos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
