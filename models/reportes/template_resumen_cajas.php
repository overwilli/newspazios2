<page_footer>
    <table style="width: 100%;border:1px solid black ">
        <tr>
            <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y h:i:s') ?></td>
            <td style="text-align: right;    width: 50%"><b>Página [[page_cu]]/[[page_nb]]</b></td>
        </tr>
    </table>
</page_footer>
<style>
    .header_tabla{
        font-weight: bold;
    }
    .filapar{
        background-color: #e4ecf7;

    }
    .filaimpar{
        background-color: #FFF;
    }
    .tabla{
        border:1px solid black;
    }
    .estilo_celda{
        vertical-align: top;

    }
    .centrar_texto{
        text-align: right;
    }
    .alinear_derecha{
        text-align: right;
    }


</style>
<h1>Resumen de Cajas</h1>
<?php
$cajas = "";
if (!empty($numero_caja_desde) && !empty($numero_caja_hasta)) {
    for ($i = $numero_caja_desde; $i <= $numero_caja_hasta; $i++) {
        if ($i != $numero_caja_desde) {
            $cajas.=" OR ";
        }
        $cajas.= " numero_caja={$i}";
    }
} else {//En caso en que no se ingresen los numeros de cajas.
    $obj_cajas = new Cajas();
    $resultado_cajas = $obj_cajas->obtener_las_cajas_existentes();
    while ($fila = mysql_fetch_array($resultado_cajas)) {
        if ($cajas != "") {
            $cajas.=" OR ";
        }
        $cajas.= " numero_caja={$fila['numero_caja']}";
    }
}

$cadenaSQL = "SELECT 
    a2_caja.id_caja,
    a2_caja.inicio_usuario,
    a2_caja.inicio_fecha,
    a2_caja.inicio,
    a2_caja.caja_final as aux_caja_final,
    a2_caja.sobrante_faltante as aux_sobrante_faltante,
    a2_caja.caja_cierre,
    a2_caja.rendicion,
    a2_caja.cierre_usuario,
    a2_caja.cierre_fecha,
    a2_caja.cierre_hora,		
    a2_caja.numero_caja,
    a2_caja.caja_dia_siguiente
    
    FROM a2_caja 
    WHERE (" . $cajas . ")";
if (!empty($fecha_desde) && !empty($fecha_hasta)) {
    $cadenaSQL.=" AND (((inicio_fecha>='" . $fecha_desde . "' AND inicio_fecha<='" . $fecha_hasta . "') AND
    (cierre_fecha>='" . $fecha_desde . "' AND cierre_fecha<='" . $fecha_hasta . "')";
    $cadenaSQL.=" OR ((inicio_fecha>='" . $fecha_desde . "' AND inicio_fecha<='" . $fecha_hasta . "') AND (cierre_fecha IS NULL))";
    $cadenaSQL.=")";
    $cadenaSQL.=" OR ((inicio_fecha>='" . $fecha_desde . "' AND inicio_fecha<='" . $fecha_hasta . "') OR
    (cierre_fecha>='" . $fecha_desde . "' AND cierre_fecha<='" . $fecha_hasta . "')) OR 
    ('" . $fecha_desde . "'>=inicio_fecha AND '" . $fecha_hasta . "'<=cierre_fecha))";
}
$cadenaSQL.=" ORDER BY  inicio_fecha,a2_caja.numero_caja";

$resultado_cajas = mysql_query($cadenaSQL);
if (mysql_errno() > 0) {
    print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $cadenaSQL;
    die();
}

