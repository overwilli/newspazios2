<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PromocionesMails;
use app\models\PromocionIndividual;
use app\models\Clientes;
use app\models\EnvioMails;
use app\models\BlackListPromo;
use app\models\LogsNavegacion;
use app\models\Mailing;
use app\models\InvitacionesMasivas;
use app\models\Invitaciones;
use app\models\RegistroInvMasivas;
use app\models\PromomailLocalidades;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['login', 'logout', 'index', 'enviar-promo', 'cantidad-visitas-navegacion',
                //    'resumen-general', 'invitaciones-masivas'],
                'only' => ['login', 'logout', 'index',],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login','procesar-contratos'],
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'procesar-contratos', 'resumen-general', 
                        'historial-visitas'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    
    public function actionProcesarContratos() {
        //SE DETERMINA LA FECHA A PROCESAR
        $d = new \DateTime('first day of this month');
        $d->sub(new \DateInterval('P1D'));
        $fecha_hasta=$d->format('Y-m-d');
        $query="SELECT count(*) as cantidad FROM auditoria_contratos WHERE fecha_contrato='{$fecha_hasta}'";
        $connection = Yii::$app->getDb();
        $cantidad = $connection->createCommand($query)->queryScalar();
        if($cantidad==0){
            //SE BUSCA TODOS LOS CONTRATOS ACTIVOS CON FECHA DE VENCIMIENTO HASTA LA FECHA A PROCESAR

            $query = "SELECT id_operacion_inmobiliaria,cod_propiedad FROM a2_operaciones_inmobiliarias WHERE estado='ACTIVO' AND 
            DATE(CONCAT(hasta_anio,'-',hasta_mes,'-',dia_venc_mensual))<=DATE('{$fecha_hasta}')";

            $connection = Yii::$app->getDb();
            $model = $connection->createCommand($query)->queryAll();
            foreach($model as $row){
                $query="SELECT id_operacion_inmobiliaria,cod_propiedad FROM a2_operaciones_inmobiliarias WHERE 
                estado='PENDIENTE' AND cod_propiedad=".$row['cod_propiedad'];
                $connection = Yii::$app->getDb();
                $contrato_pendiente = $connection->createCommand($query)->queryOne();

                $transaction = \Yii::$app->db->beginTransaction();
                if($contrato_pendiente){
                    $query = "UPDATE a2_operaciones_inmobiliarias SET estado='FINALIZADO'
                    WHERE id_operacion_inmobiliaria=".$row['id_operacion_inmobiliaria'];

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute(); 

                    $query = "INSERT INTO auditoria_contratos (operacion_id,fecha_contrato,fecha_procesamiento,
                    estado_contrato) VALUES (".$row['id_operacion_inmobiliaria'].",'{$fecha_hasta}','".date('Y-m-d')."','RENOVADO')";

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute(); 

                    $query = "UPDATE a2_operaciones_inmobiliarias SET estado='ACTIVO'
                    WHERE id_operacion_inmobiliaria=".$contrato_pendiente['id_operacion_inmobiliaria'];        

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute();

                    $query = "INSERT INTO auditoria_contratos (operacion_id,fecha_contrato,fecha_procesamiento,
                    estado_contrato) VALUES (".$contrato_pendiente['id_operacion_inmobiliaria'].",'{$fecha_hasta}','".date('Y-m-d')."','ACTIVO')";

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute();
                    $transaction->commit();
                }else{
                    $query = "UPDATE a2_operaciones_inmobiliarias SET estado='FINALIZADO'
                    WHERE id_operacion_inmobiliaria=".$row['id_operacion_inmobiliaria'];

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute(); 

                    $query = "INSERT INTO auditoria_contratos (operacion_id,fecha_contrato,fecha_procesamiento,
                    estado_contrato) VALUES (".$row['id_operacion_inmobiliaria'].",'{$fecha_hasta}','".date('Y-m-d')."','FINALIZADO')";

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute();
                    $transaction->commit();
                }                
            }
            $query = "SELECT id_operacion_inmobiliaria,cod_propiedad FROM a2_operaciones_inmobiliarias WHERE 
            estado='PENDIENTE' AND DATE(CONCAT(hasta_anio,'-',hasta_mes,'-',dia_venc_mensual))>=DATE('{$fecha_hasta}')";

            $connection = Yii::$app->getDb();
            $model = $connection->createCommand($query)->queryAll();
            foreach($model as $row){
                $query="SELECT id_operacion_inmobiliaria,cod_propiedad FROM a2_operaciones_inmobiliarias WHERE 
                estado='ACTIVO' AND cod_propiedad=".$row['cod_propiedad'];
                $connection = Yii::$app->getDb();
                $contrato_activo = $connection->createCommand($query)->queryOne();

                $transaction = \Yii::$app->db->beginTransaction();
                if(!$contrato_activo){
                    $query = "UPDATE a2_operaciones_inmobiliarias SET estado='ACTIVO'
                    WHERE id_operacion_inmobiliaria=".$row['id_operacion_inmobiliaria'];

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute(); 

                    $query = "INSERT INTO auditoria_contratos (operacion_id,fecha_contrato,fecha_procesamiento,
                    estado_contrato) VALUES (".$row['id_operacion_inmobiliaria'].",'{$fecha_hasta}','".date('Y-m-d')."','ACTIVO')";

                    $connection = Yii::$app->getDb();
                    $connection->createCommand($query)->execute();
                    $transaction->commit();
                }
            }
        }
    }

//    private function hash($data) {
//        $clave_privada = Yii::$app->params['clave_privada'];
//        $hash = sha1($data . $clave_privada);
//        return $hash;
//    }



    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->redirect(array('site/index'));
            //return $this->goBack();
        }
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        //return $this->goHome();
        return $this->redirect(array('site/login'));
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }
    

    public function actionAbout() {
        return $this->render('about');
    }

}
