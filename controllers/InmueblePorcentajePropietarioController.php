<?php

namespace app\controllers;

use Yii;
use app\models\InmueblePorcentajePropietario;
use app\models\InmueblePorcentajePropietarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * InmueblePorcentajePropietarioController implements the CRUD actions for InmueblePorcentajePropietario model.
 */
class InmueblePorcentajePropietarioController extends Controller
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
     * Lists all InmueblePorcentajePropietario models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InmueblePorcentajePropietarioSearch();
        $param=Yii::$app->request->queryParams;        
        $param['InmueblePorcentajePropietarioSearch']['inmueble_id']=Yii::$app->request->get('id');
        $dataProvider = $searchModel->search($param);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InmueblePorcentajePropietario model.
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
     * Creates a new InmueblePorcentajePropietario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InmueblePorcentajePropietario();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index' ,'id' => $model->inmueble_id]);
        } else {
            $model->inmueble_id=Yii::$app->request->get('id');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing InmueblePorcentajePropietario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index' ,'id' => $model->inmueble_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing InmueblePorcentajePropietario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $inmueble_id=$model->inmueble_id;
        $model->delete();
        
        return $this->redirect(['index','id' => $inmueble_id]);
    }

    /**
     * Finds the InmueblePorcentajePropietario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InmueblePorcentajePropietario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InmueblePorcentajePropietario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
