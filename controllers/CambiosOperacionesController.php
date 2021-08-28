<?php

namespace app\controllers;

use Yii;
use app\models\CambiosOperaciones;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\A2OperacionesInmobiliarias;
use yii\filters\AccessControl;
use app\models\A2OperacionesItems;
/**
 * CambiosOperacionesController implements the CRUD actions for CambiosOperaciones model.
 */
class CambiosOperacionesController extends Controller
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
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
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
     * Lists all CambiosOperaciones models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model_operacion=A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => Yii::$app->request->get('operacion_id'),'estado'=>'ACTIVO'])->one();
        $dataProvider = new ActiveDataProvider([
            'query' => CambiosOperaciones::find()->where(['operacion_id'=>Yii::$app->request->get('operacion_id')]),
        ]);
        $dataProvider_periodos = new ActiveDataProvider([
            'query' =>A2OperacionesItems::find()->where(['id_operacion'=>Yii::$app->request->get('operacion_id')]),			
			 'sort'=> ['defaultOrder' => ['anio'=>SORT_DESC,'mes'=>SORT_DESC]]
        ]);
        //$model_periodos=A2OperacionesItems::find()->where(['id_operacion'=>Yii::$app->request->get('operacion_id')])->all();        


        return $this->render('index', [
            'dataProvider' => $dataProvider,'model_operacion'=>$model_operacion,'dataProvider_periodos'=>$dataProvider_periodos
        ]);
    }    

    /**
     * Displays a single CambiosOperaciones model.
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
     * Creates a new CambiosOperaciones model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CambiosOperaciones();

        if ($model->load(Yii::$app->request->post()) ) {
			$operacion = A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => $model->operacion_id])->one();
			
			$model->desde_mes=$operacion->desde_mes;
			$model->desde_anio=$operacion->desde_anio;			
		
			$model->estado='pendiente';
			$model->fecha=date('Y-m-d');
			$model->usuario=Yii::$app->user->identity->nikname;
			if($model->save()){
				return $this->redirect(['cambios-operaciones/index', 'operacion_id' =>  $model->operacion_id]);
			}else{
				return $this->render('create', [
					'model' => $model,
				]);
			}
        } else {
			$model->operacion_id=Yii::$app->request->get('operacion_id');
			
			$operacion = A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => $model->operacion_id])->one();
			
			$model->desde_mes=$operacion->desde_mes;
			$model->desde_anio=$operacion->desde_anio;
			$model->hasta_mes=$operacion->hasta_mes;
			$model->hasta_anio=$operacion->hasta_anio;
			
            return $this->render('create', [
                'model' => $model,'operacion'=>$operacion
            ]);
        }
    }

    /**
     * Updates an existing CambiosOperaciones model.
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
     * Deletes an existing CambiosOperaciones model.
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
     * Finds the CambiosOperaciones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CambiosOperaciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CambiosOperaciones::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
