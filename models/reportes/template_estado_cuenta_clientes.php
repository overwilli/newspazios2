<?php 
$formatter = \Yii::$app->formatter;
?>
<page orientation="paysage" >
    <page_footer>
        <table style="width: 100%;border:1px solid black ">
            <tr>
                <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y h:i:s') ?></td>
                <td style="text-align: right;    width: 50%"><b>P&aacute;gina [[page_cu]]/[[page_nb]]</b></td>
            </tr>
        </table>
    </page_footer>

    <?php    
    $cadena = "SELECT 
				a2_liquidaciones.id_liquidacion,
				a2_liquidaciones.liq_anio,
				a2_liquidaciones.liq_mes,
				a2_liquidaciones.fecha_pago,
				a2_liquidaciones.monto_pagado,
				a2_liquidaciones.monto_intereses,
				a2_liquidaciones.usuario,
				a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_operaciones_inmobiliarias.dia_venc_mensual,	
				a2_clientes.NOMBRE as nombrecliente,
				a2_clientes.TELEFONO as telefono_cliente
				FROM a2_liquidaciones 
				LEFT JOIN a2_operaciones_inmobiliarias ON 
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
				LEFT JOIN a2_noticias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				LEFT JOIN a2_clientes ON
				a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
				WHERE 1								
				 
                                AND a2_secciones.sitio=1 AND a2_operaciones_inmobiliarias.cod_cliente={$cliente_id}  ORDER BY a2_noticias.titulo,a2_liquidaciones.liq_anio DESC, a2_liquidaciones.liq_mes DESC";

    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
        die();
    }
    ?>
	<style>
	td, th{
		padding:5px;		
	}
	</style>
	<div style="margin-left:10px">
    <h1>Estado de Cuenta del Cliente</h1>
    <label style="font-size:16px"><b><?php echo strtoupper($nombre) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $model_cliente->CUIL; ?></b></label><br/>
	<?php echo "Tel.: " . $model_cliente->TELEFONO." &nbsp;&nbsp;&nbsp;Tel . 3: " . $model_cliente->TELEF2." &nbsp;&nbsp;&nbsp;Tel. 3: " . $model_cliente->TELEF3; ?><br/><br/>
    <?php echo "Numero de registros encontrados:" . mysql_num_rows($result); ?><br/><br/>
	</div>
	<?php 
	if (mysql_num_rows($result)>0){
		
	?>
    <table   style="width:90%"  align="center" ><!--style="border-collapse: collapse"-->
        <thead><tr> 
                <th><b>Propiedad</b></th>                
				<th><b>Concepto</b></th>   
                <!--<th><b>Vigencia Contrato</b></th>
                <th>Telefono</th>-->
                <th>Per&iacute;odo</th>
                
                <th><b>Estado</b></th>
                <th><b>Fecha de Pago</b></th>
				<th><b>Monto</b></th>
				<th><b>Interes</b></th>
                <th><b>Total Pagado</b></th>
            </tr>
        </thead>
        <tbody>            

            <?php
            $i = 0;
            while ($row = mysql_fetch_array($result)) {
                if (($i % 2) == 0) {
                    $estilo_color = "#e4ecf7";
                } else {
                    $estilo_color = "#FFF";
                }
                ?>
                <tr style="background-color:<?php echo $estilo_color ?>"> 
                    <td valign="top" ><?php print utf8_encode($row["direccion"]); ?></td>                            
					<td valign="top" style="text-align:center;"><?php print utf8_encode("Alquiler"); ?></td>
                    <!--<td valign="top"><?php //print ($row["fecha_ope"] == "") ? "" : $row["hasta_mes"] . '/' . $row["hasta_anio"]; ?></td>-->
                    <!--<td valign="top"><?php //print $row["telefono_cliente"]; ?></td>-->
                    <!--<td valign="top"><?php //print $row["liq_mes"] . '/' . $row["liq_anio"] . ' Vencimiento: ' . $row["dia_venc_mensual"]; ?></td>-->
					<td valign="top"><?php print $row["liq_mes"] . '/' . $row["liq_anio"]; ?></td>

                    

                    <?php
                    if ($row["fecha_pago"] == "0000-00-00 00:00:00") { // si est� pagadoo controlo si coloreo los vencidos
                        $aux_fecha_actual = date("Y") + date("m");
                        $aux_fecha_vencimiento = $row["liq_anio"] + $row["liq_mes"];

                        $zz_fecha_venc_liquidacion = $row["liq_anio"] . '-' . $row["liq_mes"] . '-' . $row["dia_venc_mensual"];
                        $zz_fecha_actual = date("Y") . '-' . date("m") . '-' . date("d");
                        $zz_dias_mora = (strtotime($zz_fecha_actual) - strtotime($zz_fecha_venc_liquidacion)) / 86400;

                        if ($zz_dias_mora > 0) {
                            
                        }
                        ?>
                        <td valign="top"><b>Impago</b></td>
                        <?php
                    } else {
                        ?>
                        <td valign="top">Pagado</td>
                        <?php
                    }
                    $query = "SELECT * FROM a2_movimientos WHERE id_liquidacion=" . $row["id_liquidacion"];
                    $resultado = mysql_query($query);
                    if (mysql_errno() > 0) {
                        print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
                        die();
                    }
                    if (mysql_num_rows($resultado) > 0) {
                        $fila = mysql_fetch_array($resultado)
                        ?>
                        <td valign="top"><?php echo date("d/m/Y",  strtotime($fila['fecha_movimiento']))?></td>
						<td valign="top" style="text-align: right"><?php
                        $aux_monto_a_cobrar = Calcular_Monto_Periodo($row["id_operacion_inmobiliaria"], $row["liq_anio"], $row["liq_mes"], 0);
                        if ($aux_monto_a_cobrar == 0)
                            print '<div class="Errores">$' . number_format($aux_monto_a_cobrar, 0, ',', '.') . '</div>';
                        else
                            print '$' . number_format($aux_monto_a_cobrar, 0, ',', '.');
                        ?></td>
						<td valign="top"></td>		
                        <td valign="top"><?php echo $formatter->asCurrency($fila['monto']);?></td>
										
                        <?php
                    } else {
                        ?>
                        <td valign="top"></td>
						<td valign="top" style="text-align: right"><?php
                        $aux_monto_a_cobrar = Calcular_Monto_Periodo($row["id_operacion_inmobiliaria"], $row["liq_anio"], $row["liq_mes"], 0);
                        if ($aux_monto_a_cobrar == 0)
                            print '<div class="Errores">$' . number_format($aux_monto_a_cobrar, 0, ',', '.') . '</div>';
                        else
                            print '$' . number_format($aux_monto_a_cobrar, 0, ',', '.');
                        ?></td>
                        <td valign="top"></td>
						<td valign="top"></td>
                        <?php
                    }
                    $i++;
                    ?>
                </tr>
                <?php
				$query = "SELECT * FROM expensas_pagadas WHERE operacion_id=" . $row["id_operacion_inmobiliaria"]." AND mes=".$row["liq_mes"]." AND year=".$row["liq_anio"];
				$resultado = mysql_query($query);
				if (mysql_errno() > 0) {
					print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
					die();
				}
				if (mysql_num_rows($resultado) > 0) {
					if (($i % 2) == 0) {
						$estilo_color = "#e4ecf7";
					} else {
						$estilo_color = "#FFF";
					}
					$fila = mysql_fetch_array($resultado);
					
					//BUSCA LA FECHA DE PAGO Y SI HAY INTERES
					$query = "SELECT * FROM a2_movimientos WHERE comprobante_pago_unico=" . $fila["comprobante_id"];
					$resul_interes = mysql_query($query);
					if (mysql_errno() > 0) {
						print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
						die();
					}
					$fecha_pago_int="";
					$monto_pago_exp=0;
					$monto_interes_exp=0;
					if (mysql_num_rows($resul_interes) > 0) {						
						
						while ($fila_exp = mysql_fetch_array($resul_interes)) {
							if($fila_exp['movimiento_tipo']==11){
								$fecha_pago_int=date('d/m/Y',strtotime($fila_exp['fecha_movimiento']));
								$monto_pago_exp=$fila_exp['monto'];
							}
							if($fila_exp['movimiento_tipo']==7){
								$monto_interes_exp=$fila_exp['monto'];
							}
						}
						//$fila_interes_pagado = mysql_fetch_array($resul_interes);
						
					}
					?>
					<tr style="background-color:<?php echo $estilo_color ?>"> 
						<td valign="top" ><?php print utf8_encode($row["direccion"]); ?></td>                            
						<td valign="top" style="text-align:center;"><?php print utf8_encode("Expensas"); ?></td>						
						<td valign="top"><?php print $row["liq_mes"] . '/' . $row["liq_anio"]; ?></td>
						<td valign="top"><?php print utf8_encode("Pagado"); ?></td>
						<td valign="top"><?php echo $fecha_pago_int; ?></td>
						<td valign="top"><?php echo '$' . number_format($fila["pagado"], 0, ',', '.'); ?></td>
						<td valign="top"><?php echo '$' . number_format($monto_interes_exp, 0, ',', '.'); ?></td>
						<td valign="top"><?php echo '$' . number_format($monto_pago_exp, 0, ',', '.'); ?></td>
					</tr>
					<?php
					$i++;
				}
				
				$query = "SELECT * FROM deuda_expensas WHERE operacion_id=" . $row["id_operacion_inmobiliaria"]." AND mes=".$row["liq_mes"]." AND year=".$row["liq_anio"];
				$resultado = mysql_query($query);
				if (mysql_errno() > 0) {
					print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
					die();
				}
				if (mysql_num_rows($resultado) > 0) {
					if (($i % 2) == 0) {
						$estilo_color = "#e4ecf7";
					} else {
						$estilo_color = "#FFF";
					}
					$fila = mysql_fetch_array($resultado);
					?>
					<tr style="background-color:<?php echo $estilo_color ?>"> 
						<td valign="top" ><?php print utf8_encode($row["direccion"]); ?></td>                            
						<td valign="top" style="text-align:center;"><?php print utf8_encode("Expensas"); ?></td>						
						<td valign="top"><?php print $row["liq_mes"] . '/' . $row["liq_anio"]; ?></td>
						<td valign="top"><?php print utf8_encode("Impago"); ?></td>
						<td valign="top" ><?php //print utf8_encode("Pagado"); ?></td>
						<td valign="top"><?php echo '$' . number_format($fila["deuda"], 0, ',', '.'); ?></td>
						<td valign="top"><?php echo '$' . number_format(0, 0, ',', '.'); ?></td>
						<td valign="top"><?php echo '$' . number_format(0, 0, ',', '.'); ?></td>
					</tr>
					<?php
					$i++;
				}
            }
            ?>     
        </tbody>
        <tfoot>
            <tr>
                <th style="width: 30%; text-align: left;">&nbsp;</th>
                <th style="width: 30%; text-align: left;"></th>
            </tr>
        </tfoot>
    </table>
	<?php
		
	}
	?>
</page>