<?php
$formatter = \Yii::$app->formatter;

$aux_fecha_consulta = explode("-", $model_caja->inicio_fecha);

$estilo="";
 if (Yii::$app->user->identity->permisos  == "operador")
  $estilo='style="position:absolute;display:none;"';
  else
  $estilo='style="display:block"';
?>

<table class="table table-condensed" style="width:500px" <?php echo $estilo;?>>
    <tr>
        <td width="250" ><div align="right"><b>Fecha:</b></div></td>
        <td width="250" ><?php echo $aux_fecha_consulta[2] . '-' . $aux_fecha_consulta[1] . '-' . $aux_fecha_consulta[0] ?></td>
        <td width="250" >&nbsp;</td>
    </tr>
    <tr>
        <td><div align="right"><b>Caja Inicio:</b></div></td>
        <td style="text-align: right"><?php echo $formatter->asCurrency($model_caja->inicio) ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center"><b>Ingresos</b></td>
        <td style="text-align: center"><b>Egresos</b></td>
    </tr>
    <tr>
        <td><div align="right">Cobranzas en efectivo: </div></td>
        <td style="text-align: right"><?php echo $formatter->asCurrency($vector[1] + $vector[11]) ?></td>
        <td></td>
    </tr>
    <tr>
        <td><div align="right">Cheques:</div></td>
        <td style="text-align: right"><?php echo $formatter->asCurrency($vector[2]) ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>    
        <td><div align="right">Dep&oacute;sitos:</div></td>
        <td style="text-align: right"><?php echo $formatter->asCurrency($vector[3]) ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>    
        <td><div align="right">Retenciones:</div></td>
        <td style="text-align: right"><?php echo $formatter->asCurrency($vector[4]) ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>            
        <td><div align="right">Ingresos Varios: </div></td>
        <td style="text-align: right"><?php echo $formatter->asCurrency($vector[8] + $vector[12] + $vector[15]) ?> </td>
        <td>&nbsp;</td>
    </tr>
    <tr>            
        <td><div align="right">Transferencia entre Cajas:</div></td>
        <td style="text-align: right"><?php echo $formatter->asCurrency($vector[13]) ?></td>
        <td></td>
    </tr>
    <tr>            
        <td><div align="right"><b>Subtotal</b></div></td>
        <td style="text-align: right"><b><?php
                echo $formatter->asCurrency($vector[1] + $vector[2] + $vector[4] + $vector[3] +
                        $vector[8] + $vector[12] + $vector[11] + $vector[13] + $vector[15])
                ?></b></td>
        <td></td>
    </tr>
    <tr>

        <td><div align="right">Pago de Liquidaciones: </div></td>
        <td>&nbsp;</td>
        <td  style="text-align: right"><?php echo $formatter->asCurrency($vector[14]) ?></td>
    </tr>
    <tr>

        <td><div align="right">Egresos Varios: </div></td>
        <td>&nbsp;</td>
        <td  style="text-align: right"><?php echo $formatter->asCurrency($vector[6] + $vector[5]) ?></td>
    </tr>
    <tr>

        <td><div align="right">Notas de Cr&eacute;dito:</div></td>
        <td>&nbsp;</td>
        <td  style="text-align: right"><?php echo $formatter->asCurrency($vector[9]) ?></td>
    </tr>
    <tr>            
        <td><div align="right">Transferencia entre Cajas:</div></td>
        <td>&nbsp;</td>
        <td  style="text-align: right"><?php echo $formatter->asCurrency($vector[10]) ?></td>
    </tr>
    <tr>            
        <td><div align="right"><b>Subtotal</b></div></td>
        <td></td>
        <td  style="text-align: right"><b><?php echo $formatter->asCurrency($vector[6] + $vector[5] + $vector[9] + $vector[10] + $vector[14]) ?></b></td>

    </tr>
    <tr>            
        <td>&nbsp;</td>
        <td></td>
        <td ></td>

    </tr>
    <?php
    if (!empty($model_caja->cierre_fecha)) {
        ?>
        <tr>            
            <td><div align="right"><b>Sobreante/Faltante</b></div></td>
            <td style="text-align: right"><b><?php echo ($aux_sobrante_faltante > 0) ? $aux_sobrante_faltante : 0; ?></b></td>
            <td ><b><?php echo ($aux_sobrante_faltante < 0) ? $aux_sobrante_faltante : 0; ?></b></td>

        </tr>
        <?php
    }
    ?>
    <tr>            
        <td>&nbsp;</td>
        <td></td>
        <td ></td>

    </tr>
    <tr>
        <td ><div align="right"><b>Saldo de Caja:</b>
                <?php
                $efectivo_en_caja = ($model_caja->inicio + $vector[1] + $vector[2] + $vector[4] + $vector[3] + $vector[8] +
                        $vector[12] + $vector[11] + $vector[13] + $vector[15]) - ($vector[6] + $vector[5] + $vector[9] + $vector[10] + $vector[14]);
                ?>
            </div></td>

        <td style="text-align: right" colspan="2" ><b><?php echo $formatter->asCurrency($efectivo_en_caja) ?></b></div></td>

    </tr>
    <?php if (!empty($model_caja->cierre_fecha)) { ?>
        <tr>
            <td ><div align="right"><b>Rendici&oacute;n diaria:</b>
                    <?php
                    $sobrante_faltante_suma = 0;
                    $sobrante_faltante_resta = 0;
                    if ($aux_sobrante_faltante > 0) {
                        $sobrante_faltante_suma = $aux_sobrante_faltante;
                    } else {
                        if ($aux_sobrante_faltante < 0) {
                            $sobrante_faltante_resta = $aux_sobrante_faltante;
                        }
                    }
                    $efectivo_en_caja = ($model_caja->inicio + $vector[1] + $vector[2] + $vector[4] + $vector[3] + $vector[8] +
                            $vector[12] + $vector[11] + $vector[13] + $sobrante_faltante_suma) - ($vector[6] + $vector[5] + $vector[9] +
                            $vector[10] + ($sobrante_faltante_resta * -1));
                    ?>
                </div></td>

            <td colspan="2" >$<b><?php echo $formatter->asCurrency($efectivo_en_caja) ?></b></div></td>

        </tr>
    <?php } ?>
</table>

<?=
$this->render('formulario-cierre-caja', ['model_caja' => $model_caja, 'vector' => $vector, 'efectivo_en_caja' => $efectivo_en_caja
])
?>
