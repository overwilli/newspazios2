<?php



namespace app\models;

use Yii;
use class_IFiscal;


class ImpresoraFiscal extends \yii\base\Model
{
    /*public $liquidacion_id;
    public $cliente_id;
    public $dni;
    public $cuil;
    public $direccion;
    public $monto;
    public $descripcion;*/

    /*public function rules()
    {
        return [
			[['liquidacion_id','cliente_id','dni','cuil','direccion','monto'], 'required'],
            [['liquidacion_id','cliente_id',], 'integer'],            
            [['monto'], 'number'],
            [['descripcion','cuil','direccion'], 'string'],
        ];
    }
	
	public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'liquidacion_id' => 'Cliente-Liquidacion',
            'dni' => 'DNI',
            'cuil' => 'CUIL',
            'direccion' => 'DIRECCION',
            'descripcion' => 'DESCRIPCION',
            'monto' => 'Importe',            
        ];
	}*/
	
	public function factura_a($param){
		$model = A2ParametrosGlobales::findOne(1);
		if(!$model){
			throw new Exception("No se pudo obtener datos para inicializar impresion");
		}
		
		$p = new \stdClass;

		$p->conexion = new \stdClass;
		$p->conexion->host = $model->impresora_host;
		$p->conexion->port = $model->impresora_puerto;	
		
		$p->copias=null;
		$p->encabezado=null;
		$p->cola=null;
		
		$p->ifiscal = new \stdClass;
		$p->ifiscal->modelo = $model->impresora_modelo;
		$p->ifiscal->firmware = $model->impresora_firmware;		
		
		$p->documento = new \stdClass;
		$p->documento->tipo = "A";
		$p->documento->estacion = "S"; //"S"= carro ancho/pagina completa, "T" = tickera.
		
		$p->cliente = new \stdClass;		
		$p->cliente->nombre =  $param['nombre'];
		$p->cliente->domicilio = $param['direccion_cliente'];
		$p->cliente->responsabilidadIVA = "I";
		$p->cliente->nrodoc = $param['cuil'];
		$p->cliente->tipodoc = "C"; //C=CUIT L=CUIL 0=LIBRETA DE ENROLAMINETO, 1=LIBRETA CIVICA, 2=DNI, 3=PASAPORTA 4=CEDULA DE IDENTIDAD (CEL FEDERAL)
		
		$p->detalle = array();
		$item = new \stdClass;
		//$item->descrip = "Alquiler del mes: ".$periodo." CódInmueble: ".$cod_inmueble." Dirección: ".$direccion_inmueble;
		$item->descrip = "Alquiler del mes: " .$param['periodo'].", Direccion: ".$param['direccion_inmueble'];
		$item->cantidad = 1;
		$item->monto = $param['monto']; //-----------MONTO SIN IVA?		
		if ($param['monto'] > 1501) {
			$item->iva = "21.00";
		} else {
			$item->iva = "0";
		}
		$item->imputacion = "M";
		$item->impuesto_interno = 0;
		$item->calificador_monto = "T";
		$p->detalle[] = $item;
		
		
		$p->descuento_gral = new \stdClass;
		$p->descuento_gral->descrip = "Importe";
		$p->descuento_gral->monto = 0.00;
		$p->descuento_gral->imputacion = "m";
		$p->descuento_gral->calificador_monto = "T";
		
		$p->pago = array();
		$item = new \stdClass;
		$item->descrip = "Efectivo";
		$item->monto = $param['monto'];
		$p->pago[] = $item;
		
		
		$a = array($p);
		Yii::$classMap['class_IFiscal'] = dirname(__FILE__).'/facturacion/IFiscal.php';
		$IFiscal = new class_IFiscal;
		//$resultado = $IFiscal->method_documento_fiscal($a, null);
		//echo json_encode($resultado);
		
		//Grabar_Respuesta_Impresora(json_encode($resultado), "factura A", $monto, $id_liquidacion);
		$resultado='[["SetCustomerData",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]}],["OpenFiscalReceipt",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]},"00001431"],["PrintFiscalText",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]}],["PrintLineItem",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]}],["GeneralDiscount",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]}],["Subtotal",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]},"1.0000","84881.50","14731.50","0.00","0.00","0.00"],["TotalTender",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]},"0.00"],["CloseFiscalReceipt",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]},"00001431","1","6356-3231635484"]]';
		return $resultado;
	}

