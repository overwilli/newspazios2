<?php

include("../config.php");
include("../mainfile.php");
if($_SESSION['datosUsuario'][0]=="") Header("Location:../index.php");
$datos=SesionUsuario();

require("IFiscal.php");

$fecha_desde=explode("-",$_REQUEST["fecha_inicio"]);
$fecha_desde=  substr($fecha_desde[2],2,2).$fecha_desde[1].$fecha_desde[0];
$fecha_hasta=explode("-",$_REQUEST["fecha_fin"]);
$fecha_hasta=  substr($fecha_hasta[2],2,2).$fecha_hasta[1].$fecha_hasta[0];
$tipo=$_REQUEST["dni"];
//$monto_sin_iva=$_REQUEST["monto_sin_iva"];
//$monto=$_REQUEST["monto"];
//$periodo=$_REQUEST["periodo"];
//$cod_inmueble=$_REQUEST["cod_inmueble"];
//$direccion_cliente=$_REQUEST["direccion_cliente"];	
//$direccion_inmueble=$_REQUEST["direccion_inmueble"];

$p = new stdClass;

$p->conexion = new stdClass;
$p->conexion->host = $datos[11];
$p->conexion->port = $datos[12];

$p->ifiscal = new stdClass;
$p->ifiscal->modelo = $datos[13];
$p->ifiscal->firmware = $datos[14];

$p->auditoria = new stdClass;

//$p->auditoria->fecha_desde = "131024";
$p->auditoria->fecha_desde=$fecha_desde;
//$p->auditoria->fecha_hasta = "131024";
$p->auditoria->fecha_hasta = $fecha_hasta;

$p->auditoria->tipo = "T";


/*$p->documento = new stdClass;
$p->documento->tipo = "A";
$p->documento->estacion = "S"; //"S"= carro ancho/pagina completa, "T" = tickera.

$p->cliente = new stdClass;
$p->cliente->nombre =  $nombre;
$p->cliente->domicilio = $direccion_cliente;
$p->cliente->responsabilidadIVA = "I";
$p->cliente->nrodoc = $cuil;
$p->cliente->tipodoc = "C"; //C=CUIT L=CUIL 0=LIBRETA DE ENROLAMINETO, 1=LIBRETA CIVICA, 2=DNI, 3=PASAPORTA 4=CEDULA DE IDENTIDAD (CEL FEDERAL)

$p->detalle = array();
$item = new stdClass;
//$item->descrip = "Alquiler del mes: ".$periodo." CódInmueble: ".$cod_inmueble." Dirección: ".$direccion_inmueble;
$item->descrip = "Alquiler del mes: ".$periodo.", Dirección: ".$direccion_inmueble;
$item->cantidad = 1;
$item->monto = $monto; //-----------MONTO SIN IVA?
if($monto>1500){//AGREGADO POR PEDIDO DEL USUARIO
	$item->iva = "21.00";
}else{
	$item->iva = "0";
}
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
$p->pago[] = $item;*/


$a = array($p);
$IFiscal = new class_IFiscal;
$resultado = $IFiscal->method_reporte_auditoria($a,null);
//echo json_encode($resultado);

Grabar_Respuesta_Impresora(json_encode($resultado),"reporte auditoria ", $monto);

?>
