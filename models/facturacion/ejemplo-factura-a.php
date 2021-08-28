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

//p->cliente->responsabilidadIVA tiene que ser "I",
// $p->cliente->tipodoc tiene que ser "C" y $p->cliente->nrodoc tiene que ser el CUIT


$p->cliente = new stdClass;
$p->cliente->nombre = "Alejandro Paz";
$p->cliente->domicilio = "Andrés Rojas 471";
$p->cliente->responsabilidadIVA = "I";
$p->cliente->nrodoc = "30241421";
$p->cliente->tipodoc = "2";

$p->detalle = array();
//while
$item = new stdClass;
$item->descrip = "3776 - 3M ARGENTIÑA - LIJAS MADERA Y PAR";
$item->cantidad = 1;
$item->monto = 1.63;
$item->iva = "21.00";
$item->imputacion = "M";
$item->impuesto_interno = 0;
$item->calificador_monto = "T";
$p->detalle[] = $item;
//cierro while

$p->descuento_gral = new stdClass;
$p->descuento_gral->descrip = "Desc. Gral.";
$p->descuento_gral->monto = 0.03;
$p->descuento_gral->imputacion = "m";
$p->descuento_gral->calificador_monto = "T";

$p->pago = array();
//pagos
$item = new stdClass;
$item->descrip = "Efectivo";
$item->monto = 1.60;
$p->pago[] = $item;

$item = new stdClass;
$item->descrip = "Tarjeta";
$item->monto = 221.60;
$p->pago[] = $item;
//ccierro while



$a = array($p);
$IFiscal = new class_IFiscal;
$resultado = $IFiscal->method_factura($a, null);
echo json_encode($resultado);

?>
