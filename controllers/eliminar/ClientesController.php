<?php

namespace app\controllers;

use Yii;
use app\models\Clientes;
use app\models\ClientesSearch;
use app\models\Invitaciones;
use app\models\EnvioMails;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BlackListPromo;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;

/**
 * ClientesController implements the CRUD actions for Clientes model.
 */
class ClientesController extends Controller {

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
                        'actions' => ['enviar-invitacion-mail'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'listado-clientes-mail-incorrecto',
                            'listado-clientes-mail-incorrecto-excel', 'enviar-invitacion-mail',
                            'clientes-no-reciben-promociones', 'remover-cliente-blacklist', 'remover-cliente-blacklist-all',
                            'clientes-no-reciben-promociones-excel'],
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
     * Lists all Clientes models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ClientesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Clientes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Clientes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Clientes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dni]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Clientes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dni]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Clientes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Clientes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Clientes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Clientes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListadoClientesMailIncorrecto() {
        $searchModel = new ClientesSearch();
        $dataProvider = $searchModel->mails_incorrecto();
        return $this->render('listado-clientes-mail-incorrecto', [
                    //'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListadoClientesMailIncorrectoExcel() {
        header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
        header("Content-type:   application/x-msexcel; charset=Windows-1252");
        header("Content-Disposition: attachment; filename=ClientesMailIncorrecto.xls");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        $searchModel = new ClientesSearch();
        $dataProvider = $searchModel->mails_incorrecto();
        $model = Clientes::find()->All();
        $arreglo_clientes = null;
        foreach ($model as $row) {
            $expresion = "/^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,3}$/";
            $expresion1 = "/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/";
            $expresion2 = "/^[^0-9][.a-zA-Z0-9_-]+([.][_][-a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/";
            if (!preg_match($expresion1, $row['email'])) {
                $arreglo_clientes[] = ['dni' => $row['dni'], 'nombre' => $row['nombre'], 'email' => $row['email']];
            }
        }
        if (count($arreglo_clientes) > 0) {
            echo "<table>";
            echo "<th>DNI</th>";
            echo "<th>APELLIDO Y NOMBRE</th>";
            echo "<th>E-MAIL</th>";
            foreach ($arreglo_clientes as $row) {
                ?>
                <tr>
                    <td><?php echo $row['dni'] ?></td>
                    <td><?php echo $row['nombre'] ?></td>
                    <td><?php echo $row['email'] ?></td>
                </tr>
                <?php
            }
            echo "</table>";
        }
    }

    public function actionEnviarInvitacionMail($dni) {
        header('Content-Type: application/json');
        $model = Clientes::find()->where(['dni' => $dni])->one();
        if ($model) {
            $bandera = TRUE;
            if ($model->password != "") {
                $bandera = FALSE;
            }
            if ($bandera && isset($model->clientesUpdate) && !empty($model->clientesUpdate->password)) {
                $bandera = FALSE;
            }
            if ($bandera) {
                $hash = Clientes::gernerar_hash($dni);
                $template_invitacion = file_get_contents("../templates/mailA.html", true);
                $message_body = str_replace("[Nombre de Usuario]", $model->nombre, $template_invitacion);
                $message_body = str_replace("[Hash]", $hash, $message_body);
                EnvioMails::enviar_mail(utf8_encode("Bienvenido a Mí Dinamíca"), $model->email, $message_body);

                Invitaciones::incrementar_invitacion($model->dni);
                $arreglo['mensaje'] = "Se envio correctamente el mail.";
            } else {
                $arreglo['mensaje'] = "No se le envío porque el cliente ya tiene acceso";
            }
        } else {
            $arreglo['mensaje'] = "No se encontro el DNI:" . $dni;
        }


        echo json_encode($arreglo);
    }

    public function actionClientesNoRecibenPromociones() {
        $obj_black_list = new BlackListPromo();
        $resultado = [];
        //if (Yii::$app->request->post()) {
        $fecha_desde = Yii::$app->request->post('fecha_desde');
        if (preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $fecha_desde)) {
            $fecha_desde = explode('/', $fecha_desde);
            $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];
        } else {
            $fecha_desde = "";
        }
        $fecha_hasta = Yii::$app->request->post('fecha_hasta');
        if (preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $fecha_hasta)) {
            $fecha_hasta = explode('/', $fecha_hasta);
            $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];
        } else {
            $fecha_hasta = "";
        }
        $resultado = $obj_black_list->clientes_en_blacklist_by_fecha($fecha_desde, $fecha_hasta);
        //}
        $arreglo_clientes = null;
        foreach ($resultado as $row) {
            $fecha_baja = BlackListPromo::obtener_ultima_baja($row['dni']);
            $arreglo_clientes[] = ['dni' => $row['dni'], 'nombre' => $row['nombre'], 'email' => $row['email'], 'fecha_baja' => $fecha_baja];
        }
        $provider = new ArrayDataProvider([
            'allModels' => $arreglo_clientes,
            'pagination' => [
                'pageSize' => 500,
            ],
            'sort' => [
                'attributes' => ['nombre','dni','email','fecha_baja'],
            ],
        ]);
        return $this->render('clientes-no-reciben-promociones', [
                    //'searchModel' => $searchModel,
                    'dataProvider' => $provider, 'fecha_desde' => Yii::$app->request->post('fecha_desde'), 'fecha_hasta' => Yii::$app->request->post('fecha_hasta')
        ]);
    }

    public function actionClientesNoRecibenPromocionesExcel() {
        header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
        header("Content-type:   application/x-msexcel; charset=Windows-1252");
        header("Content-Disposition: attachment; filename=ClientesNoRecibenPromociones.xls");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);

        $obj_black_list = new BlackListPromo();
        $fecha_desde = Yii::$app->request->post('fecha_desde');
        if (preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $fecha_desde)) {
            $fecha_desde = explode('/', $fecha_desde);
            $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];
        } else {
            $fecha_desde = "";
        }
        $fecha_hasta = Yii::$app->request->post('fecha_hasta');
        if (preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $fecha_hasta)) {
            $fecha_hasta = explode('/', $fecha_hasta);
            $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];
        } else {
            $fecha_hasta = "";
        }
        $resultado = $obj_black_list->clientes_en_blacklist_by_fecha($fecha_desde, $fecha_hasta);

        $arreglo_clientes = null;
        foreach ($resultado as $row) {
            $fecha_baja = BlackListPromo::obtener_ultima_baja($row['dni']);
            $arreglo_clientes[] = ['dni' => $row['dni'], 'nombre' => $row['nombre'], 'email' => $row['email'], 'fecha_baja' => $fecha_baja];
        }        
        
        
        if (count($arreglo_clientes) > 0) {
            echo "<table>";
            echo "<th>DNI</th>";
            echo "<th>APELLIDO Y NOMBRE</th>";
            echo "<th>E-MAIL</th>";
            echo "<th>FECHA DE BAJA</th>";
            foreach ($arreglo_clientes as $row) {
                ?>
                <tr>
                    <td><?php echo $row['dni'] ?></td>
                    <td><?php echo $row['nombre'] ?></td>
                    <td><?php echo $row['email'] ?></td>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($row['fecha_baja'])); ?></td>
                </tr>
                <?php
            }
            echo "</table>";
        }
    }

    public function actionRemoverClienteBlacklist($dni) {
        try {
            BlackListPromo::deleteAll('dni = ' . $dni);
            $arreglo['mensaje'] = "Se removio el cliente del listado.";
        } catch (Exception $ex) {
            $arreglo['mensaje'] = "Ocurrio un error al eliminar el cliente del listado.";
        }
        header('Content-Type: application/json');

        echo json_encode($arreglo);
    }

    public function actionRemoverClienteBlacklistAll() {


        header('Content-Type: application/json');
        $listado = Yii::$app->request->post('listado_dni');
        $arreglo = null;
        foreach ($listado as $value) {
            try {
                BlackListPromo::deleteAll('dni = ' . $value);
            } catch (Exception $ex) {
                $arreglo['mensaje'] = "Ocurrio un error al eliminar el cliente del listado.";
            }
        }
        if (count($arreglo) == 0) {
            $arreglo['mensaje'] = "Accion ejecutada correctamente.";
        }
        echo json_encode($arreglo);
    }

}
