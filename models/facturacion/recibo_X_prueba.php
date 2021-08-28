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
$cod_inmueble=$_REQUEST["cod_inmueble"];
$direccion_cliente=$_REQUEST["direccion_cliente"];	
$direccion_inmueble=$_REQUEST["direccion_inmueble"];


$p = new stdClass;
/*
$p->conexion = new stdClass;
$p->conexion->host = $datos[11];
$p->conexion->port = $datos[12];

$p->ifiscal = new stdClass;
$p->ifiscal->modelo = $datos[13];
$p->ifiscal->firmware = $datos[14];
*/

$p->conexion = new stdClass;
$p->conexion->host = '192.168.1.110';
$p->conexion->port = 1600;

$p->ifiscal = new stdClass;
$p->ifiscal->modelo = "P-1120F";
$p->ifiscal->firmware = 1.00;

$p->documento = new stdClass;
$p->documento->tipo = "x";
$p->documento->estacion = "S"; //"S"= carro ancho/pagina completa, "T" = tickera.
$p->documento->identificacion = "Recibo.Nro. 123";//"Recibo.Nro. 123"

/
$p->cliente = new stdClass;
$p->cliente->nombre =  $nombre;
$p->cliente->domicilio = $direccion_cliente;
$p->cliente->responsabilidadIVA = "I";
$p->cliente->nrodoc = "20238083029";
$p->cliente->tipodoc = "C"; //C=CUIT L=CUIL 0=LIBRETA DE ENROLAMINETO, 1=LIBRETA CIVICA, 2=DNI, 3=PASAPORTA 4=CEDULA DE IDENTIDAD (CEL FEDERAL)
*/

$p->cliente->nombre = "ABUD JOSE MIGUEL";
$p->cliente->domicilio = "ALEM 126 LB";
$p->cliente->responsabilidadIVA = "I";
$p->cliente->nrodoc = "20238083029";
$p->cliente->tipodoc = "C";

$p->detalle = array();
$item = new stdClass;

//$item->descrip = "Período: ".$periodo." CódInmueble: ".$cod_inmueble." Dirección: ".$direccion_inmueble;
$item->descrip = "31902 - Wepel - WEPEL ENDUIDO PLASTIK INTERIOR - BLANCO - 20,000lts";

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




//$p->texto_recibo = "Pago por "."Período: ".$periodo." CódInmueble: ".$cod_inmueble." Dirección: ".$direccion_inmueble;
$p->texto_recibo = "Pago por ";

$a = array($p);
$IFiscal = new class_IFiscal;
$resultado = $IFiscal->method_documento_no_fiscal_homologado($a, null);
//echo json_encode($resultado);

Grabar_Respuesta_Impresora(json_encode($resultado),"recibo X", $monto);

?>
