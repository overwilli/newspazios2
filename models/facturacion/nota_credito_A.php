<?php

include("../config.php");
include("../mainfile.php");
if($_SESSION['datosUsuario'][0]=="") Header("Location:../index.php");
$datos=SesionUsuario();

require("IFiscal.php");

$nombre=$_REQUEST["nombre_cliente"];
$cuil=$_REQUEST["cuil"];
$dni=$_REQUEST["dni"];
$monto_sin_iva=$_REQUEST["monto_sin_iva"];
$monto=$_REQUEST["monto"];
$periodo=$_REQUEST["periodo"];
$cod_inmueble=$_REQUEST["cod_inmueble"];
$direccion_cliente=$_REQUEST["direccion"];	
$direccion_inmueble=$_REQUEST["direccion_inmueble"];
$documento_vinculado=$_REQUEST["documento_vinculado"];
$descripcion=$_REQUEST["descripcion"];
$liquidacion_id=$_REQUEST["liquidacion_id"];
if(empty($liquidacion_id)){
    $liquidacion_id=0;
}


$p = new stdClass;

$p->conexion = new stdClass;
$p->conexion->host = $datos[11];
$p->conexion->port = $datos[12];

$p->ifiscal = new stdClass;
$p->ifiscal->modelo = $datos[13];
$p->ifiscal->firmware = $datos[14];

$p->documento = new stdClass;
$p->documento->tipo = "R";
$p->documento->estacion = "S"; //"S"= carro ancho/pagina completa, "T" = tickera.

$p->cliente = new stdClass;
$p->cliente->nombre =  $nombre;
$p->cliente->domicilio = $direccion_cliente;
$p->cliente->responsabilidadIVA = "I";
$p->cliente->nrodoc = $cuil;
$p->cliente->tipodoc = "C"; //C=CUIT L=CUIL 0=LIBRETA DE ENROLAMINETO, 1=LIBRETA CIVICA, 2=DNI, 3=PASAPORTA 4=CEDULA DE IDENTIDAD (CEL FEDERAL)


$p->documento_vinculado = array();
$p->documento_vinculado[0] = $documento_vinculado;


$p->detalle = array();
$item = new stdClass;
$item->descrip = $descripcion;
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


$a = array($p);
$IFiscal = new class_IFiscal;
$resultado = $IFiscal->method_documento_no_fiscal_homologado($a, null);
//echo json_encode($resultado);
Grabar_Respuesta_Impresora(json_encode($resultado),"Nota de Crédito", $monto,$liquidacion_id);


//------------------MANDO A GRAVAR EN MOVIMIENTOS

$fecha_movimiento=date("Y-m-d H:i:s");
$importe_movimiento=$monto;
$selectmovimientos=9; //nota de credito
$observaciones_ingreso_egreso=$documento_vinculado.' - <br>'.$descripcion;
$enviado_desde="impresora fiscal";

$path='../caja_registrar_movimiento.php?importe_movimiento='.$monto.'&selectmovimientos='.$selectmovimientos.
	'&observaciones_ingreso_egreso='.$observaciones_ingreso_egreso.
	'&enviado_desde='.$enviado_desde;
?>
 <div id="Layer1" style="position:absolute; width:400px; height:150px; z-index:1; left: 10px; top: 10px; 
visibility:hidden; background-color: #CCCCCC; layer-background-color: #CCCCCC; border: 1px none #000000;"> 
  <iframe src="<?php print $path;?>" name="Iframe1" id="Iframe1" width="100%" height="250" frameborder="4">Cargando...</iframe>
</div> 