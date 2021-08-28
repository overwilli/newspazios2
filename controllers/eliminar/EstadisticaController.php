<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Clientes;

class EstadisticaController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
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
    public function actionAccesoUsuarios() {
        return $this->render('acceso_usuarios');
    }

    private function validar_fecha($fecha) {
        if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $fecha)) {
            return FALSE;
        }
        return TRUE;
    }

    public function actionHistorialVisitas() {
        if (!$this->validar_fecha($_GET['desde']) || !$this->validar_fecha($_GET['hasta'])) {
            throw new Exception("Formato de fecha incorrecto xx/xx/xxxx");
        }

        $fecha_desde = explode("/", $_GET['desde']);
        $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

        $fecha_hasta = explode("/", $_GET['hasta']);
        $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];

        $this->layout = false;
        //$primer_dia = new \DateTime($fecha_desde);
        //$ultimo_dia = new \DateTime($fecha_hasta);

        $resultado = Clientes::cantidad_clientes_autenticados_por_dia($fecha_desde, $fecha_hasta);

        $arreglo_values = null;
        foreach ($resultado as $row) {
            $arreglo = null;
            $arreglo['fecha'] = $row['fecha'];
            $arreglo['cantidad'] = $row['cantidad'];
            $arreglo_values[] = $arreglo;
        }
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($arreglo_values);
        exit;
    }

    public function actionCantidadClientesPeriodo() {
        if (!$this->validar_fecha($_GET['desde']) || !$this->validar_fecha($_GET['hasta'])) {
            throw new Exception("Formato de fecha incorrecto xx/xx/xxxx");
        }

        $fecha_desde = explode("/", $_GET['desde']);
        $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

        $fecha_hasta = explode("/", $_GET['hasta']);
        $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];

        $this->layout = false;
        $primer_dia = new \DateTime($fecha_desde);
        $ultimo_dia = new \DateTime($fecha_hasta);

        $cantidad_clientes_distintos_mes = 0;
        $resultado_clientes_mes = Clientes::cantidad_clientes_autenticados_por_dia($fecha_desde, $fecha_hasta);
        foreach ($resultado_clientes_mes as $row) {
            $cantidad_clientes_distintos_mes += $row['cantidad'];
        }
        header('Content-Type: application/json; charset=utf-8');
        $arreglo_values['cantidad'] = $cantidad_clientes_distintos_mes;
        echo json_encode($arreglo_values);
        exit;
    }

    public function actionHistorialVisitasMes() {
        if (!$this->validar_fecha($_GET['desde']) || !$this->validar_fecha($_GET['hasta'])) {
            throw new Exception("Formato de fecha incorrecto xx/xx/xxxx");
        }

        $fecha_desde = explode("/", $_GET['desde']);
        $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

        $fecha_hasta = explode("/", $_GET['hasta']);
        $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];
        
        
        $primer_dia = new \DateTime($fecha_desde);
        $ultimo_dia = new \DateTime($fecha_hasta);




        $primer_dia->modify('first day of this month');
        $primero = $primer_dia->format("Y-m-d");

        $primer_dia->modify('last day of this month');
        $ultimo = $primer_dia->format("Y-m-d");

        $aux_primer_dia = new \DateTime($primer_dia->format("Y-m-d"));

        $arreglo[0] = array($primero, $ultimo,$primer_dia->format("Y-m"));

        $ultimo_dia->modify('first day of this month');
        $primero = $ultimo_dia->format("Y-m-d");
        $ultimo = $ultimo_dia->format("Y-m-d");
        $aux_ultimo_dia = new \DateTime($primero);
        $i=1;
        while ($aux_primer_dia < $aux_ultimo_dia) {
            $aux_primer_dia->modify('+1 day');
            $aux_pd = $aux_primer_dia;

            $aux_pd->modify('first day of this month');
            $primero = $aux_pd->format("Y-m-d");

            $aux_pd->modify('last day of this month');
            $ultimo = $aux_pd->format("Y-m-d");            
            $arreglo[$i] = array($primero, $ultimo,$aux_pd->format("Y-m"));
            $i++;
        }
        

        /* if (!$this->validar_fecha($_GET['desde']) || !$this->validar_fecha($_GET['hasta'])) {
          throw new Exception("Formato de fecha incorrecto xx/xx/xxxx");
          }

          $fecha_desde = explode("/", $_GET['desde']);
          $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

          $fecha_hasta = explode("/", $_GET['hasta']);
          $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];

          $this->layout = false;
          //$primer_dia = new \DateTime($fecha_desde);
          //$ultimo_dia = new \DateTime($fecha_hasta);

          $resultado = Clientes::cantidad_clientes_autenticados_por_dia($fecha_desde, $fecha_hasta);
         */
        
        $arreglo_values = null;
        foreach ($arreglo as $row) {
            
            $cantidad_clientes_distintos_mes=0;
            $resultado_clientes_mes = Clientes::cantidad_clientes_autenticados_por_dia($row[0], $row[1]);
            
            foreach ($resultado_clientes_mes as $row1) {
                $cantidad_clientes_distintos_mes += $row1['cantidad'];
            }
            
            
            $arreglo_nuevo = null;
            $arreglo_nuevo['fecha'] = $row[2];
            $arreglo_nuevo['cantidad'] = $cantidad_clientes_distintos_mes;
            $arreglo_values[] = $arreglo_nuevo;
            
        }
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($arreglo_values);
        exit;
    }

    public function actionHistorialVisitasSemana() {
        if (!$this->validar_fecha($_GET['desde']) || !$this->validar_fecha($_GET['hasta'])) {
            throw new Exception("Formato de fecha incorrecto xx/xx/xxxx");
        }

        $fecha_desde = explode("/", $_GET['desde']);
        $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

        $fecha_hasta = explode("/", $_GET['hasta']);
        $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];
        
        
        $primer_dia = new \DateTime($fecha_desde);
        $ultimo_dia = new \DateTime($fecha_hasta);




        $primer_dia->modify('Monday this week');
        $primero = $primer_dia->format("Y-m-d");

        $primer_dia->modify('Sunday this week');
        $ultimo = $primer_dia->format("Y-m-d");

        $aux_primer_dia = new \DateTime($primer_dia->format("Y-m-d"));

        $arreglo[0] = array($primero, $ultimo);

        $ultimo_dia->modify('Monday this week');
        $primero = $ultimo_dia->format("Y-m-d");
        $ultimo = $ultimo_dia->format("Y-m-d");
        $aux_ultimo_dia = new \DateTime($primero);        
        
        $i = 1;
        while ($aux_primer_dia < $aux_ultimo_dia) {
            $aux_primer_dia->modify('+1 day');
            $aux_pd = $aux_primer_dia;

            $aux_pd->modify('Monday this week');
            $primero = $aux_pd->format("Y-m-d");

            $aux_pd->modify('Sunday this week');
            $ultimo = $aux_pd->format("Y-m-d");
            $arreglo[$i] = array($primero, $ultimo);
            $i++;
        }

        
        $arreglo_values = null;
        foreach ($arreglo as $row) {
            
            $cantidad_clientes_distintos_mes=0;
            $resultado_clientes_mes = Clientes::cantidad_clientes_autenticados_por_dia($row[0], $row[1]);
            
            foreach ($resultado_clientes_mes as $row1) {
                $cantidad_clientes_distintos_mes += $row1['cantidad'];
            }
            
            
            $arreglo_nuevo = null;
            $arreglo_nuevo['fecha'] = $row[0];
            $arreglo_nuevo['cantidad'] = $cantidad_clientes_distintos_mes;
            $arreglo_values[] = $arreglo_nuevo;
            
        }
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($arreglo_values);
        exit;
    }

}
