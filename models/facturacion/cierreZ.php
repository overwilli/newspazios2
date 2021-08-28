<?php

include("../config.php");
include("../mainfile.php");
if($_SESSION['datosUsuario'][0]=="") Header("Location:../index.php");
$datos=SesionUsuario();

require("IFiscal.php");

$p = new stdClass;

$p->conexion = new stdClass;
$p->conexion->host = $datos[11];
$p->conexion->port = $datos[12];


$p->ifiscal = new stdClass;
$p->ifiscal->modelo = $datos[13];
$p->ifiscal->firmware = $datos[14];

$a = array($p);
$IFiscal = new class_IFiscal;
$resultado = $IFiscal->method_cierreZ($a, null);
//echo json_encode($resultado);

Grabar_Respuesta_Impresora(json_encode($resultado),"Cierre Z", $monto);

?>