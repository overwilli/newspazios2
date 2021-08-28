<?php

namespace app\controllers;
ini_set('memory_limit',-1);
set_time_limit(0);

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Clientes;
use app\models\Utils;
use HTML2PDF;

class InformesController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'actions' => ['index', 'listado-clientes', 'listado-clientes-excel', 'padrones', 'padrones-excel',
                            'precio-locaciones', 'precio-locaciones-excel', 'morosos', 'morosos-excel', 'morosos-expensas',
                            'morosos-expensas-excel', 'intereses-mora', 'intereses-mora-excel', 'libro-ventas','resumen-cajas','resumen-cajas-excel',
                            'expensas-consorcio','expensas-consorcio-excel','expensas-cargadas',
                            'expensas-cargadas-excel','gestion-cobranzas','auditoria-por-propietario-excel'],
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

    public function actionListadoClientes() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        require_once('../models/reportes/template_clientes.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');
//    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('impresion_clientes.pdf');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionListadoClientesExcel() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        require_once('../models/reportes/excel/clientes.php');
        $content = ob_get_clean();
        echo $content;
    }

    public function actionPadrones() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        require_once('../models/reportes/template_padrones.php');
        $content = ob_get_clean();

        //require_once('../models/html2pdf_v4/html2pdf.class.php');
        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');
