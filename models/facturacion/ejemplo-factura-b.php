<?php
require("IFiscal.php");


$p = new stdClass;

$p->conexion = new stdClass;
$p->conexion->host = '10.0.0.4';
$p->conexion->port = 1600;


$p->ifiscal = new stdClass;
$p->ifiscal->modelo = "P-1120F";
$p->ifiscal->firmware = 1.00;

$p->estacion = "S"; //$p->estacion: alfanumerico: "S"= carro ancho/pagina completa, "T" = tickera.

$p->vendedor = "Ramon";

$p->cliente = new stdClass;
$p->cliente->nombre = "Alejandro Paz";
$p->cliente->domicilio = "Andrés Rojas 471 Ñ";
$p->cliente->responsabilidadIVA = "C";
$p->cliente->nrodoc = "30241421";
$p->cliente->tipodoc = "2";

$p->detalle = array();
$item = new stdClass;
$item->descrip = "3776 - 3M ARGENTINA - LIJAS MADERA Y PAR";
$item->cantidad = 1;
$item->monto = 1.63;
$item->iva = "21.00";
$item->imputacion = "M";
$item->impuesto_interno = 0;
$item->calificador_monto = "T";
$p->detalle[] = $item;

$p->descuento_gral = new stdClass;
$p->descuento_gral->descrip = "Desc. Gral.";
$p->descuento_gral->monto = 0.03;
$p->descuento_gral->imputacion = "m";
$p->descuento_gral->calificador_monto = "T";

$p->pago = array();
$item = new stdClass;
$item->descrip = "Efectivo";
$item->monto = 1.60;
$p->pago[] = $item;




$a = array($p);
$IFiscal = new class_IFiscal;
$resultado = $IFiscal->method_factura($a, null);
echo json_encode($resultado);

?>
