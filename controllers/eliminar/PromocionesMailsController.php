<?php

namespace app\controllers;

use Yii;
use app\models\PromocionesMails;
use app\models\PromocionesMailsSearch;
use app\models\PromocionIndividual;
use app\models\PromocionIndividualSearch;
use app\models\PromomailLocalidades;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * PromocionesMailsController implements the CRUD actions for PromocionesMails model.
 */
class PromocionesMailsController extends Controller {

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
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'ver-mail-promo'],
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
     * Lists all PromocionesMails models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PromocionesMailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PromocionesMails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new PromocionIndividualSearch();
        $searchModel->promociones_mails_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new PromocionesMails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new PromocionesMails();

        $model->arreglo_localidades = Yii::$app->request->post('localidades_promo');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach ($model->arreglo_localidades as $row) {
                $model_promomail =new PromomailLocalidades();
                $model_promomail->promomail_id = $model->id;
                $model_promomail->localidad_id = $row;
                if($model_promomail->save()){
                    
                }
            }


            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionVerMailPromo($promocion_id) {
        $this->layout = false;
        $template_promocion = file_get_contents("../templates/promociones.html", true);
        $model_promo_individual = PromocionIndividual::find()->where(['promociones_mails_id' => $promocion_id, 'estado' => 'ACTIVO'])->orderBy('orden')->all();
        $body = "";
        foreach ($model_promo_individual as $row) {
            $fila = "";
            $fila.="<img style='display:block;width:600px;' alt='{$row->texto_imagen}' src='" . \Yii::$app->params['directorio_promocion'] . "{$row->url_image}'  />";
            if (!empty($row->enlace)) {
                $fila = "<a target='_blank' href='{$row->enlace}'>{$fila}</a>";
            }
            $fila.="";
            $body.=$fila;
        }
        $body.="";
        $message = str_replace("[contenido]", $body, $template_promocion);
        echo $message;
    }

    /**
     * Updates an existing PromocionesMails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        $model->arreglo_localidades = Yii::$app->request->post('localidades_promo');        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            PromomailLocalidades::deleteAll('promomail_id = :promomail_id', array(':promomail_id' => $model->id));
            foreach ($model->arreglo_localidades as $row) {
                $model_promomail =new PromomailLocalidades();
                $model_promomail->promomail_id = $model->id;
                $model_promomail->localidad_id = $row;
                if($model_promomail->save()){
                    
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if(Yii::$app->request->isGet){
                $rows=PromomailLocalidades::find()->where(['promomail_id'=>$model->id])->all();
                foreach ($rows as $row) {
                    $model->arreglo_localidades[]=$row->localidad_id;
                }
                
            }
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PromocionesMails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        PromocionIndividual::deleteAll('promociones_mails_id = :promociones_mails_id', array(':promociones_mails_id' => $id));
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PromocionesMails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PromocionesMails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = PromocionesMails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
