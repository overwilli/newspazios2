<?php

namespace app\controllers;

use Yii;
use app\models\InmueblesPropietarios;
use app\models\InmueblesPropietariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * InmueblesPropietariosController implements the CRUD actions for InmueblesPropietarios model.
 */
class InmueblesPropietariosController extends Controller
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
     * Lists all InmueblesPropietarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InmueblesPropietariosSearch();
        $param=Yii::$app->request->queryParams;        
        $param['InmueblesPropietariosSearch']['inmueble_id']=Yii::$app->request->get('id');        
        //$param['InmueblesPropietariosSearch']['estado']='ACTIVO';
        $dataProvider = $searchModel->search($param);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InmueblesPropietarios model.
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
     * Creates a new InmueblesPropietarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InmueblesPropietarios();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index','id' => $model->inmueble_id]);
        } else {
            $model->inmueble_id=Yii::$app->request->get('id');
            $model->comision=0;
            $model->porcentaje=100;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing InmueblesPropietarios model.
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
     * Deletes an existing InmueblesPropietarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $inmueble_id=$model->inmueble_id;
        $this->findModel($id)->delete();

        return $this->redirect(['index','id' => $inmueble_id]);
    }

   
    
/*
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $inmueble_id=$model->inmueble_id;
        //$model->estado='ELIMINADO';
        if($model->save()){

        }
        
        return $this->redirect(['index','id' => $inmueble_id]);
        //return $this->redirect(['index']);
    }
    */

    /**
     * Finds the InmueblesPropietarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InmueblesPropietarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InmueblesPropietarios::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
