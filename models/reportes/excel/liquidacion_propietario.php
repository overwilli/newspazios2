<?php

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=liquidacion_propietarios.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

$obj_liqpagadas = new LiquidacionPropietario();
$resultado = $obj_liqpagadas->obtener_liquidacion_by_id($orden_id);
$row = mysql_fetch_array($resultado);
$orden_de_pago_id = $row['id'];
$gastos = $row['gastos'];
?>
<table   width="80%">
    <tbody>
        <tr>
            <td width="500">
                <table   width="80%">
                    <tbody>
                        <tr>
                            <td width="200"><label >Liquidaci&oacute;n N&uacute;mero:</label></td>
                            <td width="100"><?php echo $row['id'] ?></td>

                        </tr>
                        <tr>
                            <td width="200"><label class="etiqueta1">Fecha de Liquidaci&oacute;n:</label></td>
                            <td width="100"><?php echo date("d/m/Y", strtotime($row['fecha'])) ?></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td >

            </td>
        </tr>
    </tbody>
</table>
<br/><br/>
<table   width="80%">
    <tbody>
        <tr>
            <td width="200"><label >Propietario:</label></td>
            <td width="100"><b><?php
                    $obj_propietario = new Propietario();
                    $resultado_prop = $obj_propietario->obtener_propietario_por_id($row['propietario_id']);
                    $registro_prop = mysql_fetch_array($resultado_prop);
                    echo strtoupper($registro_prop['apellido'] . "," . $registro_prop['nombre']);
                    ?></b></td>
        </tr>        
        <tr>
            <td width="200"><label >Cuit:</label></td>
            <td width="300"><b><?php
                $obj_prop = new app\models\Propietarios();
                $obj_prop->cuit=$registro_prop['cuit'];                        
                echo strtoupper($obj_prop->obtener_cuit());                    
                    ?></b></td>
        </tr>
    </tbody>
</table>

<br/>
<hr/>

<table class="tablalistado"  width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
    <thead>
        <tr id="cabezera-tabla"> 
            <td height="36" style="font-weight: bold">FECHA DE PAGO</td>	          
            <td height="36" style="font-weight: bold">PROPIEDAD</td>
            <td height="36" style="font-weight: bold">INQUILINO</td>	
            <td height="36" style="font-weight: bold;text-align: center">MES</td>
            <td height="36" style="font-weight: bold;text-align: center">A&Ntilde;O</td>	              

            <td height="36" style="font-weight: bold">TIPO</td>		                            	
			<td height="36" style="font-weight: bold">IMPORTE</td>		                            	
            <td height="36" style="font-weight: bold">MORA</td>                 
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
		$cadena="SELECT * FROM (SELECT a2_noticias.direccion,a2_clientes.NOMBRE,liqpagadas_detalle.id,
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
			$cadena.=" direccion,fecha_pago ASC, liq_anio,liq_mes ASC";
        /*if ($_GET['ordena_por'] == 2) {
            $cadena.=" fecha_pago ASC,titulo, liq_anio,liq_mes ASC";
        } else {*/
            //$cadena.=" titulo,fecha_pago ASC, liq_anio,liq_mes ASC";
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
            $dinero_cobrado+=$row['dinero_cobrado'];
            $interes_mora+=$row['interes_por_mora'];
			if($row['tipo']=='ALQUILER'){ 				
				$obj_propiedad = new Propiedad();
				$registro_prop = $obj_propiedad->obtener_propiedad_por_liquidacion(array('id_liquidacion' => $row['liquidaciones_id']));

				if (mysql_num_rows($registro_prop) > 0) {
					$row_registro_prop = mysql_fetch_array($registro_prop);
				} else {
					$registro_prop = $obj_propiedad->obtener_propiedad_historial_por_liquidacion(array('id_liquidacion' => $row['liquidaciones_id']));
					$row_registro_prop = mysql_fetch_array($registro_prop);
				}

				$comision = $obj_propiedad->obtener_comision_por_inmueble(array('inmuebles_id' => $row_registro_prop['id_noticia']));
				$total_comision+=($row['dinero_cobrado'] * $comision) / 100;
			}
            echo '<tr>';
            echo "<td>" . date("d/m/Y", strtotime($row['fecha_pago'])) . "</td>";
            echo "<td width='200'>" . substr($row['direccion'], 0, 30) . "</td>";
            echo "<td>" . substr($row['NOMBRE'], 0, 30) . "</td>";
            echo "<td style='text-align:center'>" . $row['liq_mes'] . "</td>";
            echo "<td style='text-align:center'>" . $row['liq_anio'] . "</td>";
			echo "<td width='60' style='text-align:center;'>" . $row['tipo'] . "</td>";
            echo "<td width='130'  style='text-align:right;'>$ " . number_format(($row['dinero_cobrado'] - $row['interes_por_mora']), CANT_DIGITOS, ',', '.') . "</td>";
            echo "<td width='130' style='text-align:right;'>$ " . number_format($row['interes_por_mora'], CANT_DIGITOS, ',', '.') . "</td>";
            echo "</tr>";
            $i++;
        }
        ?>

    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td width='200'></td>
            <td colspan="3" style='text-align:center'><b>Subtotal</b></td>            

            <td style='text-align:right'>$ <?php echo number_format(($dinero_cobrado - $interes_mora), CANT_DIGITOS, '.', ',') ?></td>
            <td style='text-align:right'>$ <?php echo number_format($interes_mora, CANT_DIGITOS, '.', ',') ?></td>
        </tr>
    </tfoot>
