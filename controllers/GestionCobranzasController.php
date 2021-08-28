<?php

namespace app\controllers;

use app\models\A2LiquidacionesSearch;
use Yii;
use app\models\GestionCobranzas;
use app\models\GestionCobranzasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * GestionCobranzasController implements the CRUD actions for GestionCobranzas model.
 */
class GestionCobranzasController extends Controller
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
                        'actions' => ['index', 'create', 'view', 'update', 'delete','listado-morosos',
                        'imprimir-nota'],
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

    public function actionListadoMorosos(){
        $searchModel = new A2LiquidacionesSearch();
        $param = Yii::$app->request->queryParams; //['A2NoticiasSearch']['tipo_filtro']='por_vencer';        
        $param['A2LiquidacionesSearch']['estado'] = 'ACTIVO';
        $searchModel->tipo_filtro=(isset($param['A2LiquidacionesSearch']['tipo_filtro']))?$param['A2LiquidacionesSearch']['tipo_filtro']:'';
        $dataProvider = $searchModel->morosos_search($param);


        //$param['A2LiquidacionesSearch']['estado'] = 'PREIMPRESO';

        //$dataProvider_pendientes = $searchModel->search($param);

        return $this->render('listado-morosos', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    //'dataProvider_pendientes' => $dataProvider_pendientes,
        ]);
    }

    public function actionImprimirNota($id){
        $model=$this->findModel($id);
        $this->layout=FALSE;
        return $this->render('imprimir-nota',['model' => $model,]);
    }

    /**
     * Lists all GestionCobranzas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GestionCobranzasSearch();
		$param['GestionCobranzasSearch']['inmueble_id']=Yii::$app->request->get('id');
		$param['GestionCobranzasSearch']['operacion_id']=Yii::$app->request->get('operacion_id');        
        $dataProvider = $searchModel->search($param);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GestionCobranzas model.
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
     * Creates a new GestionCobranzas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GestionCobranzas();

        if ($model->load(Yii::$app->request->post()) ) {
			$model->fecha=date('Y-m-d');
			$model->hora=date('H:i:s');
			if($model->save()){
				return $this->redirect(['index', 'id' => $model->inmueble_id,'operacion_id'=>$model->operacion_id]);
			}else{
				$model->inmueble_id=Yii::$app->request->get('id');
				$model->operacion_id=Yii::$app->request->get('operacion_id'); 
				$model->cliente_id=$model->contrato->cod_cliente;
				return $this->render('create', [
					'model' => $model,
				]);
			}
        } else {
			$model->inmueble_id=Yii::$app->request->get('id');
			$model->operacion_id=Yii::$app->request->get('operacion_id'); 
			$model->cliente_id=$model->contrato->cod_cliente;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GestionCobranzas model.
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
     * Deletes an existing GestionCobranzas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $inmueble_id=$model->inmueble_id;
        $operacion_id=$model->operacion_id;
        $model->delete();

        //return $this->redirect(['index']);
        return $this->redirect(['index', 'id' => $inmueble_id,'operacion_id'=>$operacion_id]);
    }

    /**
     * Finds the GestionCobranzas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GestionCobranzas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GestionCobranzas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