	public function factura_b($param){
		$model = A2ParametrosGlobales::findOne(1);
		if(!$model){
			throw new Exception("No se pudo obtener datos para inicializar impresion");
		}
		
		$p = new \stdClass;		

		$p->conexion = new \stdClass;
		$p->conexion->host = $model->impresora_host;
		$p->conexion->port = $model->impresora_puerto;	
		
		$p->copias=null;
		$p->encabezado=null;
		$p->cola=null;
		
		$p->ifiscal = new \stdClass;
		$p->ifiscal->modelo = $model->impresora_modelo;
		$p->ifiscal->firmware = $model->impresora_firmware;		
		
		
		$p->documento = new \stdClass;
		$p->documento->tipo = "B";
		$p->documento->estacion = "S"; //"S"= carro ancho/pagina completa, "T" = tickera.
		
		//$p->vendedor = $datos[0];
		
		$p->cliente = new \stdClass;
		$p->cliente->nombre =  $param['nombre'];
		$p->cliente->domicilio = $param['direccion_cliente'];
		$p->cliente->responsabilidadIVA = "C";
		$p->cliente->nrodoc =  $param['dni'];
		$p->cliente->tipodoc = "2"; //C=CUIT L=CUIL 0=LIBRETA DE ENROLAMINETO, 1=LIBRETA CIVICA, 2=DNI, 3=PASAPORTA 4=CEDULA DE IDENTIDAD (CEL FEDERAL)
		
		
		$p->detalle = array();
		$item = new \stdClass;
		//$item->descrip = "Período: ".$periodo." CódInmueble: ".$cod_inmueble." Dirección: ".$direccion_inmueble;
		$item->descrip = "Alquiler del mes: ".$param['periodo'].", Direccion: ".$param['direccion_inmueble'];
		$item->cantidad = 1;
		$item->monto = $param['monto']; //-----------MONTO SIN IVA?
		if ($param['monto'] > 1501) {
			$item->iva = "21.00";
		} else {
			$item->iva = "0";
		}
		$item->imputacion = "M";
		$item->impuesto_interno = 0;
		$item->calificador_monto = "T";
		$p->detalle[] = $item;
		
		
		$p->descuento_gral = new \stdClass;
		$p->descuento_gral->descrip = "Desc. Gral.";
		$p->descuento_gral->monto = 0.00;
		$p->descuento_gral->imputacion = "m";
		$p->descuento_gral->calificador_monto = "T";
		
		
		$p->pago = array();
		$item = new \stdClass;
		$item->descrip = "Efectivo";
		$item->monto = $param['monto']; //-------------MONTO TOTAL
		$p->pago[] = $item;
		
		
		$a = array($p);
		Yii::$classMap['class_IFiscal'] = dirname(__FILE__).'/facturacion/IFiscal.php';
		$IFiscal = new class_IFiscal;
		//$resultado = $IFiscal->method_documento_fiscal($a, null);
		$resultado='[["SetCustomerData",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]}],["OpenFiscalReceipt",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]},"00000672"],["PrintFiscalText",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]}],["PrintLineItem",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]}],["GeneralDiscount",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]}],["Subtotal",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]},"1.0000","2420.00","420.00","0.00","0.00","0.00"],["TotalTender",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]},"0.00"],["CloseFiscalReceipt",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]},"00000672","1","xxxx-xxxxxxxxxx"]]';
		return utf8_encode($resultado);
	}
	
