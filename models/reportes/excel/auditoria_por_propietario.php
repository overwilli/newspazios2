<?php

use app\models\InmueblesPropietarios;

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=auditoria_por_propietarios.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
$formatter = \Yii::$app->formatter;
?>

<div class="col-xs-12 col-md-12">
    <table>
        <tbody>
            <tr>
                <td colspan="3" width="500">
                    <H2>Informe de Auditoria por Propietario</H2>
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <tr>
            <th>FECHA</th>
            <th>USUARIO</th>            
            <th>IMPORTE</th>
            <th>INTERESES</th>
            <th>INQUILINO</th>
            <th>FORMA DE PAGO</th>
            <th>CONCEPTO</th>
            <th>PROPIEDAD</th>
            <th>PROPIETARIO</th>
        </tr>
        <?php
        $dinero_cobrado = 0;
        $interes_mora = 0;
        $total_comision = 0;
        $total=0;
        $total_intereses=0;
        $i = 0;
        foreach ($resultado as $row) {
            if (($i % 2) == 0) {
                $estilo_color = "#e4ecf7";
            } else {
                $estilo_color = "#FFF";
            }
            $dinero_cobrado += $row['dinero_cobrado'];
            $interes_mora += $row['interes_por_mora'];
            $model_movimiento = null;
            $propietario="";
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

                $model_movimiento = app\models\A2Movimientos::find()->where(
                    "id_liquidacion=:id_liquidacion AND 
                movimiento_tipo<>:movimiento_tipo AND tipo_registro='pago'",
                    [':id_liquidacion' => $row['liquidaciones_id'], ':movimiento_tipo' => 3]
                )->one();

                $inmueble_prop = app\models\InmueblesPropietarios::find()->where(['inmueble_id' => $row_registro_prop['id_noticia']])->one();
                if ($inmueble_prop) {
                    $obj_propietario = $inmueble_prop->propietario;
                    if ($obj_propietario) {
                        $propietario = $obj_propietario->apellido . ", " . $obj_propietario->nombre;
                    }
                }
            } else {
                $model_ope_expensas = app\models\OperacionesExpensas::find()->where("id=:id", [':id' => $row['id']])->one();

                $model_movimiento = app\models\A2Movimientos::find()->where(
                    "id_movimiento=:id_movimiento AND 
                movimiento_tipo<>:movimiento_tipo",
                    [':id_movimiento' => $model_ope_expensas->comprobante_id, ':movimiento_tipo' => 3]
                )->one();

                $inmueble_prop = app\models\InmueblesPropietarios::find()->
                    where(['inmueble_id' => $model_ope_expensas->inmuebles_id])->one();
                if ($inmueble_prop) {
                    $obj_propietario = $inmueble_prop->propietario;
                    if ($obj_propietario) {
                        $propietario = $obj_propietario->apellido . ", " . $obj_propietario->nombre;
                    }
                }
            }

            if ($model_movimiento) {
                echo '<tr>';
                echo "<td>" . $model_movimiento['usuario'] . "</td>";
                echo "<td>" . date("d/m/Y", strtotime($row['fecha_pago'])) . "</td>";

                
                echo "<td width='130'  style='text-align:right;'>$ " . number_format(($row['dinero_cobrado'] - $row['interes_por_mora']), 2, ',', '.') . "</td>";
                echo "<td width='130' style='text-align:right;'>$ " . number_format($row['interes_por_mora'], 2, ',', '.') . "</td>";
                echo "<td>" . substr($row['NOMBRE'], 0, 30) . "</td>";
                echo "<td width='60' style='text-align:center;'>" . $row['tipo'] . "</td>";
                echo "<td style='text-align:center'>" . $row['liq_mes'] . "-" . $row['liq_anio'] . "</td>";
                echo "<td width='200'>" . substr(utf8_decode($row['direccion']), 0, 30) . "</td>";

                echo "<td>{$propietario}</td>";

                echo "</tr>";
                $total+=$row['dinero_cobrado'];
                $total_intereses+=$row['interes_por_mora'];
                $i++;
            }
        }
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style='text-align:right;'>$<?php echo number_format($total, 2, ',', '.')?></td>
            <td style='text-align:right;'>$<?php echo number_format($total_intereses, 2, ',', '.')?></td>
        </tr>
    </table><br /><br />

    DEPOSITOS
    <table>
        <tr>
            <th>FECHA</th>
            <th>USUARIO</th>
            <th>FORMA DE PAGO</th>
            <th>IMPORTE</th>
            <th>INTERESES</th>
            <th>INQUILINO</th>
            <th>CONCEPTO</th>
            <th>PROPIEDAD</th>
            <th>PROPIETARIO</th>
        </tr>
        <?php
        $dinero_cobrado = 0;
        $interes_mora = 0;
        $total_comision = 0;
        $total=0;
        $total_intereses=0;
        $i = 0;
        foreach ($resultado as $row) {
            if (($i % 2) == 0) {
                $estilo_color = "#e4ecf7";
            } else {
                $estilo_color = "#FFF";
            }
            $dinero_cobrado += $row['dinero_cobrado'];
            $interes_mora += $row['interes_por_mora'];
            $model_movimiento = null;
            $propietario = "";
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

                $model_movimiento = app\models\A2Movimientos::find()->where(
                    "id_liquidacion=:id_liquidacion AND 
                movimiento_tipo=:movimiento_tipo AND tipo_registro='pago'",
                    [':id_liquidacion' => $row['liquidaciones_id'], ':movimiento_tipo' => 3]
                )->one();

                $inmueble_prop = app\models\InmueblesPropietarios::find()->where(['inmueble_id' => $row_registro_prop['id_noticia']])->one();
                if ($inmueble_prop) {
                    $obj_propietario = $inmueble_prop->propietario;
                    if ($obj_propietario) {
                        $propietario = $obj_propietario->apellido . ", " . $obj_propietario->nombre;
                    }
                }
            } else {
                $model_ope_expensas = app\models\OperacionesExpensas::find()->where("id=:id", [':id' => $row['id']])->one();

                $model_movimiento = app\models\A2Movimientos::find()->where(
                    "id_movimiento=:id_movimiento AND 
                movimiento_tipo=:movimiento_tipo",
                    [':id_movimiento' => $model_ope_expensas->comprobante_id, ':movimiento_tipo' => 3]
                )->one();

                $inmueble_prop = app\models\InmueblesPropietarios::find()->
                    where(['inmueble_id' => $model_ope_expensas->inmuebles_id])->one();
                if ($inmueble_prop) {
                    $obj_propietario = $inmueble_prop->propietario;
                    if ($obj_propietario) {
                        $propietario = $obj_propietario->apellido . ", " . $obj_propietario->nombre;
                    }
                }
            }

            if ($model_movimiento) {
                echo '<tr>';
                echo "<td>" . $model_movimiento['usuario'] . "</td>";
                echo "<td>" . date("d/m/Y", strtotime($row['fecha_pago'])) . "</td>";

                
                echo "<td width='130'  style='text-align:right;'>$ " . number_format($model_movimiento['monto'], 2, ',', '.') . "</td>";
                echo "<td width='130' style='text-align:right;'>$ " . number_format(0, 2, ',', '.') . "</td>";
                echo "<td>" . substr($row['NOMBRE'], 0, 30) . "</td>";
                echo "<td width='60' style='text-align:center;'>" . $row['tipo'] . "</td>";
                echo "<td style='text-align:center'>" . $row['liq_mes'] . "-" . $row['liq_anio'] . "</td>";
                echo "<td width='200'>" . substr(utf8_decode($row['direccion']), 0, 30) . "</td>";

                echo "<td>{$propietario}</td>";

                echo "</tr>";
                $total+=$model_movimiento['monto'];
                $total_intereses+=0;
                $i++;
            }
        }
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style='text-align:right;'>$<?php echo number_format($total, 2, ',', '.')?></td>
            <td style='text-align:right;'>$<?php echo number_format($total_intereses, 2, ',', '.')?></td>
        </tr>
    </table><br /><br />
</div>