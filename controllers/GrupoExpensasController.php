<?php

namespace app\controllers;

use Yii;
use app\models\GrupoExpensas;
use app\models\GrupoExpensasSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\A2Noticias;
/**
 * GrupoExpensasController implements the CRUD actions for GrupoExpensas model.
 */
class GrupoExpensasController extends Controller
{
    /**
     * @inheritdoc
     */
    
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => [],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'create-multiple', 'create-by-rubro', 'update','delete',
                            'view','inmuebles-por-grupo'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all GrupoExpensas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GrupoExpensasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GrupoExpensas model.
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
     * Creates a new GrupoExpensas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GrupoExpensas();
        $model->expensas_por="GRUPO";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $query = A2Noticias::find();
            $query->joinWith(['operacionInmobiliaria']);
            $model_inmuebles =$query->where(['id_grupo'=>$model->grupo_id,'a2_operaciones_inmobiliarias.estado'=>'ACTIVO'])->all();
            foreach ($model_inmuebles as $row) {                
                $model_ope_exp=new \app\models\OperacionesExpensas();
                
                $contrato_activo=$row->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();                

                $model_ope_exp->operacion_id=$contrato_activo->id_operacion_inmobiliaria;
                $model_ope_exp->tipo_expensas_id=$model->tipo_expensa_id;
                $model_ope_exp->inmuebles_id=$row->id_noticia;
                $model_ope_exp->mes=$model->mes;
                $model_ope_exp->year=$model->year;
                if(is_numeric($row->porcion)){
                    $model_ope_exp->importe=($model->importe/$row->porcion)*100;
                }else{
                    $model_ope_exp->importe=$model->importe/count($model_inmuebles);
                }
                
                $model_ope_exp->estado='impago';
                $model_ope_exp->estado_reg='PENDIENTE';
                $model_ope_exp->grupo_expensas_id=$model->id;
                if($model_ope_exp->save()){

                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if(Yii::$app->request->get()){
                $fecha = new \DateTime(date('Y-m-d'));
                $fecha->modify('first day of this month');
                $fecha->sub(new \DateInterval('P1D'));            
                $model->mes=(int)$fecha->format('m');                
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateMultiple()
    {        
        $model = new GrupoExpensas();
        
        if (Yii::$app->request->post()) {
            
            $indice_items=Yii::$app->request->post('indice');
            $datos_items=Yii::$app->request->post('datos');
            //var_dump($datos_items);exit;
            foreach($indice_items as $item){
                $grupo=Yii::$app->request->post('GrupoExpensas');
                $model = new GrupoExpensas();
                $model->grupo_id=$grupo['grupo_id'];
                $model->tipo_expensa_id=$datos_items[$item]["id_expensa"];
                $model->mes=$datos_items[$item]["mes"];
                $model->year=$datos_items[$item]["anio"];
                $model->importe=$datos_items[$item]["importe"];
                $model->expensas_por="MULTIPLE";
                if($model->save()){           
                        
                    $query = A2Noticias::find();
                    $query->joinWith(['operacionInmobiliaria']);
                    $model_inmuebles =$query->where(['id_grupo'=>$model->grupo_id,'a2_operaciones_inmobiliarias.estado'=>'ACTIVO'])->all();
                    foreach ($model_inmuebles as $row) {
                        

                        foreach($indice_items as $item){
                            $model_ope_exp=new \app\models\OperacionesExpensas();
                        
                            $contrato_activo=$row->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();    
                            if($contrato_activo){            
                                //print_r($datos_items);echo "<br/>";echo "<br/>";
                                //print_r($item);echo "<br/>";echo "<br/>";
                                $arreglo_item=$datos_items[$item];
                                /*print_r($arreglo_item);
                                print_r($arreglo_item["\"id_expensa\""]);
                                print_r(array('test'=>1,'test1'=>2,'test3'=>3));*/
                                //exit;
                                $model_ope_exp->operacion_id=$contrato_activo->id_operacion_inmobiliaria;
                                $model_ope_exp->tipo_expensas_id=$arreglo_item["id_expensa"];
                                $model_ope_exp->inmuebles_id=$row->id_noticia;
                                $model_ope_exp->mes=$arreglo_item['mes'];
                                $model_ope_exp->year=$arreglo_item['anio'];
                                if(is_numeric($row->porcion)){
                                    $model_ope_exp->importe=($arreglo_item['importe']/$row->porcion)*100;
                                }else{
                                    $model_ope_exp->importe=$arreglo_item['importe']/count($model_inmuebles);
                                }
                                
                                $model_ope_exp->estado='impago';
                                $model_ope_exp->estado_reg='PENDIENTE';
                                $model_ope_exp->grupo_expensas_id=$model->id;
                                
                                if($model_ope_exp->save()){                                    
                                }
                            }else{
                                echo "no hay contratos activos";
                            }
                        }
                    }
                }
            }
            return $this->redirect(['index']);
        } else {
            if(Yii::$app->request->get()){
                $fecha = new \DateTime(date('Y-m-d'));
                $fecha->modify('first day of this month');
                $fecha->sub(new \DateInterval('P1D'));            
                $model->mes=(int)$fecha->format('m');                
            }
            return $this->render('create_multiple', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateByRubro()
    {        
        $model = new GrupoExpensas();
        
        if (Yii::$app->request->post()) {
            $grupo=Yii::$app->request->post('GrupoExpensas');
            $model = new GrupoExpensas();
            $model->grupo_id=$grupo['grupo_id'];
            $model->tipo_expensa_id=$grupo["tipo_expensa_id"];
            $model->mes=$grupo["mes"];
            $model->year=$grupo["year"];
            $model->importe=0;
            $inmuebles=Yii::$app->request->post('inmuebles');
            $model->expensas_por="RUBRO";
            if($model->save()){
                foreach($inmuebles as $item){
                    if(is_numeric(Yii::$app->request->post('importe')[$item])){
                        $model_ope_exp=new \app\models\OperacionesExpensas();
                        
                        
                        $model_ope_exp->operacion_id=Yii::$app->request->post('id_operacion_inmobiliaria')[$item];
                        $model_ope_exp->tipo_expensas_id=$grupo["tipo_expensa_id"];
                        $model_ope_exp->inmuebles_id=$item;
                        $model_ope_exp->mes=$grupo["mes"];
                        $model_ope_exp->year=$grupo["year"];
                        $model_ope_exp->importe=Yii::$app->request->post('importe')[$item];
                        
                        $model_ope_exp->estado='impago';
                        $model_ope_exp->estado_reg='PENDIENTE';
                        $model_ope_exp->grupo_expensas_id=$model->id;
                        
                        if($model_ope_exp->save()){                                    
                        }
                    }
                    
                }
                return $this->redirect(['index']);
            }
        } else {
            if(Yii::$app->request->get()){
                $fecha = new \DateTime(date('Y-m-d'));
                $fecha->modify('first day of this month');
                $fecha->sub(new \DateInterval('P1D'));            
                $model->mes=(int)$fecha->format('m');                
            }
            return $this->render('create_by_rubro', [
                'model' => $model,
            ]);
        }
    }

    public function actionInmueblesPorGrupo(){
        $query = A2Noticias::find();
        $query->joinWith(['operacionInmobiliaria']);
        $model_inmuebles =$query->where(['id_grupo'=>Yii::$app->request->post('grupo_id'),'a2_operaciones_inmobiliarias.estado'=>'ACTIVO'])->all();
        foreach ($model_inmuebles as $row) {
            $contrato_activo=$row->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();

            echo "<tr><td><input name='inmuebles[]' type='hidden' value='".$row->id_noticia."' />
            <input name='id_operacion_inmobiliaria[".$row->id_noticia."]' type='hidden' value='".$contrato_activo->id_operacion_inmobiliaria."' />
            </td><td>".$row->direccion."</td>";
            echo "<td><input class='solo_numeros' name='importe[".$row->id_noticia."]' type='decimal' value='' /></td></tr>";
        }
        exit;
    }

    /**
     * Updates an existing GrupoExpensas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model_ope_exp=\app\models\OperacionesExpensas::find()->where("estado='impago' AND (estado_reg='ACTIVO' OR estado_reg='PENDIENTE' 
            AND grupo_expensas_id={$model->id})")->all();
            foreach ($model_ope_exp as $row) {                
            
                $model_inmueble = A2Noticias::find()->where('id_noticia='.$row->inmuebles_id)->one();

                //$model_ope_exp->operacion_id=$contrato_activo->id_operacion_inmobiliaria;
                $row->tipo_expensas_id=$model->tipo_expensa_id;
                //$model_ope_exp->inmuebles_id=$row->id_noticia;
                $row->mes=$model->mes;
                $row->year=$model->year;
                if(is_numeric($model_inmueble->porcion)){
                    $row->importe=($model->importe/$model_inmueble->porcion)*100;
                }else{
                    $row->importe=$model->importe/count($model_ope_exp);
                }
                
                //$model_ope_exp->estado='impago';
                //$model_ope_exp->estado_reg='PENDIENTE';
                //$model_ope_exp->grupo_expensas_id=$model->id;
                if($row->save()){

                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GrupoExpensas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        
        \app\models\OperacionesExpensas::deleteAll("estado='impago' AND (estado_reg='ACTIVO' OR estado_reg='PENDIENTE' 
            AND grupo_expensas_id={$model->id})");
        //$model_operaciones->delete();
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GrupoExpensas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GrupoExpensas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GrupoExpensas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
