<?php
require_once("Enletras.php");
$formatter = \Yii::$app->formatter;
?>
<page>
    <page_footer>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y') ?></td>
                <td style="text-align: right;    width: 50%"><b>Página [[page_cu]]/[[page_nb]]</b></td>
            </tr>
        </table>
    </page_footer>

    <?php
    $obj_liqpagadas = new LiquidacionPropietario();
    $resultado = $obj_liqpagadas->obtener_liquidacion_by_id($orden_id);
    $row = mysql_fetch_array($resultado);
    $orden_de_pago_id = $row['id'];
    $gastos = $row['gastos'];
    ?>
    <table width="80%">
        <tbody>
            <tr>
                <td width="500">
                    <table width="80%" style="font-size: 17px;">
                        <tbody>
                            <tr>
                                <td width="200"><label>Liquidaci&oacute;n N&uacute;mero:</label></td>
                                <td width="100"><b><?php echo $row['id'] ?></b></td>

                            </tr>
                            <tr>
                                <td width="200"><label class="etiqueta1">Fecha de Liquidaci&oacute;n:</label></td>
                                <td width="100"><b><?php echo date("d/m/Y", strtotime($row['fecha'])) ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>

                    <img src="http://<?php echo $_SERVER['SERVER_NAME'] . Yii::$app->request->baseUrl ?>/images/logo_spazio.png" width="200" />
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <table width="80%" style="font-size: 17px;">
        <tbody>
            <tr>
                <td width="200"><b><label>Propietario:</label></b></td>
                <td width="300"><b><?php
                                    $obj_propietario = new Propietario();
                                    $resultado_prop = $obj_propietario->obtener_propietario_por_id($row['propietario_id']);
                                    $registro_prop = mysql_fetch_array($resultado_prop);
                                    echo strtoupper($registro_prop['apellido'] . "," . $registro_prop['nombre']);
                                    ?></b></td>
            </tr>
            <tr>
                <td width="200"><b><label>Cuit:</label></b></td>
                <td width="300"><b><?php
                                    $obj_prop = new app\models\Propietarios();
                                    $obj_prop->cuit = $registro_prop['cuit'];
                                    echo strtoupper($obj_prop->obtener_cuit());
                                    ?></b></td>
                <td width="550" style='text-align:right;'>Página:[[page_cu]]/[[page_nb]]</td>
            </tr>
        </tbody>
    </table>

    <br />
    Comprobante no fiscal
    <hr />
    <div style="text-align:center">
        COBRANZAS EFECTUADAS
    </div>
    <table style="margin-bottom: 10px;font-size: 10px;width:100%" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <thead>
            <tr id="cabezera-tabla">
                <td width='70' height="36" style="font-weight: bold;">Fecha</td>
                <td width='160' height="36" style="font-weight: bold;min-width: 300px">Propiedad</td>
                <td width='160' height="36" style="font-weight: bold;min-width: 300px">Inquilino</td>
                <td width='30' height="36" style="font-weight: bold;text-align: center;">Mes</td>
                <td width='30' height="36" style="font-weight: bold;text-align: center;">A&ntilde;o</td>
                <td width='40' height="36" style="font-weight: bold;text-align: center;">Concepto</td>
                <td width='80' height="36" style="font-weight: bold;text-align: center;">Monto</td>
                <td width='80' height="36" style="font-weight: bold;text-align: center;">Mora</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $dinero_cobrado = 0;
            $interes_mora = 0;
            $total_comision = 0;
            //$obj_liqprop = new LiquidacionPropietario();
            //$resultado = $obj_liqprop->obtener_detalle_liquidacion($orden_de_pago_id);
            /* $cadena = "SELECT a2_noticias.titulo,a2_clientes.NOMBRE,liqpagadas_detalle.*,liq_anio,liq_mes,fecha_pago FROM liqpagadas_detalle INNER JOIN a2_liquidaciones ON 
              liqpagadas_detalle.liquidaciones_id=a2_liquidaciones.id_liquidacion INNER JOIN a2_operaciones_inmobiliarias ON
              (a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion) INNER JOIN a2_noticias ON
              (a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad) INNER JOIN a2_clientes ON
              (a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente)
              WHERE liquidacionpagadas_id={$orden_de_pago_id} ORDER BY "; */
            $cadena = "SELECT * FROM (SELECT a2_noticias.direccion,a2_clientes.NOMBRE,liqpagadas_detalle.id,
			liqpagadas_detalle.liquidaciones_id,liq_anio,liq_mes,liqpagadas_detalle.dinero_cobrado,liqpagadas_detalle.interes_por_mora,fecha_pago,'ALQUILER' as tipo FROM liqpagadas_detalle INNER JOIN a2_liquidaciones ON 
			liqpagadas_detalle.liquidaciones_id=a2_liquidaciones.id_liquidacion INNER JOIN a2_operaciones_inmobiliarias ON
			(a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion) INNER JOIN a2_noticias ON
			(a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad) INNER JOIN a2_clientes ON 
			(a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente)
			  WHERE liquidacionpagadas_id={$orden_de_pago_id}
			UNION
			SELECT a2_noticias.direccion,a2_clientes.NOMBRE,operaciones_expensas.id,0 as liquidaciones_id,
			operaciones_expensas.`year` as liq_anio,operaciones_expensas.mes as liq_mes,operaciones_expensas.importe as dinero_cobrado,
			0 as interes_por_mora,a2_facturacion.fecha as fecha_pago,'EXPENSAS' AS tipo FROM 
			liqpagadas_expensas INNER JOIN operaciones_expensas ON 
			liqpagadas_expensas.expensa_id=operaciones_expensas.id INNER JOIN a2_operaciones_inmobiliarias ON
			(a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=operaciones_expensas.operacion_id) INNER JOIN a2_noticias ON
			(a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad) INNER JOIN a2_clientes ON 
			(a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente)
			LEFT JOIN a2_facturacion ON 
			(operaciones_expensas.comprobante_id=a2_facturacion.id_facturacion)
			  WHERE liquidacionpagadas_id={$orden_de_pago_id}
			)as t1 ORDER BY ";

            /*$cadena = "SELECT * FROM(SELECT a2_noticias.titulo,a2_clientes.NOMBRE,liqpagadas_detalle.*,liq_anio,liq_mes,fecha_pago FROM liqpagadas_detalle INNER JOIN a2_liquidaciones ON 
liqpagadas_detalle.liquidaciones_id=a2_liquidaciones.id_liquidacion INNER JOIN a2_operaciones_inmobiliarias ON
(a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion) INNER JOIN a2_noticias ON
(a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad) INNER JOIN a2_clientes ON 
(a2_operaciones_inmobiliarias.cod_cliente=a2_clientes.id_cliente)
  WHERE liquidacionpagadas_id={$orden_de_pago_id}
UNION
SELECT a2_noticias.titulo,a2_clientes.NOMBRE,liqpagadas_detalle.*,liq_anio,liq_mes,fecha_pago FROM liqpagadas_detalle INNER JOIN a2_liquidaciones ON 
liqpagadas_detalle.liquidaciones_id=a2_liquidaciones.id_liquidacion INNER JOIN a2_operaciones_inmobiliarias_copy ON
(a2_operaciones_inmobiliarias_copy.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion) INNER JOIN a2_noticias ON
(a2_noticias.id_noticia=a2_operaciones_inmobiliarias_copy.cod_propiedad) INNER JOIN a2_clientes ON 
(a2_operaciones_inmobiliarias_copy.cod_cliente=a2_clientes.id_cliente)
  WHERE liquidacionpagadas_id={$orden_de_pago_id}
)as t1 ORDER BY ";*/
            /*if ($_GET['ordena_por'] == 2) {
                $cadena.=" fecha_pago ASC,titulo, liq_anio,liq_mes ASC";
            } else {*/
            $cadena .= " direccion,fecha_pago ASC, liq_anio,liq_mes ASC";
            //}

            $resultado = mysql_query($cadena);
            if (mysql_errno() > 0) {
                print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
                die();
            }
            $i = 0;
            while ($row = mysql_fetch_array($resultado)) {
                if (($i % 2) == 0) {
                    $estilo_color = "#e4ecf7";
                } else {
                    $estilo_color = "#FFF";
                }
                $dinero_cobrado += $row['dinero_cobrado'];
                $interes_mora += $row['interes_por_mora'];
                if ($row['tipo'] == 'ALQUILER') {
                    $obj_propiedad = new Propiedad();
                    $registro_prop = $obj_propiedad->obtener_propiedad_por_liquidacion(array('id_liquidacion' => $row['liquidaciones_id']));

                    if (mysql_num_rows($registro_prop) > 0) {
                        $row_registro_prop = mysql_fetch_array($registro_prop);
                    } else {
                        $registro_prop = $obj_propiedad->obtener_propiedad_historial_por_liquidacion(array('id_liquidacion' => $row['liquidaciones_id']));
                        $row_registro_prop = mysql_fetch_array($registro_prop);
                    }

                    $comision = $obj_propiedad->obtener_comision_por_inmueble(array('inmuebles_id' => $row_registro_prop['id_noticia']));
                    $total_comision += ($row['dinero_cobrado'] * $comision) / 100;
                }
                echo '<tr>';
                echo "<td>" . date("d/m/Y", strtotime($row['fecha_pago'])) . "</td>";
                echo "<td width='160' style='font-size:8px'>" . utf8_encode(substr($row['direccion'], 0, 30)) . "</td>";
                echo "<td width='160' style='font-size:8px'>" . utf8_encode(substr($row['NOMBRE'], 0, 30)) . "</td>";
                echo "<td width='30' style='text-align:center;'>" . $row['liq_mes'] . "</td>";
                echo "<td width='30' style='text-align:center;'>" . $row['liq_anio'] . "</td>";
                echo "<td width='40' style='text-align:center;'>" . $row['tipo'] . "</td>";
                echo "<td width='80'  style='text-align:right;'>$ " . number_format(($row['dinero_cobrado'] - $row['interes_por_mora']), CANT_DIGITOS, ',', '.') . "</td>";
                echo "<td width='80' style='text-align:right;'>$ " . number_format($row['interes_por_mora'], CANT_DIGITOS, ',', '.') . "</td>";
                echo "</tr>";
                $i++;
            }
            ?>

            <tr>
                <td colspan="8" style="padding-top: 10px">
                    <hr />
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3" style='text-align:center;padding-top: 10px;'><b>Subtotal</b></td>

                <td width='80' style='text-align:right;padding-top: 10px;'>$ <?php echo number_format(($dinero_cobrado - $interes_mora), CANT_DIGITOS, ',', '.') ?></td>
                <td width='50' style='text-align:right;padding-top: 10px;'>$ <?php echo number_format($interes_mora, CANT_DIGITOS, ',', '.') ?></td>
            </tr>

        </tbody>
        <tfoot>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </tfoot>
    </table>
    <hr />
    <div style="text-align:center">
        GASTOS EFECTUADOS
    </div>
    <table style="font-size: 10px;" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <thead>
            <tr id="cabezera-tabla">
                <td width='55' height="36" style="font-weight: bold;">Fecha</td>
                <td width='160' height="36" style="font-weight: bold;min-width: 300px">Propiedad</td>
                <td width='200' height="36" style="font-weight: bold;min-width: 300px">Inquilino</td>

                <td width='190' height="36" style="font-weight: bold;text-align: center;">Observacion</td>
                <td width='60' height="36" style="font-weight: bold;text-align: center;">Importe</td>
            </tr>
        </thead>
        <tbody>

            <?php
            $gasto_total = 0;
            foreach ($model_detalle_gastos as $row) {
                  
                $model_mov = \app\models\A2Movimientos::find()->where(['id_movimiento' => $row['movimientos_id']])->one();
                if ($model_mov->movimiento_tipo == 8) {
                    $gasto_total -= $row->importe;
                } else {
                    $gasto_total += $row->importe;
                }
                $model_operacion = \app\models\A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' =>  $model_mov->operacion_id])->one();
                if ($model_operacion) {
                    echo "<tr>";
                    echo "<td width='40'>" . date('d/m/Y', strtotime($model_mov->fecha_movimiento)) . "</td>";
                    echo "<td width='160'>" . $model_operacion->inmueble->direccion . "</td>";
                    echo "<td width='260'>" . $model_operacion->cliente->NOMBRE . "</td>";
                    echo "<td width='190'>" . (($model_mov->movimiento_tipo == 8) ? "Ingreso:" : "Egreso:" . $model_mov->observaciones) . "</td>";
                    echo "<td width='60' style='text-align:right'>" . $formatter->asCurrency($row['importe']) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
            <tr>
                <td colspan="5" style="padding-top: 10px">
                    <hr />
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align:right"><b>Subtotales:</b></td>
                <td style="text-align:right"><b>$<?php echo number_format($gasto_total, 2, ',', '.'); ?></b></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </tfoot>
    </table>

    <?php
    $gastos = (empty($gastos)) ? 0 : $gastos;
    ?>


    <table width="100%" cellspacing="10">
        <tr>
            <td></td>
            <td></td>
            <td width="400"></td>
            <td width="150"><label class="ancho_label totales">Cobranzas Efectuadas</label></td>
            <td style="text-align: right">$<?php echo number_format($dinero_cobrado, 2, ',', '.'); ?></td>
        </tr>
        <!--    <tr>
            <td></td>
            <td></td>
            <td></td>
            <td width="150"><label class="ancho_label totales">Total Interes Mora</label></td>
            <td style="text-align: right">$<?php //echo number_format($interes_mora, 2, ',', '.');      
                                            ?></td>
        </tr>-->
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td width="150"><label class="ancho_label totales">Importe de Comisiones</label></td>
            <td style="text-align: right">$<?php echo number_format($total_comision, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td width="150"><label class="ancho_label totales">Otros Gastos</label></td>
            <td style="text-align: right">$<?php echo (empty($gasto_total)) ? 0.00 : number_format($gasto_total, 2, ',', '.'); ?></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td width="150"><label class="ancho_label totales">Importe a Percibir</label></td>
            <td style="text-align: right">$<b><?php echo number_format($dinero_cobrado - ($gasto_total + $total_comision), 2, ',', '.'); ?></b></td>
        </tr>
    </table>

    <br /><br />
    <?php
    $total_calculado = $dinero_cobrado - ($gasto_total + $total_comision);
    $arreglo_financia = explode(",", number_format($total_calculado, 2, ',', ''));
    $V = new EnLetras();
    echo "<b> Son pesos " . $V->ValorEnLetras(INTVAL($total_calculado), "");
    echo " con " . $arreglo_financia[1] . "/100" . ".</b>";
    ?>
</page>