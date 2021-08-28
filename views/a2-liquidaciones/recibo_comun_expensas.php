<?php
use app\models\Utils;
use app\models\A2Liquidaciones;
use app\models\A2OperacionesInmobiliarias;
use app\models\A2ParametrosGlobales;
$formatter = \Yii::$app->formatter;


$concepto = " pago por expensas - C&oacute;digo: " . 
        $model_operacion->cod_propiedad;
if ($model_operacion) {    
    $nombre = $model_operacion->cliente->NOMBRE;
}


?>
<table width="800" border="1" cellpadding="0" cellspacing="0" bordercolor="#333333">
    <tr><td>
            <table width="100%" border="0" cellpadding="2" cellspacing="2">
                <tr>
                    <td colspan="2" rowspan="3">
                        <!--
                        <img src="../imagenes/logo_spazio.png" width="248" height="118" />
                        -->
                        <div class="tipo_doc">RECIBO</div>
                        <div class="letra">C</div>		</td>

                    <td bgcolor="#F3F3F3"></td>
                    <td bgcolor="#F3F3F3"><div align="right">ORIGINAL</div></td>
                </tr>
                <tr>
                    <td align="right" width="109" bgcolor="#F3F3F3"><b>Fecha:</b></td>
                    <td width="214" bgcolor="#F3F3F3"><?php
                        if (!empty($fecha_pago) && $fecha_pago!='0000-00-00 00:00:00') {
                            $fecha_y_hora_actual = $fecha_pago;
                        } else {
                            $fecha_y_hora_actual = date("Y-m-d");
                        }
                        //$fecha_y_hora_actual = date("Y-m-d"); 
                        print Utils::Fecha_en_Castellano($fecha_y_hora_actual);
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
                    <td colspan="3"><?php print $formatter->asCurrency($monto); ?></td>
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
                    <td colspan="3"><!--Recibo por cuenta de terceros, documento no v&aacute;lido como factura--></td>
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

<table width="800" border="1" cellpadding="0" cellspacing="0" bordercolor="#333333">
    <tr><td>
            <table width="100%" border="0" cellpadding="2" cellspacing="2">
                <tr>
                    <td colspan="2" rowspan="3">
                        <!--
                        <img src="../imagenes/logo_spazio.png" width="248" height="118" />
                        -->
                        <div class="tipo_doc">RECIBO</div>
                        <div class="letra">C</div>		</td>

                    <td bgcolor="#F3F3F3"></td>
                    <td bgcolor="#F3F3F3"><div align="right">DUPLICADO</div></td>
                </tr>
                <tr>
                    <td align="right" width="109" bgcolor="#F3F3F3"><b>Fecha:</b></td>
                    <td width="214" bgcolor="#F3F3F3"><?php
                        if (!empty($fecha_pago)  && $fecha_pago!='0000-00-00 00:00:00') {
                            $fecha_y_hora_actual = $fecha_pago;
                        } else {
                            $fecha_y_hora_actual = date("Y-m-d");
                        }
                        print Utils::Fecha_en_Castellano($fecha_y_hora_actual);
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
                    <td colspan="3"><?php print $formatter->asCurrency($monto); ?></td>
                </tr>
                <tr>
                    <td><div align="right"><strong>en concepto de : </strong></div></td>
                    <td colspan="3"><?php print $concepto; ?></td>
                </tr>
                <tr>
                    <td colspan="4"><div align="center">Recibo por cuenta de terceros, no v&aacute;lido como factura </div></td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
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
                    <td colspan="3"><!--Recibo por cuenta de terceros, documento no v&aacute;lido como factura --></td>
                </tr>
            </table>

        </td></tr></table>
<div style="position:absolute; width:400px; height:150px; z-index:1; left: 0px; top: 0px; 
     visibility:hidden;">
    <?php
//Grabar_Respuesta_Impresora($concepto, "recibo com�n", $monto, $id_liquidacion, $numero_recibo, "C");
    ?>
</div>
<script language="JavaScript">window.print();</script>