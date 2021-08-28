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
$cod_inmueble = $_REQUEST["cod_inmueble"];
$id_liquidacion=$_REQUEST["id_liquidacion"];
$direccion_cliente = $_REQUEST["direccion_cliente"];
$direccion_inmueble = $_REQUEST["direccion_inmueble"];
$concepto = $_REQUEST["concepto"];
if (!empty($_REQUEST['fecha_pago'])) {
    $fecha_pago = $_REQUEST['fecha_pago'];
}

$numero_recibo = '' . Ultimo_recibo_D();
$concepto = " pago por período: " . $periodo . " Código: " . $cod_inmueble;
//$concepto=$concepto. " período: ".$periodo." Dirección: ".$direccion_inmueble;
?>	
<link href="estilos2.css" type="text/css" rel="stylesheet"/>

<style type="text/css">
    <!--
    body {
        margin-left: 0px;
        margin-top: 0px;
    }
    .tipo_doc{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 20px;
        font-weight: bold;
        color: #333333;
        float: left;
        text-align: right;
        width: 370px;
        padding: 10px;
        vertical-align: middle;
        height: 45px;
    }
    .letra{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 40px;
        color: #000000;
        float: left;
        font-weight: bold;
        padding: 10px;
        border-top-width: thin;
        border-right-width: thin;
        border-bottom-width: thin;
        border-left-width: thin;
        border-top-style: solid;
        border-right-style: solid;
        border-bottom-style: solid;
        border-left-style: solid;
        border-top-color: #333333;
        border-right-color: #333333;
        border-bottom-color: #333333;
        border-left-color: #333333;
        height: 45px;
    }
    -->
</style>
<br />
<table width="800" border="1" cellpadding="0" cellspacing="0" bordercolor="#333333">
    <tr><td>
            <table width="100%" border="0" cellpadding="2" cellspacing="2">
                <tr>
                    <td colspan="2" rowspan="3">
                        <!--
                        <img src="../imagenes/logo_spazio.png" width="248" height="118" />
                        -->
                        <div class="tipo_doc">RECIBO</div>
                        <div class="letra">D</div>		</td>

                    <td bgcolor="#F3F3F3"></td>
                    <td bgcolor="#F3F3F3"><div align="right">ORIGINAL</div></td>
                </tr>
                <tr>
                    <td align="right" width="109" bgcolor="#F3F3F3"><b>Fecha:</b></td>
                    <td width="214" bgcolor="#F3F3F3"><?php
                        if (!empty($fecha_pago)) {
                            $fecha_y_hora_actual = $fecha_pago;
                        } else {
                            $fecha_y_hora_actual = date("Y-m-d");
                        }
                        print Fecha_en_Castellano($fecha_y_hora_actual);
                        ?></td>
                </tr>
                <tr>
                    <td valign="top" bgcolor="#F3F3F3"><div align="right"><strong>Recibo n&deg; </strong></div></td>
                    <td valign="top" bgcolor="#F3F3F3"><?php print $numero_recibo; ?></td>
                </tr>

                <tr>
                    <td colspan="4"><hr /></td>
                </tr>

                <tr>
                    <td width="247"><div align="right"><strong>Recibimos del se&ntilde;or/a :</strong></div></td>
                    <td colspan="3"><?php print $nombre; ?></td>
                </tr>



                <tr>
                    <td><div align="right"><strong>la cantidad de:</strong></div></td>
                    <td colspan="3"><?php print $monto; ?></td>
                </tr>
                <tr>
                    <td><div align="right"><strong>en concepto de : </strong></div></td>
                    <td colspan="3"><?php print $concepto; ?></td>
                </tr>
                <tr>
                    <td colspan="4"><div align="center">Recibo por cuenta de terceros, no v&aacute;lido como factura </div></td>
                </tr>
                <tr>
                    <td colspan="4"><div align="center"></div></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">..............................................................................................................................</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3"><div align="center">Firma</div></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                </tr>
            </table>

        </td></tr></table>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br /><br />
<br />
<div  style="position:absolute; width:400px; height:150px; z-index:1; left: 0px; top: 0px; 
      visibility:hidden;">
    <?php 
    Grabar_Respuesta_Impresora($concepto, "recibo común", $monto,$id_liquidacion,$numero_recibo,"D");
    ?>
</div>
<script language="JavaScript">window.print();</script>