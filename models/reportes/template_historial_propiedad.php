
<page orientation="paysage" >
    <page_footer>
        <table style="width: 100%;border:1px solid black ">
            <tr>
                <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y h:i:s') ?></td>
                <td style="text-align: right;    width: 50%"><b>Página [[page_cu]]/[[page_nb]]</b></td>
            </tr>
        </table>
    </page_footer>

    <?php   
    /*$cadena = "SELECT * FROM (SELECT 
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
				a2_clientes.TELEFONO as telefono_cliente,
                                'CONTRATO VIGENTE' as estado_contrato,
                                a2_movimientos.fecha_movimiento
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
                                INNER JOIN a2_movimientos ON a2_movimientos.id_liquidacion=a2_liquidaciones.id_liquidacion
				WHERE 1	AND a2_secciones.sitio=1 AND a2_noticias.id_noticia={$propiedad_id} 
                                AND a2_movimientos.tipo_registro='pago' and a2_movimientos.movimiento_tipo<>9 and a2_movimientos.movimiento_tipo<>7
                                GROUP BY a2_movimientos.id_liquidacion
                    UNION
                    SELECT 
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
				a2_operaciones_inmobiliarias_copy.fecha_ope,
				a2_operaciones_inmobiliarias_copy.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias_copy.hasta_anio,
				a2_operaciones_inmobiliarias_copy.hasta_mes,
				a2_operaciones_inmobiliarias_copy.dia_venc_mensual,	
				a2_clientes.NOMBRE as nombrecliente,
				a2_clientes.TELEFONO as telefono_cliente,
                                'CONTRATO FINALIZADO O ELIMINADO' as estado_contrato,
                                a2_movimientos.fecha_movimiento
				FROM a2_liquidaciones 
				LEFT JOIN a2_operaciones_inmobiliarias_copy ON 
				a2_operaciones_inmobiliarias_copy.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
				LEFT JOIN a2_noticias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias_copy.cod_propiedad
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				LEFT JOIN a2_clientes ON
				a2_clientes.id_cliente=a2_operaciones_inmobiliarias_copy.cod_cliente 
                                INNER JOIN a2_movimientos ON a2_movimientos.id_liquidacion=a2_liquidaciones.id_liquidacion
				WHERE 1 AND a2_noticias.id_noticia={$propiedad_id} AND a2_movimientos.tipo_registro='pago'
                                    and a2_movimientos.movimiento_tipo<>9 and a2_movimientos.movimiento_tipo<>7
                                GROUP BY a2_movimientos.id_liquidacion
                                    ) as t1 
                                ORDER BY fecha_movimiento DESC             

";*/
    $cadena = "SELECT * FROM (SELECT 
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
    a2_clientes.TELEFONO as telefono_cliente,
                    'CONTRATO VIGENTE' as estado_contrato,
                    a2_movimientos.fecha_movimiento
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
                    INNER JOIN a2_movimientos ON a2_movimientos.id_liquidacion=a2_liquidaciones.id_liquidacion
    WHERE 1	AND a2_secciones.sitio=1 AND a2_noticias.id_noticia={$propiedad_id} 
                    AND a2_movimientos.tipo_registro='pago' and a2_movimientos.movimiento_tipo<>9 and a2_movimientos.movimiento_tipo<>7
                    GROUP BY a2_movimientos.id_liquidacion
                    ) as t1 
                    ORDER BY fecha_movimiento DESC             

    ";

    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
        die();
    }
    ?>
    <h1>Historial por Propiedad</h1>
    <b>Domicilio:<label style="font-size:20px"><?php echo strtoupper($model_inmueble->direccion);?>
        <?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .$localidad." - ".$provincia; ?></label></b><br/><br/>
    <?php //echo "Numero de registros encontrados:" . mysql_num_rows($result); ?><br/><br/>
	<b>Propietarios:</b>
	<?php	
	foreach($model_inmueble_prop as $row){		
		echo strtoupper($row->propietario->apellido.", ".$row->propietario->nombre." (".$row->porcentaje."%)");
	}
	?>
	<br/><br/>
	<style>
	td, th{
		padding:5px;		
	}
	</style>
    <table style="width:100%" <!--style="border-collapse: collapse"--> >
        <thead>
			<tr> 
                <th width="200px"><b>Fecha de Pago</b></th>
                <th><b>Cliente</b></th>
				<th><b>Concepto</b></th>
                <th>Per&iacute;odo</th>               
                <th><b>Monto</b></th>
				<th><b>Interes</b></th>
				<th><b>Total Pagado</b></th>
                <th><b>Tipo de Comprobante</b></th>
            </tr>
        </thead>
        <tbody>            

            <?php
            $i = 0;
            $total = 0;
            $total_expensas = 0;
            while ($row = mysql_fetch_array($result)) {
                if (($i % 2) == 0) {
                    $estilo_color = "#e4ecf7";
                } else {
                    $estilo_color = "#FFF";
                }
                $query = "SELECT * FROM a2_movimientos WHERE id_liquidacion=" . $row["id_liquidacion"] . " "
                        . " AND a2_movimientos.tipo_registro='pago' and a2_movimientos.movimiento_tipo<>9 ORDER BY"
                        . " fecha_movimiento DESC";//and a2_movimientos.movimiento_tipo<>7
                $resultado = mysql_query($query);
                if (mysql_errno() > 0) {
                    print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
                    die();
                }
                $fecha_pago = "";
                $mostrar_monto = "";
				$interes_monto = 0;
                if (mysql_num_rows($resultado) > 0) {
                    $fila = mysql_fetch_array($resultado);					
                    $total+=$fila['monto'];
                    $fecha_pago = date("d/m/Y", strtotime($fila['fecha_movimiento']));
                    $mostrar_monto = $fila['monto'];
                    mysql_data_seek($resultado,0);
                    while($fila = mysql_fetch_array($resultado)){
                        if($fila['movimiento_tipo']==7){
                            $interes_monto+=$fila['monto'];
                        }
                    }
                }
                ?>
                <tr style="background-color:<?php echo $estilo_color ?>"> 
                    <td width="200px" valign="top" ><?php print $fecha_pago; ?></td>
                    <td ><?php print utf8_encode($row['nombrecliente']) ?></td>
					<td style=" text-align: center;"><?php print "Alquiler" ?></td>
                    <td valign="top"><?php print $row["liq_mes"] . '/' . $row["liq_anio"]; ?></td>                            
                    <td style=" text-align: right;" valign="top"><?php echo number_format($mostrar_monto-$interes_monto,0, '', '.');; ?></td>
					<td style=" text-align: right;"><?php echo number_format($interes_monto,0, '', '.'); ?></td>
					<td style=" text-align: right;"><?php echo number_format($mostrar_monto,0, '', '.'); ?></td>
                    <td>
                        <?php
                        /*if ($row['estado_contrato'] == 'CONTRATO VIGENTE') {*/
                            $query = "SELECT 
                                id_item,
                                id_operacion,
                                anio,
                                mes,
                                a2_tipos_facturacion.descripcion,
                                monto
                                FROM a2_operaciones_items
                                LEFT JOIN a2_tipos_facturacion ON
                                a2_tipos_facturacion.id_facturacion=a2_operaciones_items.id_factura
                                WHERE a2_operaciones_items.id_operacion=" . $row["id_operacion_inmobiliaria"] . " AND
                                    a2_operaciones_items.anio=" . $row["liq_anio"] . " AND
                                    a2_operaciones_items.mes=" . $row["liq_mes"] . "  ORDER BY FIELD( a2_tipos_facturacion.descripcion,'Factura A','Factura B','Recibo \"X\"','Documento','Recibo Común') , anio DESC, mes DESC 
                                ";
                        /*} else {
                            $query = "SELECT 
                                id_item,
                                id_operacion,
                                anio,
                                mes,
                                a2_tipos_facturacion.descripcion,
                                monto
                                FROM a2_operaciones_items_copy
                                LEFT JOIN a2_tipos_facturacion ON
                                a2_tipos_facturacion.id_facturacion=a2_operaciones_items_copy.id_factura
                                WHERE a2_operaciones_items_copy.id_operacion=" . $row["id_operacion_inmobiliaria"] . " AND
                                    a2_operaciones_items_copy.anio=" . $row["liq_anio"] . " AND
                                    a2_operaciones_items_copy.mes=" . $row["liq_mes"] . "  ORDER BY anio DESC, mes DESC 
                                ";
                        }*/
                        $resultado1 = mysql_query($query);
                        if (mysql_errno() > 0) {
                            print mysql_errno() . " " . mysql_error() . "\n" . $query;
                            die();
                        }
                        while ($fila = mysql_fetch_array($resultado1)) {
							switch(utf8_encode($fila['descripcion'])){
								case 'Documento':{echo "D ";}break;
								case 'Factura A':{echo "A ";}break;
								case 'Factura B':{echo "B ";}break;
                                case 'Recibo Común':{echo "C ";}break;
                                case 'Recibo "X"':{echo "X ";}break;
								default:{echo utf8_encode($fila['descripcion']) . "<br/>";}break;
								//echo $fila['descripcion'];
							}							
                        }
                        ?>
                    </td>                					
                </tr>
                <?php
                $obj_expensa = new Expensas();
                $resultado_expensas = $obj_expensa->obtener_expensas_pagadas(array('operacion_id' => $row["id_operacion_inmobiliaria"], 'inmuebles_id' => $row["id_noticia"],
                    'mes' => $row["liq_mes"], 'year' => $row["liq_anio"]));
                if (mysql_num_rows($resultado_expensas) > 0) {
                    $i++; //contador para cambiar color de celdas
                    while ($fila_exp = mysql_fetch_array($resultado_expensas)) {
                        if (($i % 2) == 0) {
                            $estilo_color = "#e4ecf7";
                        } else {
                            $estilo_color = "#FFF";
                        }
                        echo '<tr style="background-color:' . $estilo_color . '">';

                        $query_exp = "SELECT * FROM a2_movimientos WHERE comprobante_pago_unico=" . $fila_exp["comprobante_id"] . " "
                        . " AND a2_movimientos.tipo_registro='pago' and a2_movimientos.movimiento_tipo<>9 ORDER BY"
                        . " fecha_movimiento DESC";//and a2_movimientos.movimiento_tipo<>7
                        $resultado_mov_exp = mysql_query($query_exp);
                        if (mysql_errno() > 0) {
                            print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
                            die();
                        }
                        $interes_mov_exp=0;
                        while($fila = mysql_fetch_array($resultado_mov_exp)){
                            if($fila['movimiento_tipo']==7){
                                $interes_mov_exp+=$fila['monto'];
                            }
                        }
                        ?>
                    <td valign="top"><?php print date('d/m/Y', strtotime($fila_exp["fecha_movimiento"])); ?></td> 
                    <td valign="top" ><?php print $fila_exp["nombrecliente"]; ?></td> 
					<td valign="top" style=" text-align: center;" ><?php print "Expensa"; ?></td> 
                    <td valign="top"><?php print $fila_exp["mes"] . "/" . $fila_exp["year"]; ?></td>                                      
                    <td style=" text-align: right;" valign="top"><?php echo number_format($fila_exp["importe"],0, '', '.'); ?></td>
					<td style=" text-align: right;"><?php echo number_format($interes_mov_exp,0, '', '.'); ?></td>
					<td style=" text-align: right;"><?php echo number_format($fila_exp["importe"]+$interes_mov_exp,0, '', '.');?></td>
                    <td valign="top"><?php print "C ";//$fila_exp["descripcion"]; ?></td>
					


                    <?php
                    echo '</tr>';
                    $total_expensas+=$fila_exp["importe"]+$interes_mov_exp;
                    $i++; //contador para cambiar color de celdas
                }
            } else {
                $i++; //contador para cambiar color de celdas
            }
        }
        ?>     
        </tbody>
        <tfoot>
            <tr>
                <th style=" text-align: left;">&nbsp;</th>
                <th style="width: 30%; text-align: left;"></th>
            </tr>
        </tfoot>
        <tr>
            <td valign="top"></td>

<!--            <td valign="top"></td>-->
            <td valign="top"></td>                        
            <td valign="top"><?php echo "<b>El total cobrado es:$</b>"; ?></td>
            <td valign="top"><?php echo "<b>" . number_format($total, 2, ',', '.') . "</b>"; ?></td>
        </tr>
        <tr>
            <td valign="top"></td>
            <td valign="top"></td>                        
            <td valign="top"><?php echo "<b>El total expensas es:$</b>"; ?></td>
            <td valign="top"><?php echo "<b>" . number_format($total_expensas, 2, ',', '.') . "</b>"; ?></td>
        </tr>
        <tr>
            <td valign="top"></td>
            <td valign="top"></td>                        
            <td valign="top"><?php echo "<b>El total general:$</b>"; ?></td>
            <td valign="top"><?php echo "<b>" . number_format($total + $total_expensas, 2, ',', '.') . "</b>"; ?></td>
        </tr>
    </table>


</page>