<?php

namespace app\controllers;

use Yii;
use app\models\Llaves;
use app\models\LlavesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * LlavesController implements the CRUD actions for Llaves model.
 */
class LlavesController extends Controller
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
                        'actions' => ['index', 'create', 'update'],
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
     * Lists all Llaves models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LlavesSearch();
        
        $param=Yii::$app->request->queryParams;        
        $param['LlavesSearch']['inmueble_id']=Yii::$app->request->get('id'); 
        $dataProvider = $searchModel->search($param);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Llaves model.
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
     * Creates a new Llaves model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Llaves();

        if ($model->load(Yii::$app->request->post())) {
            $model->fecha_solicitud=date("Y-m-d H:i:s");
            $model->persona=Yii::$app->user->identity->nikname;
            $model->tipo_solicitud='PRESTAMO';
            if($model->save()){
                return $this->redirect(['index', 'id' => $model->inmueble_id]);
            }            
        }

        $model->inmueble_id=Yii::$app->request->get('id');
        return $this->render('create', [
            'model' => $model,
        ]);        
    }

    /**
     * Updates an existing Llaves model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $observacion=$model->observacion;
        if ($model->load(Yii::$app->request->post()) ) {
            $model->observacion=$observacion.' Devolución:'.Yii::$app->request->post('Llaves')['observacion'];
            $model->fecha_devolucion=date("Y-m-d H:i:s");
            $model->usuario_devolucion=Yii::$app->user->identity->nikname;
            if($model->save()){
                return $this->redirect(['index', 'id' => $model->inmueble_id]);
            }
        }
        $model->observacion='';
        

        return $this->render('update', [
            'model' => $model,
        ]);
        
    }

    /**
     * Deletes an existing Llaves model.
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
     * Finds the Llaves model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Llaves the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Llaves::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
