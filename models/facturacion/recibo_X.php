<?php
include("../config.php");
include("../mainfile.php");
if($_SESSION['datosUsuario'][0]=="") Header("Location:../index.php");
$datos=SesionUsuario();

require("IFiscal.php");

$nombre=$_REQUEST["nombre"];
$cuil=$_REQUEST["cuil"];
$dni=$_REQUEST["dni"];
$monto_sin_iva=$_REQUEST["monto_sin_iva"];
$monto=$_REQUEST["monto"];
$periodo=$_REQUEST["periodo"];
$id_liquidacion=$_REQUEST["id_liquidacion"];
$cod_inmueble=$_REQUEST["cod_inmueble"];
$direccion_cliente=$_REQUEST["direccion_cliente"];	
$direccion_inmueble=$_REQUEST["direccion_inmueble"];

$numero_recibo=''.Ultimo_recibo_X();

//veo de donde mandan 
$concepto_varios=$_REQUEST["conceptos_varios"];
$concepto_descripcion=$_REQUEST["concepto_descripcion"];

if ($concepto_varios==1)
	$descripcion_item_factura=$concepto_descripcion."<br> Cliente: ".$nombre;
else	
	$descripcion_item_factura="Pago periodo: ".$periodo." CodInmueble: ".$cod_inmueble." Direccion: ".$direccion_inmueble." Cliente: ".$nombre;


$p = new stdClass;

$p->conexion = new stdClass;
$p->conexion->host = $datos[11];
$p->conexion->port = $datos[12];

$p->ifiscal = new stdClass;
$p->ifiscal->modelo = $datos[13];
$p->ifiscal->firmware = $datos[14];


$p->documento = new stdClass;
$p->documento->tipo = "x";
$p->documento->estacion = "S";
$p->documento->identificacion = $numero_recibo;

$p->cliente = new stdClass;
$p->cliente->nombre =  $nombre;
$p->cliente->domicilio = $direccion_cliente;
$p->cliente->responsabilidadIVA = "I";
$p->cliente->nrodoc = "";
$p->cliente->tipodoc = "C"; //C=CUIT L=CUIL 0=LIBRETA DE ENROLAMINETO, 1=LIBRETA CIVICA, 2=DNI, 3=PASAPORTA 4=CEDULA DE IDENTIDAD (CEL FEDERAL)


$p->detalle = array();
$item = new stdClass;
$item->descrip =$descripcion_item_factura;
$item->cantidad = 1;
$item->monto = $monto; //-----------MONTO SIN IVA?
$item->iva = "21.00";
$item->imputacion = "M";
$item->impuesto_interno = 0;
$item->calificador_monto = "T";
$p->detalle[] = $item;

$p->descuento_gral = new stdClass;
$p->descuento_gral->descrip = "Importe";
$p->descuento_gral->monto = 0.00;
$p->descuento_gral->imputacion = "m";
$p->descuento_gral->calificador_monto = "T";

$p->pago = array();
$item = new stdClass;
$item->descrip = "Efectivo";
$item->monto = $monto;
$p->pago[] = $item;

$p->texto_recibo = $descripcion_item_factura;

$a = array($p);
$IFiscal = new class_IFiscal;
$resultado = $IFiscal->method_documento_no_fiscal_homologado($a, null);
//echo json_encode($resultado);
Grabar_Respuesta_Impresora(json_encode($resultado),"recibo X", $monto,$id_liquidacion,$numero_recibo,"X");
//$resultado_enviar='[["SetCustomerData",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"8610","bits":[{"bit":4,"descrip":"Datos no vu00e1lidos en un campo."}]}],["OpenDNFH",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]},"00000000"],["PrintFiscalText",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]}],["PrintLineItem",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]}],["GeneralDiscount",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]}],["Subtotal",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]},"1.0000","1500.00","0.00","0.00","0.00","0.00"],["TotalTender",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"A620","bits":[{"bit":5,"descrip":"Comando no vu00e1lido para el estado fiscal actual."}]}],["ReceiptText",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"2600","bits":[]}],["CloseDNFH",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]},"00000000","0","xxxx-xxxxxxxxxx"]]';
//Grabar_Respuesta_Impresora($resultado_enviar,"recibo X", $monto,$id_liquidacion,$numero_recibo,"X");
//---------------------REIMPRESION------------------------
$p = new stdClass;

$p->conexion = new stdClass;
$p->conexion->host = $datos[11];
$p->conexion->port = $datos[12];

$a = array($p);
$IFiscal = new class_IFiscal;

$resultado = $IFiscal->method_reimprimir_comprobante($a, null);
//echo json_encode($resultado);
//--------------------------------------------------------------------------

?>