//    $html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('impresion_clientes.pdf');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionPadronesExcel() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        require_once('../models/reportes/excel/padrones.php');
        $content = ob_get_clean();
        echo $content;
    }

    public function actionPrecioLocaciones() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        require_once('../models/reportes/template_precio_locaciones.php');
        $content = ob_get_clean();


        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');

            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('impresion_clientes.pdf');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionPrecioLocacionesExcel() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        require_once('../models/reportes/excel/precio_locaciones.php');
        $content = ob_get_clean();
        echo $content;
    }

    public function actionMorosos() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        $query="SELECT * FROM (SELECT t1.id_liquidacion,t1.id_operacion,liq_anio,liq_mes,
        a2_noticias.direccion,a2_clientes.NOMBRE,'ALQUILER' AS tipo,0 as deuda FROM(SELECT * FROM a2_liquidaciones WHERE a2_liquidaciones.fecha_pago='0000-00-00 00:00:00'
        UNION
        SELECT DISTINCT a2_liquidaciones.* FROM a2_liquidaciones LEFT JOIN  a2_movimientos ON a2_liquidaciones.id_liquidacion=a2_movimientos.id_liquidacion 
        WHERE (tipo_registro='pago_pendiente'AND estado_pago_pendiente='pendiente'))
        AS t1 
        LEFT JOIN a2_operaciones_inmobiliarias ON 
                        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=t1.id_operacion
        LEFT JOIN a2_noticias ON
                        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_clientes ON
                        a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        WHERE a2_operaciones_inmobiliarias.estado='ACTIVO' 
        
        UNION 
        
        SELECT 0,deuda_expensas.operacion_id,deuda_expensas.`year`,deuda_expensas.`mes`,a2_noticias.direccion,a2_clientes.NOMBRE,
        'EXPENSAS' AS tipo, deuda_expensas.deuda FROM a2_noticias LEFT JOIN a2_operaciones_inmobiliarias ON
                        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                        LEFT JOIN a2_clientes ON
                         a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                        LEFT JOIN deuda_expensas ON
                         a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id
        WHERE fecha_ope<>'' AND deuda_expensas.deuda>0 GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,deuda_expensas.year 
        ) AS T2 ORDER BY direccion,liq_anio,liq_mes";

        $resultado= Yii::$app->db->createCommand($query)->queryAll();

        ob_start();
        require_once('../models/reportes/template_morosos.php');
        $content = ob_get_clean();


        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');

            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('informe_morosos.pdf');
            exit;
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionMorososExcel() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");

        ob_start();
        require_once('../models/reportes/excel/morosos.php');
        $content = ob_get_clean();
        echo $content;
    }

    public function actionMorososExpensas() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");
        require_once("../models/reportes/Liquidacion.php");

        ob_start();
        require_once('../models/reportes/template_morosos.php');
        $content = ob_get_clean();


        Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');

            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('impresion_clientes.pdf');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    public function actionMorososExpensasExcel() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");
        require_once("../models/reportes/Liquidacion.php");

        ob_start();
        require_once('../models/reportes/excel/morosos.php');
        $content = ob_get_clean();
        echo $content;
    }

    public function actionInteresesMora() {
        if (Yii::$app->request->post()) {
            
            $fecha_desde = Yii::$app->request->post('fecha_desde');
            $fecha_hasta = Yii::$app->request->post('fecha_hasta');
            $numero_caja_desde = Yii::$app->request->post('numero_caja_desde');
            $numero_caja_hasta = Yii::$app->request->post('numero_caja_hasta');

            $fecha_desde = explode("/", $fecha_desde);
            $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

            $fecha_hasta = explode("/", $fecha_hasta);
            $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];
            
            require_once("../models/reportes/config.php");
            require_once("../models/reportes/mainfile.php");
            require_once("../models/reportes/Movimiento.php");

           
            if(Yii::$app->request->post('formato')==1){//1-PDF;2-Excel
                ob_start();
                require_once('../models/reportes/template_intereses_mora.php');
                $content = ob_get_clean();


                Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

                try {
                    $html2pdf = new HTML2PDF('P', 'A4', 'es');

                    $html2pdf->setDefaultFont('Arial');
                    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
                    $html2pdf->Output('impresion_clientes.pdf');
                    exit;
                } catch (HTML2PDF_exception $e) {
                    echo $e;
                    exit;
                }
            }else{
                ob_start();
                require_once('../models/reportes/excel/intereses_mora.php');
                
                $content = ob_get_clean();
                echo $content;
                exit;
            }
        } else {
            return $this->render('interes_mora');
        }
    }

    /*public function actionInteresesMoraExcel() {
        if (Yii::$app->request->post()) {
            
            $fecha_desde = Yii::$app->request->post('fecha_desde');
            $fecha_hasta = Yii::$app->request->post('fecha_hasta');
            $numero_caja_desde = Yii::$app->request->post('numero_caja_desde');
            $numero_caja_hasta = Yii::$app->request->post('numero_caja_hasta');

            $fecha_desde = explode("/", $fecha_desde);
            $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

            $fecha_hasta = explode("/", $fecha_hasta);
            $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];
            
            require_once("../models/reportes/config.php");
            require_once("../models/reportes/mainfile.php");
            require_once("../models/reportes/Movimiento.php");

            ob_start();
            require_once('../models/reportes/excel/intereses_mora.php');
            
            $content = ob_get_clean();
            echo $content;
            exit;
        } else {
            return $this->render('interes_mora');
        }
    }*/

    public function actionLibroVentas() {
        if (Yii::$app->request->post()) {
            $fecha_desde = Yii::$app->request->post('fecha_desde');
            $fecha_hasta = Yii::$app->request->post('fecha_hasta');

            $fecha_desde = explode("/", $fecha_desde);
            $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

            $fecha_hasta = explode("/", $fecha_hasta);
            $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];

            require_once("../models/reportes/config.php");
            require_once("../models/reportes/mainfile.php");


            require_once("../models/reportes/Facturacion.php");
            require_once("../models/reportes/Cliente.php");
            require_once("../models/reportes/Liquidacion.php");
            require_once("../models/reportes/Operacion.php");

            ob_start();
            require_once('../models/reportes/template_libro_ventas.php');

            $content = ob_get_clean();


            Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

            try {
                $html2pdf = new HTML2PDF('P', 'A4', 'es');

                $html2pdf->setDefaultFont('Arial');
                $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
                $html2pdf->Output('impresion_clientes.pdf');
            } catch (HTML2PDF_exception $e) {
                echo $e;
                exit;
            }
        } else {
            return $this->render('libro_ventas');
        }
    }

    public function actionResumenCajas() {
        if (Yii::$app->request->post()) {
            $numero_caja_desde = Yii::$app->request->post('numero_caja_desde');
            $numero_caja_hasta = Yii::$app->request->post('numero_caja_hasta');
            $resumido = Yii::$app->request->post('resumido');
            $fecha_desde = Yii::$app->request->post('fecha_desde');
            $fecha_hasta = Yii::$app->request->post('fecha_hasta');

            $fecha_desde = explode("/", $fecha_desde);
            $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

            $fecha_hasta = explode("/", $fecha_hasta);
            $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];

            require_once("../models/reportes/config.php");
            require_once("../models/reportes/mainfile.php");

            require_once("../models/reportes/Cajas.php");
            //require_once("../models/reportes/Cliente.php");
            //require_once("../models/reportes/Liquidacion.php");
            //require_once("../models/reportes/Operacion.php");

            ob_start();
            require_once('../models/reportes/template_resumen_cajas.php');

            $content = ob_get_clean();


            Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

            try {
                $html2pdf = new HTML2PDF('P', 'A4', 'es');

                $html2pdf->setDefaultFont('Arial');
                $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
                $html2pdf->Output('impresion_clientes.pdf');
                exit;
            } catch (HTML2PDF_exception $e) {
                echo $e;
                exit;
            }
        } else {
            return $this->render('resumen_cajas');
        }
    }

    public function actionResumenCajasExcel() {
        require_once("../models/reportes/config.php");
        require_once("../models/reportes/mainfile.php");
        require_once("../models/reportes/Cliente.php");
        require_once("../models/reportes/Liquidacion.php");
        require_once("../models/reportes/Propiedad.php");
        require_once("../models/reportes/Expensas.php");
        require_once("../models/reportes/Cajas.php"); 
        if (Yii::$app->request->post()) {  
            $numero_caja_desde = Yii::$app->request->post('numero_caja_desde');
            $numero_caja_hasta = Yii::$app->request->post('numero_caja_hasta');
            $resumido = Yii::$app->request->post('resumido');
            $fecha_desde = Yii::$app->request->post('fecha_desde');
            $fecha_hasta = Yii::$app->request->post('fecha_hasta');

            $fecha_desde = explode("/", $fecha_desde);
            $fecha_desde = $fecha_desde[2] . "-" . $fecha_desde[1] . "-" . $fecha_desde[0];

            $fecha_hasta = explode("/", $fecha_hasta);
            $fecha_hasta = $fecha_hasta[2] . "-" . $fecha_hasta[1] . "-" . $fecha_hasta[0];
            
            ob_start();
            require_once('../models/reportes/excel/resumen_cajas.php');
            
            $content = ob_get_clean();
            echo $content;
            exit;
        } else {
            return $this->render('resumen_cajas');
        }
    }
	
	public function actionExpensasConsorcio() {
		if (Yii::$app->request->post()) { 
            $consorcio=Yii::$app->request->post('consorcio');
            $periodo=Yii::$app->request->post('periodo');
            $arreglo_periodo=null;
            if(!empty($periodo)){
                $arreglo_periodo=explode('/',$periodo);
            }
            
            $resultado=\app\models\OperacionesExpensas::obtener_expensas_by_grupo($consorcio,$arreglo_periodo);            
            ob_start();
            require_once('../models/reportes/template_expensas_consorcio.php');

            $content = ob_get_clean();


            Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

            try {
                $html2pdf = new HTML2PDF('P', 'A4', 'es');

                $html2pdf->setDefaultFont('Arial');
                $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
                $html2pdf->Output('impresion_clientes.pdf');
                exit;
            } catch (HTML2PDF_exception $e) {
                echo $e;
                exit;
            }

		}else{
			return $this->render('expensas_consorcio');
		}
    }

    public function actionExpensasConsorcioExcel() {
		if (Yii::$app->request->post()) { 
            $consorcio=Yii::$app->request->post('consorcio');
            $periodo=Yii::$app->request->post('periodo');
            $formato=Yii::$app->request->post('formato');
            $arreglo_periodo=null;
            if(!empty($periodo)){
                $arreglo_periodo=explode('/',$periodo);
            }
            $grupo=\app\models\A2Grupos::find()->where(['id_grupo'=>$consorcio])->one();
			
            $abonos_tipo="3,4,5,9";
            $resultado_abonos=\app\models\OperacionesExpensas::obtener_expensas_by_grupo_tipo($consorcio,
            $arreglo_periodo,$abonos_tipo);    
            
            $impuestos_tipo="1,2,6,7";
            $resultado_impuestos=\app\models\OperacionesExpensas::obtener_expensas_by_grupo_tipo($consorcio,
            $arreglo_periodo,$impuestos_tipo); 
            
            $gastos_tipo="8";
            $resultado_gastos=\app\models\OperacionesExpensas::obtener_expensas_by_grupo_tipo($consorcio,
            $arreglo_periodo,$gastos_tipo); 

            $honorarios_tipo="10";
            $resultado_honorarios=\app\models\OperacionesExpensas::obtener_expensas_by_grupo_tipo($consorcio,
            $arreglo_periodo,$honorarios_tipo); 
            
            if($formato=="excel"){
                ob_start();
                require_once('../models/reportes/excel/expensas_grupo.php');
                
                $content = ob_get_clean();
                echo $content;
                exit;
            }else{
                ob_start(); 
                require_once('../models/reportes/template_expensas_grupo.php');
                
                $content = ob_get_clean();


                Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

                try {
                    $html2pdf = new HTML2PDF('P', 'A4', 'es');

                    $html2pdf->setDefaultFont('Arial');
                    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
                    $html2pdf->Output('informe.pdf');
                    exit;
                } catch (HTML2PDF_exception $e) {
                    echo $e;
                    exit;
                }
            }

		}else{
			return $this->render('expensas_consorcio');
		}
    }
    
    
    public function actionExpensasCargadas() {
        
		if (Yii::$app->request->post()) {
            $periodo_desde=Yii::$app->request->post('periodo_desde'); 
            $periodo_hasta=Yii::$app->request->post('periodo_hasta'); 
            $arreglo_periodo=explode('/',$periodo_desde);
            
            $bandera=true;
            $periodos="";
            while($bandera){
                $periodos[]=$arreglo_periodo[0]."/".$arreglo_periodo[1];
                
                if(($arreglo_periodo[0]."/".$arreglo_periodo[1]) == $periodo_hasta){
                    $periodos[]=$arreglo_periodo[0]."/".$arreglo_periodo[1];
                    $bandera=false;
                }

                if($arreglo_periodo[0]==12){
                    $arreglo_periodo[0]=1;
                    $arreglo_periodo[1]+=1;
                }else{
                    $arreglo_periodo[0]+=1;
                }
                
            }          

            $query = "SELECT a2_noticias.direccion,a2_clientes.NOMBRE,a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,a2_clientes.id_cliente,a2_noticias.id_noticia FROM a2_operaciones_inmobiliarias INNER JOIN a2_noticias ON a2_operaciones_inmobiliarias.cod_propiedad=a2_noticias.id_noticia
INNER JOIN a2_clientes ON a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente
WHERE a2_operaciones_inmobiliarias.estado='ACTIVO' AND a2_operaciones_inmobiliarias.tiene_expensas=1
ORDER BY direccion";

            $connection = Yii::$app->getDb();
            $resultado = $connection->createCommand($query)->queryAll();
            
            //$resultado=\app\models\A2OperacionesInmobiliarias::find()->Where(['estado'=>'ACTIVO','tiene_expensas'=>1]);            
            ob_start(); 
            require_once('../models/reportes/template_expensas_cargadas.php');

            $content = ob_get_clean();


            Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

            try {
                $html2pdf = new HTML2PDF('P', 'A4', 'es');

                $html2pdf->setDefaultFont('Arial');
                $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
                $html2pdf->Output('impresion_clientes.pdf');
                exit;
            } catch (HTML2PDF_exception $e) {
                echo $e;
                exit;
            }

		}else{
			return $this->render('expensas_cargadas');
		}
    }

    public function actionExpensasCargadasExcel() {
        $show_inmueble=Yii::$app->request->get('inmueble');
		if (Yii::$app->request->post()) {
            $periodo_desde=Yii::$app->request->post('periodo_desde'); 
            $periodo_hasta=Yii::$app->request->post('periodo_hasta'); 
            $id_operacion=Yii::$app->request->post('id_operacion'); 
            $arreglo_periodo=explode('/',$periodo_desde);
            
            $bandera=true;
            $periodos="";
            while($bandera){
                $periodos[]=$arreglo_periodo[0]."/".$arreglo_periodo[1];
                
                if(($arreglo_periodo[0]."/".$arreglo_periodo[1]) == $periodo_hasta){
                    $bandera=false;
                }

                if($arreglo_periodo[0]==12){
                    $arreglo_periodo[0]=1;
                    $arreglo_periodo[1]+=1;
                }else{
                    $arreglo_periodo[0]+=1;
                }
                
            }

            $filtro_op="";
            if($id_operacion){
                $filtro_op=" AND a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=".$id_operacion;
            }
            
            $query = "SELECT a2_noticias.direccion,a2_clientes.NOMBRE,
            a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,a2_clientes.id_cliente,a2_noticias.id_noticia 
            FROM a2_operaciones_inmobiliarias INNER JOIN a2_noticias ON 
            a2_operaciones_inmobiliarias.cod_propiedad=a2_noticias.id_noticia
INNER JOIN a2_clientes ON a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente
WHERE a2_operaciones_inmobiliarias.estado='ACTIVO' AND a2_operaciones_inmobiliarias.tiene_expensas=1 
{$filtro_op}
ORDER BY direccion";

            $connection = Yii::$app->getDb();
            $resultado = $connection->createCommand($query)->queryAll();                   
            
            ob_start();
            require_once('../models/reportes/excel/expensas_cargadas.php');
            
            $content = ob_get_clean();
            echo $content;
            exit;

		}else{
			return $this->render('expensas_cargadas',['show_inmueble'=>$show_inmueble]);
		}
    }
    
    public function actionGestionCobranzas() {
        
		if (Yii::$app->request->post()) { 
            $fecha_desde=Yii::$app->request->post('fecha_desde');           
            $fecha_hasta=Yii::$app->request->post('fecha_hasta');
            $id_cliente=Yii::$app->request->post('id_cliente');
            $concepto=Yii::$app->request->post('concepto');
            $formato=Yii::$app->request->post('formato');
            $filtro="";
            
            if($concepto=="AMBOS"){
                $query="SELECT * FROM (
                    SELECT				
                                    a2_noticias.id_noticia, 
                                    a2_noticias.titulo, 
                                    a2_noticias.direccion,
                                    a2_noticias.fecha, 
                                    a2_noticias.foto, 
                                    a2_noticias.fotopie,
                                    a2_noticias.alq_vendida,				 				
                                    a2_secciones.seccion, 
                                    a2_objeto_de_propiedad.operacion,
                                    a2_operaciones_inmobiliarias.fecha_ope,
                                    a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
                                    a2_operaciones_inmobiliarias.hasta_anio,
                                    a2_operaciones_inmobiliarias.hasta_mes,
                                    a2_operaciones_inmobiliarias.dia_venc_mensual,	
                                    a2_operaciones_inmobiliarias.interes_dia_mora,
                                    a2_clientes.NOMBRE,
                                    a2_clientes.TELEFONO as telefono_cliente,
                                                    a2_clientes.TELEF2,
                                                    a2_clientes.TELEF3,
                                    'ALQUILER' as concepto
                                    FROM a2_operaciones_inmobiliarias 
                                    LEFT JOIN a2_noticias ON
                                    a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                                    LEFT JOIN a2_secciones	ON			
                                    a2_noticias.seccion=a2_secciones.id_seccion
                                    LEFT JOIN a2_objeto_de_propiedad ON
                                    a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
                                    LEFT JOIN a2_clientes ON
                                    a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente                                
                                    WHERE								
                                    (SELECT COUNT(id_liquidacion) FROM a2_liquidaciones WHERE a2_liquidaciones.id_operacion=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria
                                    AND a2_liquidaciones.fecha_pago='0000-00-00 00:00:00' )>0
                                    AND a2_secciones.sitio=1 
                    
                    UNION
                    SELECT				
                                    a2_noticias.id_noticia, 
                                    a2_noticias.titulo, 
                                    a2_noticias.direccion,
                                    a2_noticias.fecha, 
                                    a2_noticias.foto, 
                                    a2_noticias.fotopie,
                                    a2_noticias.alq_vendida,				 				
                                    a2_secciones.seccion, 
                                    a2_objeto_de_propiedad.operacion,
                                    a2_operaciones_inmobiliarias.fecha_ope,
                                    a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
                                    a2_operaciones_inmobiliarias.hasta_anio,
                                    a2_operaciones_inmobiliarias.hasta_mes,
                                    a2_operaciones_inmobiliarias.dia_venc_mensual,
                                    a2_operaciones_inmobiliarias.interes_dia_mora,	
                                    a2_clientes.NOMBRE,
                                    a2_clientes.TELEFONO as telefono_cliente,
                                                    a2_clientes.TELEF2,
                                                    a2_clientes.TELEF3,
                                    'EXPENSAS' as concepto
                                    FROM a2_operaciones_inmobiliarias 
                                    LEFT JOIN a2_noticias ON
                                    a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                                    LEFT JOIN a2_secciones	ON			
                                    a2_noticias.seccion=a2_secciones.id_seccion
                                    LEFT JOIN a2_objeto_de_propiedad ON
                                    a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
                                    LEFT JOIN a2_clientes ON
                                    a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente                                
                                    WHERE								
                                    (SELECT COUNT(id) FROM operaciones_expensas WHERE operaciones_expensas.operacion_id=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria
                                    AND estado='impago' )>0
                                    AND a2_secciones.sitio=1 
                        
                    ) as t ORDER BY direccion";
            }
            if($concepto=="ALQUILER"){
            $query = "SELECT				
				a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_operaciones_inmobiliarias.dia_venc_mensual,
                a2_operaciones_inmobiliarias.interes_dia_mora,
				a2_clientes.NOMBRE,
				a2_clientes.TELEFONO as telefono_cliente,
                                a2_clientes.TELEF2,
                                a2_clientes.TELEF3,
                'ALQUILER' as concepto
				FROM a2_operaciones_inmobiliarias 
				LEFT JOIN a2_noticias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				LEFT JOIN a2_clientes ON
				a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente                                
				WHERE								
				(SELECT COUNT(id_liquidacion) FROM a2_liquidaciones WHERE a2_liquidaciones.id_operacion=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria
				AND a2_liquidaciones.fecha_pago='0000-00-00 00:00:00' )>0
				AND a2_secciones.sitio=1 
ORDER BY direccion";
            }
            if($concepto=="EXPENSAS"){
                $query="SELECT				
				a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_operaciones_inmobiliarias.dia_venc_mensual,
                a2_operaciones_inmobiliarias.interes_dia_mora,
				a2_clientes.NOMBRE,
				a2_clientes.TELEFONO as telefono_cliente,
                                a2_clientes.TELEF2,
                                a2_clientes.TELEF3,
                'EXPENSAS' as concepto
				FROM a2_operaciones_inmobiliarias 
				LEFT JOIN a2_noticias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				LEFT JOIN a2_clientes ON
				a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente                                
				WHERE								
				(SELECT COUNT(id) FROM operaciones_expensas WHERE operaciones_expensas.operacion_id=a2_operaciones_inmobiliarias.id_operacion_inmobiliaria
				AND estado='impago' )>0
				AND a2_secciones.sitio=1 
ORDER BY direccion";
            }

            $connection = Yii::$app->getDb();
            $resultado = $connection->createCommand($query)->queryAll();

            if($formato=='PDF'){
               
                                    
                ob_start(); 
                require_once('../models/reportes/template_gestion_cobranzas.php');

                $content = ob_get_clean();


                Yii::$classMap['HTML2PDF'] = '../models/html2pdf_v4/html2pdf.class.php';

                try {
                    $html2pdf = new HTML2PDF('P', 'A4', 'es');

                    $html2pdf->setDefaultFont('Arial');
                    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
                    $html2pdf->Output('impresion_clientes.pdf');
                    exit;
                } catch (HTML2PDF_exception $e) {
                    echo $e;
                    exit;
                }
            }else{ 
                ob_start();
                //require_once('../models/reportes/excel/resumen_cajas.php');
                require_once('../models/reportes/excel/gestion_cobranzas.php');
                //echo "ingreso";exit;
                $content = ob_get_clean();
                echo $content;
                exit;
               
            }
		}else{
            $query="SELECT a2_clientes.id_cliente,a2_clientes.NOMBRE FROM a2_clientes ";
            
            $connection = Yii::$app->getDb();
            $resultado = $connection->createCommand($query)->queryAll();
			return $this->render('gestion_cobranzas',['clientes'=>$resultado]);
		}
    }
    
    public function actionAuditoriaPorPropietarioExcel() {
		if (Yii::$app->request->post()) {
            require_once("../models/reportes/config.php");
            require_once("../models/reportes/mainfile.php");
            require_once("../models/reportes/Propietario.php");
            require_once("../models/reportes/Propiedad.php");
            $propietario_id=Yii::$app->request->post('propietario_id');

            $query="SELECT * FROM (SELECT a2_noticias.direccion,a2_clientes.NOMBRE,liqpagadas_detalle.id,
			liqpagadas_detalle.liquidaciones_id,liq_anio,liq_mes,liqpagadas_detalle.dinero_cobrado,liqpagadas_detalle.interes_por_mora,fecha_pago,'ALQUILER' as tipo FROM liqpagadas_detalle INNER JOIN a2_liquidaciones ON 
			liqpagadas_detalle.liquidaciones_id=a2_liquidaciones.id_liquidacion INNER JOIN a2_operaciones_inmobiliarias ON
			(a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion) INNER JOIN a2_noticias ON
			(a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad) INNER JOIN a2_clientes ON 
			(a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente)
            INNER JOIN liquidacionpagadas ON liquidacionpagadas.id=liqpagadas_detalle.liquidacionpagadas_id
			  WHERE propietario_id={$propietario_id} AND liquidacionpagadas.estado='Pagado'
			UNION
			SELECT a2_noticias.direccion,a2_clientes.NOMBRE,operaciones_expensas.id,0 as liquidaciones_id,
			operaciones_expensas.`year` as liq_anio,operaciones_expensas.mes as liq_mes,operaciones_expensas.importe as dinero_cobrado,
			0 as interes_por_mora,a2_facturacion.fecha as fecha_pago,'EXPENSAS' AS tipo FROM 
			liqpagadas_expensas INNER JOIN operaciones_expensas ON 
			liqpagadas_expensas.expensa_id=operaciones_expensas.id INNER JOIN a2_operaciones_inmobiliarias ON
			(a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=operaciones_expensas.operacion_id) INNER JOIN a2_noticias ON
			(a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad) INNER JOIN a2_clientes ON 
			(a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente)
			LEFT JOIN a2_facturacion ON 
			(operaciones_expensas.comprobante_id=a2_facturacion.id_facturacion)
            INNER JOIN liquidacionpagadas ON liquidacionpagadas.id=liqpagadas_expensas.liquidacionpagadas_id
			  WHERE propietario_id={$propietario_id} AND liquidacionpagadas.estado='Pagado'
			)as t1 ORDER BY ";
			$query.=" direccion,fecha_pago ASC, liq_anio,liq_mes ASC";
            
            $connection = Yii::$app->getDb();
            $resultado = $connection->createCommand($query)->queryAll();

            ob_start();
            require_once('../models/reportes/excel/auditoria_por_propietario.php');
            $content = ob_get_clean();
            echo $content;
            exit;
        }else{
			return $this->render('auditoria_por_propietario');
		}
    }

}