	public function nota_credito($param) {
		$model = A2ParametrosGlobales::findOne(1);
		if(!$model){
			throw new Exception("No se pudo obtener datos para inicializar impresion");
		}
		
		$p = new \stdClass;

		$p->conexion = new \stdClass;
		$p->conexion->host = $model->impresora_host;
		$p->conexion->port = $model->impresora_puerto;
		
		$p->copias=null;
		$p->encabezado=null;
		$p->cola=null;

		$p->ifiscal = new \stdClass;
		$p->ifiscal->modelo = $model->impresora_modelo;
		$p->ifiscal->firmware = $model->impresora_firmware;

		$p->documento = new \stdClass;
		$p->documento->tipo = "R";
		$p->documento->estacion = "S"; //"S"= carro ancho/pagina completa, "T" = tickera.

		$p->cliente = new \stdClass;
		$p->cliente->nombre =  $param['nombre'];
		$p->cliente->domicilio = $param['direccion_cliente'];
		$p->cliente->responsabilidadIVA = "I";
		$p->cliente->nrodoc = $param['cuil'];
		$p->cliente->tipodoc = "C"; //C=CUIT L=CUIL 0=LIBRETA DE ENROLAMINETO, 1=LIBRETA CIVICA, 2=DNI, 3=PASAPORTA 4=CEDULA DE IDENTIDAD (CEL FEDERAL)


		$p->documento_vinculado = array();
		$p->documento_vinculado[0] = $param['documento_vinculado'];


		$p->detalle = array();
		$item = new \stdClass;
		$item->descrip = $param['descripcion'];
		$item->cantidad = 1;
		$item->monto = $param['monto']; //-----------MONTO SIN IVA?
		$item->iva = "21.00";
		$item->imputacion = "M";
		$item->impuesto_interno = 0;
		$item->calificador_monto = "T";
		$p->detalle[] = $item;

		$p->descuento_gral = new \stdClass;
		$p->descuento_gral->descrip = "Importe";
		$p->descuento_gral->monto = 0.00;
		$p->descuento_gral->imputacion = "m";
		$p->descuento_gral->calificador_monto = "T";


		$p->pago = array();
		$item = new \stdClass;
		$item->descrip = "Efectivo";
		$item->monto = $param['monto'];
		$p->pago[] = $item;

		//var_dump($p);
		$a = array($p);
		//require("facturacion/IFiscal.php");
		//echo dirname(__FILE__).'/facturacion/class_IFiscal.php';exit;
		Yii::$classMap['class_IFiscal'] = dirname(__FILE__).'/facturacion/IFiscal.php';
		$IFiscal = new class_IFiscal;
		//$resultado = $IFiscal->method_documento_no_fiscal_homologado($a, null);
		$resultado='[["SetCustomerData",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]}],["SetEmbarkNumber",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]}],["OpenDNFH",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]},"00000080"],["PrintLineItem",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]}],["GeneralDiscount",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]}],["Subtotal",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]},"1.0000","3085.50","535.50","0.00","0.00","0.00"],["TotalTender",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"A620","bits":[{"bit":5,"descrip":"Comando no vu00e1lido para el estado fiscal actual."}]}],["CloseDNFH",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]},"00000080","1","1879-1697648586"]]';
		return $resultado;
	}
	
	public function cierre_x() {
		$model = A2ParametrosGlobales::findOne(1);
		if(!$model){
			throw new Exception("No se pudo obtener datos para inicializar impresion");
		}
		
		$p = new \stdClass;
		
		$p->conexion = new \stdClass;
		$p->conexion->host = $model->impresora_host;
		$p->conexion->port = $model->impresora_puerto;


		$p->ifiscal = new \stdClass;
		$p->ifiscal->modelo = $model->impresora_modelo;
		$p->ifiscal->firmware = $model->impresora_firmware;

		$a = array($p);
		Yii::$classMap['class_IFiscal'] = dirname(__FILE__).'/facturacion/IFiscal.php';
		$IFiscal = new class_IFiscal;
		//$resultado = $IFiscal->method_cierreX($a, null);
		//echo json_encode($resultado);

		//Grabar_Respuesta_Impresora(json_encode($resultado),"Cierre X", $monto);
		$resultado=utf8_encode('[["DailyClose",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]},"1","0","0","1","0","0","355","1473","0.00","0.00","0.00","0.00","0.00","0","82","0.00","0.00","0.00","0.00","0.00","0","0","0","0","0","0"]]');
		return $resultado;
	}
	
	public function cierre_z() {
		$model = A2ParametrosGlobales::findOne(1);
		if(!$model){
			throw new Exception("No se pudo obtener datos para inicializar impresion");
		}
		
		$p = new \stdClass;
		
		$p->conexion = new \stdClass;
		$p->conexion->host = $model->impresora_host;
		$p->conexion->port = $model->impresora_puerto;


		$p->ifiscal = new \stdClass;
		$p->ifiscal->modelo = $model->impresora_modelo;
		$p->ifiscal->firmware = $model->impresora_firmware;

		$a = array($p);
		Yii::$classMap['class_IFiscal'] = dirname(__FILE__).'/facturacion/IFiscal.php';
		$IFiscal = new class_IFiscal;
		//$resultado = $IFiscal->method_cierreZ($a, null);
		//echo json_encode($resultado);

		//Grabar_Respuesta_Impresora(json_encode($resultado),"Cierre X", $monto);
		$resultado=utf8_encode('[["DailyClose",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]},"736","0","0","0","4","0","674","1927","10550.00","1050.00","0.00","0.00","0.00","0","99","0.00","0.00","0.00","0.00","0.00","0","0","2","2","0","0"]]');
		return $resultado;
	}


}