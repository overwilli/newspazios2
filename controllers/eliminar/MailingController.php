<?php

namespace app\controllers;

use Yii;
use app\models\Mailing;
use app\models\MailingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
/**
 * MailingController implements the CRUD actions for Mailing model.
 */
class MailingController extends Controller {

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
                        'actions' => ['index','create','view','update','delete','plantillas','actualizar-template','ver-template-mail'],
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
     * Lists all Mailing models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MailingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Mailing models.
     * @return mixed
     */
    public function actionPlantillas() {
        $searchModel = new MailingSearch();
        $dataProvider = $searchModel->plantillas();

        return $this->render('plantillas', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mailing model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Mailing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Mailing();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Mailing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionActualizarTemplate($id) {
        
        $model = $this->findModel($id);         
        $model->setScenario('actualizar_template');
        if ($model->load(Yii::$app->request->post())) {            
            $model->bandera_scenario=TRUE;
            
            
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');            
            if ($model->imageFile && $model->upload()) {
                
            }
            if ($model->save()) { 
                return $this->redirect(['actualizar-template', 'id' => $model->id]);
            } else { 
                return $this->render('actualizar', [
                            'model' => $model,
                ]);
            }
        } else { 
            return $this->render('actualizar', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionVerTemplateMail($mailing_id){
        $model=$this->findModel($mailing_id);
        $this->layout = false;
        $template_file = file_get_contents("../templates/".$model->archivo, true);
        $directorio = \Yii::$app->params['directorio_templates'];
        $body="<img src='{$directorio}{$model->url_image}' width='600' height='400' style='color: rgb(0, 0, 0); display: block; font-weight: normal; max-height: none; max-width: none; text-decoration: none; margin: 0px; padding: 0; border: 0px solid transparent;' alt=''>";        
        $message = str_replace("[contenido]", $body, $template_file);
        echo $message;
    }

    /**
     * Deletes an existing Mailing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Mailing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mailing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Mailing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
