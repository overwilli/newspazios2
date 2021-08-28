<?php

namespace app\controllers;

use Yii;
use app\models\PromocionIndividual;
use app\models\PromocionIndividualSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\filters\AccessControl;


/**
 * PromocionIndividualController implements the CRUD actions for PromocionIndividual model.
 */
class PromocionIndividualController extends Controller {

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
                        'actions' => ['index','create','view','update','delete'],
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
     * Lists all PromocionIndividual models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PromocionIndividualSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PromocionIndividual model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PromocionIndividual model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($promocion_id) {
        $model = new PromocionIndividual();
        $model->promociones_mails_id = $promocion_id;
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                /* print_r($model->imageFile);
                  var_dump( $model->imageFile->baseName);
                  var_dump('.' . $model->imageFile->extension);
                  exit; */
                if ($model->save()) {
                    return $this->redirect(['promociones-mails/view', 'id' => $model->promociones_mails_id]);
                } else {
                    return $this->render('create', [
                                'model' => $model,
                    ]);
                }
            } else {
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
        /* if ($model->load(Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(['promociones-mails/view', 'id' => $model->promociones_mails_id]);
          } else {
          return $this->render('create', [
          'model' => $model,
          ]);
          } */
    }

    /**
     * Updates an existing PromocionIndividual model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile && $model->upload()) {
                
            }
            if ($model->save()) {
                return $this->redirect(['promociones-mails/view', 'id' => $model->promociones_mails_id]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PromocionIndividual model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['promociones-mails/view', 'id' => $model->promociones_mails_id]);
    }

    /**
     * Finds the PromocionIndividual model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PromocionIndividual the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = PromocionIndividual::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
