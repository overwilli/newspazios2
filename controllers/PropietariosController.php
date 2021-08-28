<?php

namespace app\controllers;

use Yii;
use app\models\Propietarios;
use app\models\PropietariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * PropietariosController implements the CRUD actions for Propietarios model.
 */
class PropietariosController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete','get-propietarios','get-propietario-by-id','view' ],
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
     * Lists all Propietarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropietariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Propietarios model.
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
     * Creates a new Propietarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Propietarios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id,]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Propietarios model.
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
     * Deletes an existing Propietarios model.
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
     * Finds the Propietarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Propietarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Propietarios::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGetPropietarios($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            
            /*$data = Yii::$app->db->createCommand("SELECT id, concat(concat(concat(apellido,', '),
            nombre),concat(' (',concat(cuit,') '))) as text FROM propietarios 
            WHERE apellido LIKE '%$q%'")->queryAll();*/
            $resultado = Yii::$app->db->createCommand("SELECT id, concat(concat(apellido,', '),
            nombre) as apellido_nombre,cuit FROM propietarios 
            WHERE apellido LIKE '%$q%' OR nombre LIKE '%$q%' ")->queryAll();
            
            $data=null;
            foreach($resultado as $row){
                $obj_prop=new Propietarios;
                $obj_prop->cuit=$row['cuit'];

                $data[]=['id'=>$row['id'],'text'=>$row['apellido_nombre']." (".$obj_prop->obtener_cuit().")"];
            }

            /*for($i=0;$i<count($data);$i++){
                $obj_prop=new Propietarios;
                $obj_prop->cuit=$data[$i]['text'];
                $data[$i]['text']=$obj_prop->obtener_cuit();
            }*/

            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => City::find($id)->name];
        }
        return $out;
    }

    public function actionGetPropietarioById() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        //$out=null;
        $id=Yii::$app->request->get('id');
        if (!is_null($id)) {
            $row=Propietarios::find()->where(['id' => $id])->one();            
            $out['results'] = $row;            
        }
        return $out;
    }
}
