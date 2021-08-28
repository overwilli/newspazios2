<?php

include("../config.php");
include("../mainfile.php");
if ($_SESSION['datosUsuario'][0] == "")
    Header("Location:../index.php");
$datos = SesionUsuario();

require("IFiscal.php");

$nombre = $_REQUEST["nombre"];
$cuil = $_REQUEST["cuil"];
$dni = $_REQUEST["dni"];
$monto_sin_iva = $_REQUEST["monto_sin_iva"];
$monto = $_REQUEST["monto"];
$periodo = $_REQUEST["periodo"];
$id_liquidacion = $_REQUEST["id_liquidacion"];
$cod_inmueble = $_REQUEST["cod_inmueble"];
$direccion_cliente = $_REQUEST["direccion_cliente"];
$direccion_inmueble = $_REQUEST["direccion_inmueble"];

$p = new stdClass;

$p->conexion = new stdClass;
$p->conexion->host = $datos[11];
$p->conexion->port = $datos[12];

$p->ifiscal = new stdClass;
$p->ifiscal->modelo = $datos[13];
$p->ifiscal->firmware = $datos[14];

$p->documento = new stdClass;
$p->documento->tipo = "A";
$p->documento->estacion = "S"; //"S"= carro ancho/pagina completa, "T" = tickera.

$p->cliente = new stdClass;
$p->cliente->nombre = $nombre;
$p->cliente->domicilio = $direccion_cliente;
$p->cliente->responsabilidadIVA = "I";
$p->cliente->nrodoc = $cuil;
$p->cliente->tipodoc = "C"; //C=CUIT L=CUIL 0=LIBRETA DE ENROLAMINETO, 1=LIBRETA CIVICA, 2=DNI, 3=PASAPORTA 4=CEDULA DE IDENTIDAD (CEL FEDERAL)

$p->detalle = array();
$item = new stdClass;
//$item->descrip = "Alquiler del mes: ".$periodo." CódInmueble: ".$cod_inmueble." Dirección: ".$direccion_inmueble;
$item->descrip = "Alquiler del mes: " . $periodo . ", Direccion: " . $direccion_inmueble;
$item->cantidad = 1;
$item->monto = $monto; //-----------MONTO SIN IVA?
if ($monto > 1501) {
    $item->iva = "21.00";
} else {
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
$p->pago[] = $item;


$a = array($p);
$IFiscal = new class_IFiscal;
$resultado = $IFiscal->method_documento_fiscal($a, null);
//echo json_encode($resultado);

Grabar_Respuesta_Impresora(json_encode($resultado), "factura A", $monto, $id_liquidacion);
//$resultado_enviar='[["SetCustomerData",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]}],["OpenFiscalReceipt",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]},"00000520"],["PrintFiscalText",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]}],["PrintLineItem",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]}],["GeneralDiscount",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]}],["Subtotal",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]},"1.0000","1500.00","0.00","0.00","0.00","0.00"],["TotalTender",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"3600","bits":[]},"0.00"],["CloseFiscalReceipt",{"status_impresora":"C080","bits":[{"bit":14,"descrip":"Caju00f3n de dinero cerrado o ausente."}]},{"status_fiscal":"0600","bits":[]},"00000520","1","5244-2389109251"]]';
//Grabar_Respuesta_Impresora($resultado_enviar, "factura A", $monto, $id_liquidacion);
?>