if (mysql_num_rows($resultado_cajas) > 0) {

    $caja_anterior = 0;
    while ($fila = mysql_fetch_array($resultado_cajas)) {

        $cadenaSQL = "SELECT a2_movimientos.fecha_movimiento,a2_movimientos.usuario,
            a2_movimientos.movimiento_tipo,a2_movimientos.monto,a2_movimientos.id_liquidacion,
            a2_movimientos.observaciones,a2_movimientos.comprobante_fecha,
            a2_movimientos.comprobante_nro,a2_movimiento_tipo.denominacion,
            a2_movimiento_tipo.tipo_movimiento,a2_movimientos.numero_caja,
            a2_movimientos.tipo_registro FROM a2_movimientos LEFT JOIN  a2_movimiento_tipo ON 
            a2_movimiento_tipo.id_tipo=a2_movimientos.movimiento_tipo 
            WHERE (numero_caja=" . $fila['numero_caja'] . ") AND (fecha_movimiento>='{$fila['inicio_fecha']}'";
        if (!empty($fila['cierre_fecha'])) {
            $cadenaSQL.="  AND fecha_movimiento<='{$fila['cierre_fecha']}'";
        }
        $cadenaSQL .=") ORDER BY a2_movimientos.fecha_movimiento DESC, a2_movimientos.id_movimiento DESC";
        /* $cadenaSQL = "SELECT a2_movimientos.fecha_movimiento,a2_movimientos.usuario,
          a2_movimientos.movimiento_tipo,a2_movimientos.monto,a2_movimientos.id_liquidacion,
          a2_movimientos.observaciones,a2_movimientos.comprobante_fecha,
          a2_movimientos.comprobante_nro,a2_movimiento_tipo.denominacion,
          a2_movimiento_tipo.tipo_movimiento,a2_movimientos.numero_caja,
          a2_movimientos.tipo_registro FROM a2_movimientos LEFT JOIN  a2_movimiento_tipo ON
          a2_movimiento_tipo.id_tipo=a2_movimientos.movimiento_tipo
          WHERE caja_id='" . $fila['id_caja'] . "' AND (numero_caja=" . $fila['numero_caja'] . ") ORDER BY a2_movimientos.fecha_movimiento DESC, a2_movimientos.id_movimiento DESC"; */

        $result = mysql_query($cadenaSQL);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $cadenaSQL;
            die();
        }

        if ($caja_anterior == 0 || $fila['numero_caja'] != $caja_anterior) {
            if ($caja_anterior != 0) {
                /* echo '<div class="filapar" style="padding:20px;border:1px solid black;width:500px">';
                  echo "<div><b>TOTALES GENERALES PARA LA CAJA N&deg; " . $caja_anterior . "</b></div>";
                  echo "<div><b>TOTAL DE INGRESOS:$</b> " . number_format($ingreso_por_caja, CANT_DIGITOS, ',', '.') . "</div>";
                  echo "<div><b>TOTAL DE EGRESOS:$</b> " . number_format($gastos_por_caja, CANT_DIGITOS, ',', '.') . "</div>";
                  echo "<div><b>TOTAL:$</b> " . number_format(($ingreso_por_caja - $gastos_por_caja), CANT_DIGITOS, ',', '.') . "</div>";
                  echo "</div><br/><br/><br/>"; */
            }
            $ingreso_por_caja = 0; //variables utilizadas para almacenar los ingresos por corte de control basado en el numero de caja.
            $gastos_por_caja = 0;
        }

        if ($fila['cierre_usuario'] == "") {
            $estado_caja = 'La caja N°' . $fila['numero_caja'] . '(' . $fila['inicio_usuario'] . ') del día ' . Fecha_en_Castellano($fila['inicio_fecha']) . ' <b>SIN CERRAR</b> ';
        } else {
            $estado_caja = 'La caja N°' . $fila['numero_caja'] . '(' . $fila['inicio_usuario'] . ') del día ' .
                    Fecha_en_Castellano($fila['inicio_fecha']) . ' fue cerrada ' . date('d/m/Y', strtotime($fila['cierre_fecha'])) . ' <b>CERRADA</b> (' . $fila['cierre_usuario'] . ') ';
        }
        print '<div class="Errores">' . $estado_caja . '</div>';
        ?><br/>
        <?php
        $contador = 0;
        $total_cobrado = 0;
        if (mysql_num_rows($result) > 0) {
            ?>
            <table class="tabla" style="border:1px solid black" width="100%"  cellspacing="0" cellpadding="0" >            	
                <?php
                if ($resumido != 1) {
                    ?>
                    <tr style="background-color: darkgray"> 
                        <td class="header_tabla" height="36" width="50">FECHA</td>	  
                        <td class="header_tabla" height="36">USUARIO</td>
                        <td class="header_tabla" height="36">MOVIMIENTO</td>		
                        <td class="header_tabla">MONTO</td>	 
                        <td class="header_tabla" width="300" >OBSERVACION</td>	


                    </tr>
                    <?php
                }
                $estilo = "filapar";
                $contador = 0;
                $total_a_cobrar = 0;
                $total_cobrado = 0;
                $id_liq_aux=0;//INICIALIZA LA VARIABLE
                $movi_tipo_anterior=0;
                $indice_vector = 0;
                while ($indice_vector <= 17) {
                    $vector[$indice_vector] = 0;
                    $indice_vector++;
                }
                $i = 0;
                while ($row = mysql_fetch_array($result)) {

                    $datetime_fecha_mov = new DateTime($row["fecha_movimiento"]);
                    $datetime_fecha_desde = new DateTime($fecha_desde);
                    $datetime_fecha_hasta = new DateTime($fecha_hasta);

                    if ($datetime_fecha_mov >= $datetime_fecha_desde && $datetime_fecha_mov <= $datetime_fecha_hasta) {

                        if ($id_liq_aux != $row["id_liquidacion"]) {
                            $fecha_movimiento_anterior = "";
                            $monto_anterior = 0;
                            $movi_tipo_anterior = 0;
                        }
                        if (($i % 2) == 0) {
                            $estilo_color = "";
                        } else {
                            $estilo_color = "";
                        }
                        if ($estilo == "filapar")
                            $estilo = "filaimpar";
                        else
                            $estilo = "filapar";

                        //print $vector[$row["movimiento_tipo"]]."<br>";
                        if ($row['tipo_registro'] != 'pago_pendiente') {
                            $vector[$row["movimiento_tipo"]]+=$row["monto"];
                        }
                        //die($vector[$row["movimiento_tipo"]]." - ".$row["monto"]); 
                        if ($resumido != 1) {
                            ?>
                            <tr  class="<?php print $estilo; ?>" onmouseover="this.className = 'filaresaltada';" onmouseout="this.className = '<?php print $estilo; ?>';"> 
                                <td style="border-bottom:1px solid black;" valign="top" > <?php
                        $aux_fecha = explode("-", $row["fecha_movimiento"]);
                        print $aux_fecha[2] . "-" . $aux_fecha[1] . "-" . $aux_fecha[0];
                            ?></td>
                                <td style="border-bottom:1px solid black;" valign="top" ><?php print $row["usuario"]; ?></td>
                                <td style="border-bottom:1px solid black;" valign="top" ><?php print utf8_encode($row["denominacion"]); ?></td>		
                                <td style="border-bottom:1px solid black;" valign="top" >
                                    <?php
                                    if ($row['tipo_registro'] == 'pago_pendiente') {
                                        print '$' . number_format(0, 2, ',', '.');
                                    } else {
                                        if ($movi_tipo_anterior == 7 &&
                                                $fecha_movimiento_anterior == $row["fecha_movimiento"] &&
                                                $id_liquidacion_anterior = $row["id_liquidacion"] &&
                                                $row["movimiento_tipo"] == 1
                                                || $row["movimiento_tipo"] == 2|| $row["movimiento_tipo"] == 3) {
                                            print '$' . number_format(($row["monto"] - $monto_anterior), 2, ',', '.');
                                        } else {
                                            print '$' . number_format($row["monto"], 2, ',', '.');
                                        }
                                    }
                                    ?>
                                </td>	
                                <td width="300" style="border-bottom:1px solid black;" valign="top" >
                                    <?php
                                    if ($row['tipo_registro'] == 'pago_pendiente') {
                                        echo "<b>Registro de pago pendiente</b><br>";
                                    }
                                    /* $arreglo_obs=  explode(' ', $row["observaciones"]);
                                      for ($i_obs = 0; $i_obs < count($arreglo_obs); $i_obs++) {
                                      print utf8_encode($arreglo_obs[$i_obs]);
                                      } */
                                    print utf8_encode($row["observaciones"]);
                                    ?>	</td>		


                            </tr>
                            <?php
                        }
                        //$total_a_cobrar+=$aux_monto_a_cobrar;
                        if ($row["movimiento_tipo"] == 1 || $row["movimiento_tipo"] == 8 || $row["movimiento_tipo"] == 11 ||
                                $row["movimiento_tipo"] == 12 || $row["movimiento_tipo"] == 13 || $row["movimiento_tipo"] == 15) {//QUE NO TENGA EN CUENTA INTERESES
                            if ($row['tipo_registro'] != 'pago_pendiente') {
                                $total_cobrado+=$row["monto"];
                            }
                        }
                        if ($row["movimiento_tipo"] == 7) {//INTERES POR MORA
                            $fecha_movimiento_anterior = $row["fecha_movimiento"];
                            $monto_anterior = $row["monto"];
                            $movi_tipo_anterior = $row["movimiento_tipo"];
                            $id_liquidacion_anterior = $row["id_liquidacion"];
                        } /* else {
                          $fecha_movimiento_anterior = "";
                          $monto_anterior = 0;
                          $movi_tipo_anterior = 0;
                          } */
                        $id_liq_aux = $row["id_liquidacion"];
                        $contador++;
                    }//FIN DE IF DE INTERVALO DE FECHAS 
                }//FIN DE WHILE.
                ?>            
            </table>
            <?php
        } else {
            for ($index = 0; $index < 17; $index++) {
                $vector[$index] = 0;
            }
        }
        ?>
        <b>
            <?php
            if ($resumido != 1) {
                print 'Mostrando  ' . $contador . ' movimientos';
            } else {
                echo $contador . " Movimiento existentes.";
            }
            ?></b>
        <!--        <br/>Recaudaci&oacute;n del d&iacute;a: $<?php //print number_format($total_cobrado, CANT_DIGITOS, ",", ".");            ?><br/>
        Gastos del d&iacute;a: $<?php //print ($vector[6] + $vector[5] + $vector[9] + $vector[10]);              ?><br/>
        Total Neto:$ <?php //echo number_format($total_cobrado - ($vector[6] + $vector[5] + $vector[9] + $vector[10]), CANT_DIGITOS, ",", ".")              ?>
        <br/><br/><br/>-->
        <?php
        $ingreso_por_caja += $total_cobrado;
        $ingreso_por_caja+=$fila['inicio']; //SUMA EN CUANTO INICIA LA CAJA.
        $gastos_por_caja += $vector[6] + $vector[5] + $vector[9] + $vector[10] + $vector[14];
//$listado_arqueo= '	
        $aux_fecha_consulta = explode("-", $fila['inicio_fecha']);
        ?>

        <table  class="tabla" style="max-width:400px" width="400px" border="0.5" cellspacing="0" cellpadding="0">
            <tr class="filaimpar">
                <td width="200" ><div align="right"><b>Fecha:</b></div></td>
                <td width="150" ><?php echo $aux_fecha_consulta[2] . '-' . $aux_fecha_consulta[1] . '-' . $aux_fecha_consulta[0] ?></td>
                <td width="200" >&nbsp;</td>
            </tr>
            <tr class="filapar">
                <td class="alinear_derecha"><b>Caja Inicio:</b></td>
                <?php
                if($fila['inicio_fecha']!=date("Y-m-d") && $fila['aux_caja_final']==''){
                    $param[':numero_caja'] = $fila['numero_caja'];
                    $param[':fecha_movimiento'] = $fila['inicio_fecha'];

                    $ultimo_movimiento = \app\models\A2Movimientos::find()->where("numero_caja=:numero_caja AND "
                            . "(fecha_movimiento>=:fecha_movimiento)", $param)->
                            orderBy(['fecha_movimiento' => SORT_DESC, 'id_movimiento' => SORT_DESC])->one();
                    
                    if($ultimo_movimiento){                        
                        $param[':fecha_movimiento'] = $ultimo_movimiento->fecha_movimiento;
                        $movimiento_ultima_caja = \app\models\A2Movimientos::find()->where("numero_caja=:numero_caja AND "
                                    . "(fecha_movimiento=:fecha_movimiento)", $param)->
                                    orderBy(['fecha_movimiento' => SORT_DESC, 'id_movimiento' => SORT_DESC])->all();
                        $indice_vector = 0;
                        while ($indice_vector <= 17) {
                            $vector[$indice_vector] = 0;
                            $indice_vector++;
                        }
                        foreach ($movimiento_ultima_caja as $row) {
                            if ($row['tipo_registro'] != 'pago_pendiente') {
                                $vector[$row["movimiento_tipo"]]+=$row["monto"];
                            }
                        }
                        $efectivo_en_caja_temp = ($fila['inicio'] + $vector[1] + $vector[2] + $vector[4] + $vector[3] + $vector[8] +
                                        $vector[12] + $vector[11] + $vector[13]) - ($vector[6] + $vector[5] + $vector[9] +
                                        $vector[10] );
                        $fila['inicio']=$efectivo_en_caja_temp;
                    }
                
                }?>
                <td class="estilo_celda centrar_texto">$<?php echo number_format($fila['inicio'], CANT_DIGITOS, ',', '.') ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center"><b>Ingresos</b></td>
                <td style="text-align: center"><b>Egresos</b></td>
            </tr>
            <tr class="filapar">
                <td class="alinear_derecha"><b>Cobranzas en efectivo: </b></td>
                <td class="estilo_celda centrar_texto">$<?php echo number_format($vector[1] + $vector[11], CANT_DIGITOS, ',', '.') ?></td>
                <td></td>
            </tr>
            <tr>
                <td><div align="right">Cheques:</div></td>
                <td class="alinear_derecha">$<?php echo number_format($vector[2], CANT_DIGITOS, ',', '.') ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr class="filapar">    
                <td><div align="right">Dep&oacute;sitos:</div></td>
                <td class="alinear_derecha">$<?php echo number_format($vector[3], CANT_DIGITOS, ',', '.') ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>    
                <td><div align="right">Retenciones:</div></td>
                <td class="alinear_derecha">$<?php echo number_format($vector[4], CANT_DIGITOS, ',', '.') ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr class="filapar">            
                <td><div align="right">Ingresos Varios: </div></td>
                <td class="alinear_derecha">$<?php echo number_format($vector[8] + $vector[12] + $vector[15], CANT_DIGITOS, ',', '.') ?> </td>
                <td>&nbsp;</td>
            </tr>
            <tr>            
                <td class="alinear_derecha"><b>Transferencia entre Cajas:</b></td>
                <td class="estilo_celda centrar_texto">$<?php echo number_format($vector[13], CANT_DIGITOS, ',', '.') ?></td>
                <td></td>
            </tr>
            <tr class="filapar">            
                <td><div align="right"><b>Subtotal</b></div></td>
                <td style="text-align: center">$<b><?php
        echo number_format($vector[1] + $vector[2] + $vector[4] + $vector[3] +
                $vector[8] + $vector[12] + $vector[11] + $vector[13] + $vector[15], CANT_DIGITOS, ',', '.')
        ?></b></td>
                <td></td>
            </tr>
            <tr>
                <td class="alinear_derecha">Egresos Varios:</td>
                <td>&nbsp;</td>
                <td class="estilo_celda centrar_texto">$<?php echo number_format($vector[6] + $vector[5] + $vector[14], CANT_DIGITOS, ',', '.') ?></td>
            </tr>
            <tr class="filapar">

                <td class="alinear_derecha">Notas de Credito:</td>
                <td>&nbsp;</td>
                <td class="estilo_celda centrar_texto">$<?php echo number_format($vector[9], CANT_DIGITOS, ',', '.') ?></td>
            </tr>
            <tr>            
                <td class="alinear_derecha">Transferencia entre Cajas:</td>
                <td>&nbsp;</td>
                <td class="estilo_celda centrar_texto">$<?php echo number_format($vector[10], CANT_DIGITOS, ',', '.') ?></td>
            </tr>
            <tr class="filapar">            
                <td class="alinear_derecha"><b>Subtotal</b></td>
                <td></td>
                <td style="text-align:center">$<b><?php echo number_format($vector[6] + $vector[5] + $vector[9] + $vector[10] + $vector[14], CANT_DIGITOS, ',', '.') ?></b></td>

            </tr>
            <tr>            
                <td>&nbsp;</td>
                <td></td>
                <td ></td>

            </tr>
            <?php
            if (!empty($fila['cierre_usuario'])) {
                ?>
                <tr class="filapar">            
                    <td><div align="right"><b>Sobreante/Faltante</b></div></td>
                    <td style="text-align: center"><b>$<?php echo ($fila['aux_sobrante_faltante'] > 0) ? $fila['aux_sobrante_faltante'] : 0; ?></b></td>
                    <td style="text-align: center"><b>$<?php echo ($fila['aux_sobrante_faltante'] < 0) ? $fila['aux_sobrante_faltante'] : 0; ?></b></td>

                </tr>
                <?php
            }
            ?>
            <tr>            
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td ></td>

            </tr>
            <?php
            $efectivo_en_caja = ($fila['inicio'] + $vector[1] + $vector[2] + $vector[4] + $vector[3] +
                    $vector[8] + $vector[12] + $vector[11] + $vector[13] + $vector[15]) - ($vector[6] + $vector[5] +
                    $vector[9] + $vector[10] + $vector[14]);
            ?>
        <!--            <tr class="filapar">
                <td class="alinear_derecha"><b>Saldo de Caja:</b></td>

                <td valign="top" class="estilo_celda" colspan="2" >$<b><?php //echo number_format($efectivo_en_caja, CANT_DIGITOS, ',', '.');  ?></b></td>                
            </tr>
            <tr>            
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td ></td>

            </tr>-->
            <?php if (!empty($fila['cierre_fecha'])) { ?>
                <tr class="filapar">
                    <td class="alinear_derecha" ><b>Rendici&oacute;n diaria:</b>
                        <?php
                        $sobrante_faltante_suma = 0;
                        $sobrante_faltante_resta = 0;
                        if ($fila['aux_sobrante_faltante'] > 0) {
                            $sobrante_faltante_suma = $fila['aux_sobrante_faltante'];
                        } else {
                            if ($fila['aux_sobrante_faltante'] < 0) {
                                $sobrante_faltante_resta = ($fila['aux_sobrante_faltante'] * -1);
                            }
                        }
                        $caja_dia_siguiente_suma = 0;
                        $caja_dia_siguiente_resta = 0;
                        if ($fila['caja_dia_siguiente'] > 0) {
                            $caja_dia_siguiente_suma = $fila['caja_dia_siguiente'];
                        } else {
                            if ($fila['caja_dia_siguiente'] < 0) {
                                $caja_dia_siguiente_resta = ($fila['caja_dia_siguiente'] * -1);
                            }
                        }
                        $efectivo_en_caja1 = ($fila['inicio'] + $vector[1] + $vector[2] + $vector[4] + $vector[3] + $vector[8] +
                                $vector[12] + $vector[11] + $vector[13] + $vector[15] + $sobrante_faltante_suma + $caja_dia_siguiente_suma) - ($vector[6] + $vector[5] + $vector[9] +
                                $vector[10] + $vector[14] + $sobrante_faltante_resta + $caja_dia_siguiente_resta);
                        ?>
                    </td>

                    <td  class="estilo_celda" colspan="2">$<b><?php echo number_format($efectivo_en_caja1, CANT_DIGITOS, ',', '.') ?></b></td>

                </tr>
            <?php } ?>
        </table>
        <br/>

        <hr/>

        <?php
        $caja_anterior = $fila['numero_caja'];
    }
    if ($caja_anterior != 0) {
        /* echo '<div class="filapar" style="padding:20px;border:1px solid black;width:500px">';
          echo "<div><b>TOTALES GENERALES PARA LA CAJA N&deg; " . $caja_anterior . "</b></div>";
          echo "<div><b>TOTAL DE INGRESOS:$</b> " . number_format($ingreso_por_caja, CANT_DIGITOS, ',', '.') . "</div>";
          echo "<div><b>TOTAL DE EGRESOS:$</b> " . number_format($gastos_por_caja, CANT_DIGITOS, ',', '.') . "</div>";
          echo "<div><b>TOTAL:$</b> " . number_format(($ingreso_por_caja - $gastos_por_caja), CANT_DIGITOS, ',', '.') . "</div>";
          echo "</div>"; */
    }
} else {
    echo "No existen datos para los parametros ingresados.";
}