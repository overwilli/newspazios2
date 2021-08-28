<?php

namespace app\controllers;

use app\models\A2OperacionesInmobiliarias;
use Yii;
use app\models\A2OperacionesItems;
use app\models\A2OperacionesItemsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * A2OperacionesItemsController implements the CRUD actions for A2OperacionesItems model.
 */
class A2OperacionesItemsController extends Controller {

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
                        'actions' => ['index', 'create', 'view', 'update', 'delete','eliminar-todos-periodos'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all A2OperacionesItems models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new A2OperacionesItemsSearch();
        $param = Yii::$app->request->queryParams;
        if (Yii::$app->request->get('id_operacion')) {
            $param['A2OperacionesItemsSearch']['id_operacion'] = Yii::$app->request->get('id_operacion');
        }
        if (Yii::$app->request->get('anio')) {
            $param['A2OperacionesItemsSearch']['anio'] = Yii::$app->request->get('anio');
        }
        if (Yii::$app->request->get('mes')) {
            $param['A2OperacionesItemsSearch']['mes'] = Yii::$app->request->get('mes');
        }
        $dataProvider = $searchModel->search($param);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single A2OperacionesItems model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new A2OperacionesItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new A2OperacionesItems();
        $estado = (Yii::$app->request->get("estado"))?Yii::$app->request->get("estado"):"";
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post()) ) {
			$param = Yii::$app->request->post();
            $model->cantidad_meses=$param['A2OperacionesItems']['cantidad_meses'];
            //VARIABLE UTILIZADA PARA CONOCER SI VIENE POR RENOVACION O POR CARGA DIRECTA 
            $renovar=Yii::$app->request->get('renovar');
            if(isset($renovar) && ($renovar==TRUE)){
                $model->renovar_contrato=TRUE;
            }else{
                $model->renovar_contrato=FALSE;
            }
           
			if($model->validate()){                
		
				$bandera_error=FALSE;
				$param = Yii::$app->request->post();
				$id_operacion = $param['A2OperacionesItems']['id_operacion'];

				$ciclos = 1;
				$contador = 0;
				$mes_auxiliar = $param['A2OperacionesItems']['mes'];
				$anio_auxiliar = $param['A2OperacionesItems']['anio'];
				$errores=null;
				while ($ciclos <= $param['A2OperacionesItems']['cantidad_meses']) {              
					if ($mes_auxiliar + $contador > 12) {
						$mes_auxiliar = 1;
						$anio_auxiliar++;
						$contador = 0;
					}
					$mes_a_grabar = $mes_auxiliar + $contador;

					$model_aux = new A2OperacionesItems();
					$model_aux->id_operacion = $id_operacion;
					$model_aux->anio = $anio_auxiliar;
					$model_aux->mes = $mes_a_grabar;
					$model_aux->monto = $param['A2OperacionesItems']['monto'];
					$model_aux->id_factura = $param['A2OperacionesItems']['id_factura'];
                    
                    if(isset($renovar) && ($renovar==TRUE)){
                        $model_aux->renovar_contrato=TRUE;
                        $model_aux->estado = "PENDIENTE";
                    }else{
                        $model_aux->renovar_contrato=FALSE;
                        $model_aux->estado = $param['A2OperacionesItems']['estado'];
                    }
					if ($model_aux->save()) {
						/*echo "SE GRABO ";
						/*print_r($model_aux);*/
					}else{ 
                        $model_aux->validar_contrato_pendiente=TRUE;
                        if ($model_aux->save()) {
						    $bandera_error=TRUE;
                            $errores=$model_aux->getFirstError('mes');					
                        }
					}

					$contador++;
					$ciclos++;
				}
				
				//echo $param['A2OperacionesItems']['cantidad_meses'];			
				if(!$bandera_error){
                    if($renovar){
                        return $this->redirect(['cambios-operaciones/index', 'operacion_id' => $id_operacion]);
                    }else{
                        return $this->redirect(['index', 'id_operacion' => $id_operacion]);
                    }
				}else{ 
					$model->id_operacion = Yii::$app->request->get('id_operacion');
					return $this->render('create', [
								'model' => $model,'errores'=>$errores,'estado'=>$estado
					]);
				}
            }else{
				return $this->render('create', [
								'model' => $model,'errores'=>NULL,'estado'=>$estado
					]);
			}
        } else {
            $model->id_operacion = Yii::$app->request->get('id_operacion');
            $model_operacion=\app\models\A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria'=>$model->id_operacion])->one();
            if($model_operacion){
                $model->anio=$model_operacion->desde_anio;
                $model->mes=$model_operacion->desde_mes;
            }else{
                throw new Exception("No se pudo encontrar el contrato");
            }
            return $this->render('create', [
                        'model' => $model,'errores'=>NULL,'estado'=>$estado
            ]);
        }
    }

    /**
     * Updates an existing A2OperacionesItems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $estado = (Yii::$app->request->get("estado"))?Yii::$app->request->get("estado"):"";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id_operacion' => $model->id_operacion]);
        } else {
            return $this->render('update', [
                        'model' => $model,'estado'=>$estado
            ]);
        }
    }

    public function actionEliminarTodosPeriodos($id_operacion,$mes,$anio){
        $model_operaciones=A2OperacionesInmobiliarias::find()->
            where(['id_operacion_inmobiliaria'=>$id_operacion])->one();
        $model=A2OperacionesItems::find()->where(['id_operacion'=>$id_operacion,'mes'=>$mes,'anio'=>$anio])->all();
        foreach($model as $row){
            $model=$this->findModel($row->id_item);
            $model->delete();
        }
        return $this->redirect(['a2-operaciones-inmobiliarias/update','id' => $id_operacion]);
    }

    /**
     * Deletes an existing A2OperacionesItems model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model=$this->findModel($id);
		$id_operacion=$model->id_operacion;
		$model->delete();
		
		return $this->redirect(['index', 'id_operacion' => $id_operacion]);
        //return $this->redirect(['index']);
    }

    /**
     * Finds the A2OperacionesItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return A2OperacionesItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = A2OperacionesItems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
