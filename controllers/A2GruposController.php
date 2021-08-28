<?php

namespace app\controllers;

use Yii;
use app\models\A2Grupos;
use app\models\A2GruposSearch;
use app\models\A2Noticias;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * A2GruposController implements the CRUD actions for A2Grupos model.
 */
class A2GruposController extends Controller
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
                        'actions' => ['index', 'create', 'view', 'update', 'delete',
                            'actualizar-porcion-grupo','nuevo-inmueble-grupo','informe-grupos'],
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
     * Lists all A2Grupos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new A2GruposSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single A2Grupos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);

        $searchModel = new \app\models\A2NoticiasSearch();
        $param = Yii::$app->request->queryParams; 
        $param['A2NoticiasSearch']['id_grupo'] = $model->id_grupo;
        $dataProvider_inmuebles = $searchModel->search($param); 

        return $this->render('view', [
            'model' => $model,'dataProvider_inmuebles'=>$dataProvider_inmuebles
        ]);
    }

    
    
    public function actionNuevoInmuebleGrupo()
    {        
        $grupo_id=Yii::$app->request->get('grupo_id');
        
        $model = new A2Grupos();        
        $model_inmueble=new A2Noticias();
        $model_inmueble->id_grupo=$grupo_id;

        if ($_POST) {
            $porcion=Yii::$app->request->post('A2Noticias')['porcion'];
            $id_grupo=Yii::$app->request->post('A2Noticias')['id_grupo'];
            $inmueble_id=Yii::$app->request->post('A2Noticias')['id_noticia'];
            
            $connection = Yii::$app->getDb();
            $connection->createCommand()->update('a2_noticias', 
                ['porcion'=>$porcion,'id_grupo'=>$id_grupo], 'id_noticia=:id_noticia',
                [':id_noticia'=>$inmueble_id])->execute();
            
            return $this->redirect(['a2-grupos/view', 'id' => $grupo_id]);
        } else {
            return $this->render('nueva_porcion_grupo', [
                'model' => $model,'grupo_id'=>$grupo_id,'model_inmueble'=>$model_inmueble
            ]);
        }
    }

    /**
     * Creates a new A2Grupos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionActualizarPorcionGrupo()
    {
        $inmueble_id=Yii::$app->request->get('inmueble_id');
        $grupo_id=Yii::$app->request->get('grupo_id');
        
        $model = new A2Grupos();
        $model_inmueble=A2Noticias::find()->where(['id_noticia'=>$inmueble_id])->one();

        if ($_POST) {
            $porcion=Yii::$app->request->post('A2Noticias')['porcion'];
            $id_grupo=Yii::$app->request->post('A2Noticias')['id_grupo'];
            
            $connection = Yii::$app->getDb();
            $connection->createCommand()->update('a2_noticias', 
                ['porcion'=>$porcion,'id_grupo'=>$id_grupo], 'id_noticia=:id_noticia',
                [':id_noticia'=>$inmueble_id])->execute();
            
            return $this->redirect(['a2-grupos/view', 'id' => $grupo_id]);
        } else {
            return $this->render('actualizar_porcion_grupo', [
                'model' => $model,'model_inmueble'=>$model_inmueble
            ]);
        }
    }

    /**
     * Creates a new A2Grupos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new A2Grupos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_grupo]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing A2Grupos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_grupo]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing A2Grupos model.
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
     * Finds the A2Grupos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return A2Grupos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = A2Grupos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionInformeGrupos(){
        ob_start();
        //require_once('../models/reportes/excel/resumen_cajas.php');
        require_once('../models/reportes/excel/informe_grupos.php');
        //echo "ingreso";exit;
        $content = ob_get_clean();
        echo $content;
        exit;
    }
}
