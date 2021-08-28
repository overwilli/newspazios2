<?php

namespace app\controllers;

use Yii;
use app\models\A2NoticiasImagenes;
use app\models\A2noticias;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
/**
 * A2NoticiasImagenesController implements the CRUD actions for A2NoticiasImagenes model.
 */
class A2NoticiasImagenesController extends Controller {

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
     * Lists all A2NoticiasImagenes models.
     * @return mixed
     */
    public function actionIndex() {
        $id=Yii::$app->request->get('id');
        $model = new A2NoticiasImagenes();
        $model->id_noticia=$id;
        $dataProvider = new ActiveDataProvider([
            'query' => A2NoticiasImagenes::find()->where(['id_noticia' => Yii::$app->request->get('id')]),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,'id'=>$id,'model'=>$model
        ]);
    }

    /**
     * Displays a single A2NoticiasImagenes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new A2NoticiasImagenes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

       public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // el archivo se subió exitosamente
                return;
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    public function actionCreate($id) {
        $model = new A2NoticiasImagenes();
        $model->id_noticia = $id;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            if ($model->upload()) {
                if ($model->save()) {
                    return $this->redirect(['a2-noticias-imagenes/index', 'id' => $model->id_noticia]);
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
            //return $this->redirect(['view', 'id' => $model->id_noticia_imagen]);
        } else {
            $model->id_noticia=Yii::$app->request->get('id');
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }



    /**
     * Updates an existing A2NoticiasImagenes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_noticia_imagen]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing A2NoticiasImagenes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $this->findModel($id)->delete();

        return $this->redirect(['a2-noticias-imagenes/index', 'id'=> $model->id_noticia]);
    }

    /**
     * Finds the A2NoticiasImagenes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return A2NoticiasImagenes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = A2NoticiasImagenes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
