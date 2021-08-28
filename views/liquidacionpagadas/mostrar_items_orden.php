<?php
$formatter = \Yii::$app->formatter;
?>
<table id="tabla_liquidaciones_pagadas" class="table" border="0" cellspacing="4" cellpadding="0" align="center">
    <thead>
        <!--<tr id="cabezera-tabla">-->
            <th height="36">FECHA DE PAGO</th>
            <th height="36">PROPIEDAD</th>
            <th height="36">PERIODO</th>
            <th height="36">IMPORTE COBRADO</th>
            <th height="36">INTERES POR MORA</th>
            <?php if ($model->estado == 'Nuevo') { ?>
                <td height="36">ACCION</td>
            <?php }
            ?>
        <!--</tr>-->
    </thead>
    <tbody>
        <?php
        $dinero_cobrado = 0;
        $interes_mora = 0;
        $total_comision = 0;
        //        $obj_liqprop = new LiquidacionPropietario();
        //        $resultado = $obj_liqprop->obtener_detalle_liquidacion($_POST['liquidacionpagadas_id']);

        foreach ($model_detalle_orden as $row) {
            //($row = mysql_fetch_array($resultado)) {
            $dinero_cobrado += $row->dinero_cobrado;
            $interes_mora += $row->interes_por_mora;
            /* $obj_propiedad = new Propiedad();
          $registro_prop = $obj_propiedad->obtener_propiedad_por_liquidacion(array('id_liquidacion' => $row['liquidaciones_id']));
          if (mysql_num_rows($registro_prop) > 0) {
          $row_registro_prop = mysql_fetch_array($registro_prop);
          } else {
          $registro_prop = $obj_propiedad->obtener_propiedad_historial_por_liquidacion(array('id_liquidacion' => $row['liquidaciones_id']));
          $row_registro_prop = mysql_fetch_array($registro_prop);
          }
          $comision = $obj_propiedad->obtener_comision_por_inmueble(array('inmuebles_id' => $row_registro_prop['id_noticia'])); */
            $comision = 0;
            $direccion = "";
            $liq_mes = "";
            $liq_anio = "";
            if ($row->liquidacion) {
                $fecha_pago = date("d/m/Y", strtotime($row->liquidacion->fecha_pago));
                $liq_mes = $row->liquidacion->liq_mes;
                $liq_anio = $row->liquidacion->liq_anio;
                if ($row->liquidacion->operacionInmobiliaria) {
                    if ($row->liquidacion->operacionInmobiliaria->inmueble) {
                        $direccion = $row->liquidacion->operacionInmobiliaria->inmueble->direccion;
                        $model_inmueble_prop = \app\models\InmueblesPropietarios::find()->where(['inmueble_id' => $row->liquidacion->operacionInmobiliaria->cod_propiedad])->one();
                        $comision = $model_inmueble_prop->comision;
                    }
                }
            }

            $total_comision += ($row->dinero_cobrado * $comision) / 100;
            echo "<tr>";
            $mydate = strtotime($row->liquidacion->fecha_pago);
            echo "<td data-sort='{$mydate}'>" . $fecha_pago . "</td>";
            echo "<td width='300px'>" . $direccion . "</td>";
            echo "<td>" . $liq_mes . "/" . $liq_anio . "</td>";
            echo "<td>" . $formatter->asCurrency($row->dinero_cobrado - $row->interes_por_mora) . "</td>";
            echo "<td>" . $formatter->asCurrency($row->interes_por_mora) . "</td>";
            if ($model->estado == 'Nuevo') {
                echo "<td><a href='#' class='glyphicon glyphicon-trash' onclick='eliminar_item(" . $row['id'] . ")'>" . '' . "</a></td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td><b>Subtotales:</b></td>
            <td><b>$<?php echo number_format($dinero_cobrado - $interes_mora, 2, ',', '.'); ?></b></td>
            <td><b>$<?php echo number_format($interes_mora, 2, ',', '.'); ?></b></td>
        </tr>
    </tfoot>
</table>
<input type="hidden" id="field_dinero_liq" value="<?php echo number_format($dinero_cobrado, 2, '.', '') ?>" />
<input type="hidden" id="field_total_comision_liq" value="<?php echo number_format($total_comision, 2, '.', '') ?>" />
<hr />