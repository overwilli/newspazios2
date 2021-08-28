<?php

namespace app\controllers;

use Yii;
use app\models\OperacionesExpensas;
use app\models\OperacionesExpensasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\A2Noticias;
use app\models\A2NoticiasSearch;
use yii\filters\AccessControl;

/**
 * OperacionesExpensasController implements the CRUD actions for OperacionesExpensas model.
 */
class OperacionesExpensasController extends Controller
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
                        'actions' => ['index', 'create', 'view', 'update', 'delete','confirmar-expensas'],
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
     * Lists all OperacionesExpensas models.
     * @return mixed
     */
    public function actionIndex()
    {
		$mensaje_expensas=Yii::$app->request->get('mensaje_expensas');
        $request = Yii::$app->request;
        
        $searchModel = new OperacionesExpensasSearch();
        $searchModel->operacion_id=$request->get('operacion_id');
        $searchModel->inmuebles_id=$request->get('propiedad_id');
		$searchModel->estado_reg="ACTIVO";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$searchModel->estado_reg="PENDIENTE";
		$dataProvider_pendiente = $searchModel->search(Yii::$app->request->queryParams);
		
		$model_exp=new OperacionesExpensas;
		$expensas_pendientes=$model_exp->obtener_expensas_pendientes($searchModel->operacion_id);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'dataProvider_pendiente' => $dataProvider_pendiente,
			'expensas_pendientes'=>$expensas_pendientes,
			'mensaje_expensas'=>$mensaje_expensas
        ]);
    }  

	public function actionConfirmarExpensas() {
		$mensaje_periodos="";
		if(isset($_POST['Guardar']) && $_POST['Guardar']=='Confirmar Pendientes'){
			if(isset($_POST['expensas_pendientes'])){
				$bandera=FALSE;
				foreach($_POST['expensas_pendientes'] as $row){
					
					$model_item=OperacionesExpensas::find()->where(['id'=>$row])->one();
					if($model_item){
						$model_item->estado_reg='ACTIVO';
						if($model_item->save()){
							$bandera=TRUE;
						}
					}
				}
			}
		}elseif(isset($_POST['Anular']) && $_POST['Anular']=='Anular Seleccionados'){
			if(isset($_POST['expensas_pendientes'])){
				$bandera=FALSE;
				foreach($_POST['expensas_pendientes'] as $row){
					
					$model_item=OperacionesExpensas::find()->where(['id'=>$row])->one();
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
		return $this->redirect(['operaciones-expensas/index', 'propiedad_id' => $_POST['propiedad_id'],'operacion_id' => $_POST['operacion_id'],
			'mensaje_expensas'=>$mensaje_expensas]);
		 //[operacion_id] => 242 [periodos_pendientes_id] => Array ( [0] => 15580 [1] => 15581 [2] => 15582 ) [Guardar] => Confirmar Pendientes
	
	}
    

    /**
     * Displays a single OperacionesExpensas model.
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
     * Creates a new OperacionesExpensas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperacionesExpensas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->operacion_id=Yii::$app->request->get('operacion_id');            
            $model->inmuebles_id= $model->contrato->inmueble->id_noticia;			
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperacionesExpensas model.
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
     * Deletes an existing OperacionesExpensas model.
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
     * Finds the OperacionesExpensas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperacionesExpensas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperacionesExpensas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