</table>
<hr/>
<div style="text-align:center">
	GASTOS EFECTUADOS
</div>
	<table  width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
        <thead>
            <tr id="cabezera-tabla"> 
                <td width='50' height="36" style="font-weight: bold;">Fecha</td>	          
                <td width='150' height="36" style="font-weight: bold;min-width: 300px">Propiedad</td>
                <td width='150' height="36" style="font-weight: bold;min-width: 300px">Inquilino</td>	
                
                <td width='150' height="36" style="font-weight: bold;text-align: center;">Observacion</td>	                                   
				<td width='50' height="36" style="font-weight: bold;text-align: center;">Importe</td>
            </tr>
        </thead>
        <tbody>			
			
			<?php 
			$gasto_total = 0;
			foreach ($model_detalle_gastos as $row) {
				//($row = mysql_fetch_array($resultado)) {
				/*$gasto_total+=$row->importe;        
			   

				
				$model_detalle_gastos = \app\models\Gastos::find()->where(['id' => $row->gastos_id])->one();
				echo "<tr>";
				echo "<td>" . date('d/m/Y',strtotime($model_detalle_gastos->fecha)) . "</td>";
				echo "<td>" . $model_detalle_gastos->inmueble->direccion . "</td>";
				echo "<td>" . $model_detalle_gastos->operacionInmobiliaria->cliente->NOMBRE . "</td>";				      
				echo "<td>" . $model_detalle_gastos->observacion . "</td>";	
				echo "<td>" . $formatter->asCurrency($row->importe) . "</td>";  				
                echo "</tr>";*/
                $model_mov = \app\models\A2Movimientos::find()->where(['id_movimiento' => $row['movimientos_id']])->one();
                if ($model_mov->movimiento_tipo == 8) {
                    $gasto_total -= $row->importe;
                } else {
                    $gasto_total += $row->importe;
                }
                $model_operacion = \app\models\A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' =>  $model_mov->operacion_id])->one();
                if ($model_operacion) {
                    echo "<tr>";
                    echo "<td >" . date('d/m/Y', strtotime($model_mov->fecha_movimiento)) . "</td>";
                    echo "<td >" . $model_operacion->inmueble->direccion . "</td>";
                    echo "<td >" . $model_operacion->cliente->NOMBRE . "</td>";
                    echo "<td >" . (($model_mov->movimiento_tipo == 8) ? "Ingreso:" : "Egreso:" . $model_mov->observaciones) . "</td>";
                    echo "<td style='text-align:right'>" . $row['importe']. "</td>";
                    echo "</tr>";
                }
			}
			?>
			<tr >
                <td colspan="5" style="padding-top: 10px">
                    <hr/>
                </td>
            </tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td><b>Subtotales:</b></td>
				<td><b>$<?php echo number_format($gasto_total, 2, ',', '.'); ?></b></td>               
			</tr>
		</tbody> 
        <tfoot>
            <tr>
                <th >&nbsp;</th>
                <th >&nbsp;</th>
                <th >&nbsp;</th>
            </tr>
            <tr>
                <th >&nbsp;</th>
                <th >&nbsp;</th>
                <th >&nbsp;</th>
            </tr>
        </tfoot>        
    </table>

<?php
$gastos = (empty($gastos)) ? 0 : $gastos;
?>


<table width="100%"  cellspacing="10"   >
    <tr>
        <td></td>
        <td></td>
        <td width="400"></td>
        <td width="150"><label class="ancho_label totales">Cobranzas Efectuadas</label></td>
        <td style="text-align: right">$<?php echo number_format($dinero_cobrado, 2, '.', ','); ?></td>
    </tr>
<!--    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td width="150"><label class="ancho_label totales">Total Interes Mora</label></td>
        <td style="text-align: right">$<?php //echo number_format($interes_mora, 2, ',', '.');       ?></td>
    </tr>-->
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td width="150"><label class="ancho_label totales">Importe de Comisiones</label></td>
        <td style="text-align: right">$<?php echo number_format($total_comision, 2, '.', ','); ?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td width="150"><label class="ancho_label totales">Otros Gastos</label></td>
        <td style="text-align: right">$<?php echo (empty($gastos)) ? 0.00 : number_format($gastos, 2, '.', ','); ?></td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td width="150" ><label class="ancho_label totales">Importe a Percibir</label></td>
        <td style="text-align: right">$<b><?php echo number_format($dinero_cobrado - ($gastos + $total_comision), 2, '.', ','); ?></b></td>
    </tr>
</table>