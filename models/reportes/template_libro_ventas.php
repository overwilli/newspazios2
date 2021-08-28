
<page orientation="paysage" >
    <page_footer>
        <table style="width: 100%;border:1px solid black ">
            <tr>
                <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y h:i:s') ?></td>
                <td style="text-align: right;    width: 50%"><b>PÃ¡gina [[page_cu]]/[[page_nb]]</b></td>
            </tr>
        </table>
    </page_footer>

    <?php
    $obj_fac = new Facturacion();
    $result = $obj_fac->obtener_facturacion_por_fechas(array('fecha_desde' => $fecha_desde,
        'fecha_hasta' => $fecha_hasta)); //INTERES POR MORA  
    ?>
    <h1>Listado de libro ventas</h1>
    <?php //echo "Numero de registros encontrados:" . mysql_num_rows($result);  ?>
    <table style="border-collapse: collapse"  width="80%"  align="center" >
        <thead><tr> 
                <th width="80" ><b>TIPO</b></th>   
                <th width="100" ><b>NRO. FACTURA</b></th>   
                <th width="80" ><b>FECHA</b></th>               

                <th width="200"><b>CLIENTE</b></th>
                <th width="120"><b>CUIL/CUIT</b></th>  
                <th width="80"><b>MONTO NETO</b></th>
                <th width="80"><b>IVA</b></th>
                <th width="70"><b>MONTO BRUTO</b></th>                 


            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            $i = 0;
            $total_bruto = 0;
            $total_neto = 0;
            $total_iva = 0;
            $total_factura_a = 0;
            $total_factura_b = 0;
            $total_neto_nota_credito = 0;

            $total_neto_a = 0;
            $total_neto_b = 0;
            $total_neto_n_credito = 0;
            $total_iva_a = 0;
            $total_iva_b = 0;
            $total_iva_n_credito = 0;

            $estilo_color = "#e4ecf7";
            while ($row = mysql_fetch_array($result)) {
                if (($i % 2) == 0) {
                    $estilo_color = "#e4ecf7";
                } else {
                    $estilo_color = "#FFF";
                }
                ?>
                <tr style="background-color:<?php echo $estilo_color ?>"> 
                    <td  valign="top"  >
                        <?php
                        print utf8_encode(strtoupper($row["tipo_movimiento"]));
                        if ($row["tipo_movimiento"] == 'Nota de Crédito' && $row["id_liquidacion"] != 0) {
                            echo " " . strtoupper($obj_fac->obtener_factura_por_liquidacion(array('id_liquidacion' => $row["id_liquidacion"])));
                        }
                        ?>
                    </td>   
                    <td  valign="top">
                        <?php
                        $nro_comprobante_fiscal = "";
                        $nro_cai = "";
                        $respuesta = json_decode($row["respuesta"]);
                        for ($j = 0; $j < count($respuesta); $j++) {
                            if ($row["tipo_movimiento"] != 'Nota de Crédito') {
                                if ($respuesta[$j][0] == 'CloseFiscalReceipt') {
                                    $item_factura = $respuesta[$j];

                                    if (array_key_exists(3, $item_factura)) {
                                        $nro_comprobante_fiscal = $respuesta[$j][3];


                                        $nro_cai = $respuesta[$j][5];
                                    }
                                    //$status_fiscal = $respuesta[$j][2];
                                    /* if ($status_fiscal->status_fiscal != 8620) {

                                      if (!$respuesta) {


                                      $nro_comprobante_fiscal = $respuesta[$j][3];
                                      echo "hola";

                                      $nro_cai = $respuesta[$j][5];
                                      }
                                      } */
                                }
                            } else {

                                if ($respuesta[$j][0] == 'CloseDNFH') {
                                    $nro_comprobante_fiscal = $respuesta[$j][3];
                                    $nro_cai = $respuesta[$j][5];
                                }
                            }
                        }
                        print $nro_comprobante_fiscal;
                        unset($nro_comprobante_fiscal);
                        unset($nro_cai);
                        ?>
                    </td>
                    <td  valign="top"  ><?php print date('d/m/Y', strtotime($row["fecha"])); ?></td>       
                    <td width="200" valign="top" >
                        <?php
                        if ($row["id_liquidacion"] != 0 && !empty($row["id_liquidacion"])) {
                            $obj_cliente = new Cliente();
                            $resultado = $obj_cliente->obtener_cliente_por_liquidacion(array('id_liquidacion' => $row["id_liquidacion"]));
                            if (mysql_num_rows($resultado) > 0) {
                                $fila = mysql_fetch_array($resultado);
                                print $fila['NOMBRE'];
                                $cuit = $fila['CUIL'];
                            } else {
                                $resultado = $obj_cliente->obtener_cliente_historial_por_liquidacion(array('id_liquidacion' => $row["id_liquidacion"]));
                                $fila = mysql_fetch_array($resultado);
                                print $fila['NOMBRE'];
                                $cuit = $fila['CUIL'];
                            }
                        }
                        ?></td>
                    <td width="120" valign="top" ><?php echo $cuit; ?></td>                    
                    <td width="70" valign="top"><?php
                    $monto_a_facturar = 0;
                    if ($row["id_liquidacion"] != 0 && !empty($row["id_liquidacion"])) {
                        if ($row["tipo_movimiento"] == 'Nota de Crédito') {
                            $monto_neto_nc = $row["monto"];
                            $monto_bruto_nc = ($monto_neto_nc / 1.21);
                            echo number_format($monto_bruto_nc, 2, ',', '.');
                            $total_neto_nota_credito+=$monto_bruto_nc;
                            /* echo $row["monto"];
                              $total_neto_n_credito+=$row["monto"]; */
                        } else {
                            $obj_liq = new Liquidacion();
                            $resultado = $obj_liq->obtener_liquidacion_por_liquidacion(array('id_liquidacion' => $row["id_liquidacion"]));
                            $fila = mysql_fetch_array($resultado);

                            //-------OBTIENE EL TOTAL DE FACTURAS A REALIZAR----
                            $obj_operacion = new Operacion();
                            $total_facturas_a_realizar = $obj_operacion->total_documento_por_operacion(array('id_operacion' => $fila["id_operacion"],
                                'anio' => $fila["liq_anio"], 'mes' => $fila["liq_mes"], 'tipo_movimiento' => $row["tipo_movimiento"]));

                            //-------OBTIENE EL TOTAL DE FACTURAS REALIZADAS----
                            $total_facturas_realizadas = $obj_fac->total_facturas_por_liquidacion(array('id_liquidacion' =>
                                $row["id_liquidacion"], 'tipo_movimiento' => $row["tipo_movimiento"]));



                            $monto_a_facturar = calcular_monto_bruto($fila["id_operacion"], $fila["liq_anio"], $fila["liq_mes"], $row["tipo_movimiento"]);

                            if ($total_facturas_a_realizar == $total_facturas_realizadas && $total_facturas_a_realizar > 1) {
                                $monto_a_facturar = $monto_a_facturar / $total_facturas_a_realizar;
                            }
                            $total_bruto+=$monto_a_facturar;

                            if ($row["tipo_movimiento"] == 'factura A') {
                                $total_neto_a+=$monto_a_facturar;
                            }
                            if ($row["tipo_movimiento"] == 'factura B') {
                                $total_neto_b+=$monto_a_facturar;
                            }

                            $monto_bruto = number_format($monto_a_facturar, 2, ',', '.');
                            echo $monto_bruto;
                        }
                    } else {
                        if ($row["tipo_movimiento"] == 'Nota de Crédito') {
                            $monto_neto_nc = $row["monto"];
                            $monto_bruto_nc = ($monto_neto_nc / 1.21);
                            echo number_format($monto_bruto_nc, 2, ',', '.');
                            $total_neto_nota_credito+=$monto_bruto_nc;
                            /* echo $row["monto"];
                              $total_neto_n_credito+=$row["monto"]; */
                        }
                    }
                        ?></td> 
                    <td width="80" valign="top">
                        <?php
                        if ($row["tipo_movimiento"] == 'Nota de Crédito') {
                            $monto_neto_nc = $row["monto"];
                            $monto_bruto_nc = ($monto_neto_nc / 1.21);
                            $iva_nc = $monto_neto_nc / 1.21 * 0.21;
                            echo number_format($iva_nc, 2, ',', '.');
                            $total_iva_n_credito+=$iva_nc;
                        } else {
                            if ($monto_a_facturar != 0 && $monto_a_facturar < $row["monto"]) {
                                $total_iva+=$row["monto"] - $monto_a_facturar;
                                echo number_format($row["monto"] - $monto_a_facturar, 2, ',', '.');

                                if ($row["tipo_movimiento"] == 'factura A') {
                                    $total_iva_a+=$row["monto"] - $monto_a_facturar;
                                }
                                if ($row["tipo_movimiento"] == 'factura B') {
                                    $total_iva_b+=$row["monto"] - $monto_a_facturar;
                                }
                            }
                        }
                        ?>
                    </td>
                    <td width="80" valign="top">
                        <?php
                        if ($row["tipo_movimiento"] == 'Nota de Crédito') {
                            $total_neto-=$row["monto"];
                        } else {
                            $total_neto+=$row["monto"];
                            if ($row["tipo_movimiento"] == 'factura A') {
                                $total_factura_a+=$row["monto"];
                            }
                            if ($row["tipo_movimiento"] == 'factura B') {
                                $total_factura_b+=$row["monto"];
                            }
                        }
                        if ($row["tipo_movimiento"] == 'Nota de Crédito') {
                            echo $row["monto"];
                            $total_neto_n_credito+=$row["monto"];
                            /* $monto_neto_nc = $row["monto"];
                              $monto_bruto_nc = ($monto_neto_nc / 1.21);
                              echo number_format($monto_bruto_nc, 2, ',', '.');
                              $total_neto_nota_credito+=$monto_bruto_nc; */
                        } else {
                            $monto_facturado = number_format($row["monto"], 2, ',', '.');
                            echo $monto_facturado;
                        }
                        ?>
                    </td>  

                </tr>
                <?php
                $total+=$row["monto"];
                $i++;
            }
            if ($estilo_color == "#e4ecf7") {
                $estilo_color = "#FFF";
            }
            ?>
            <tr style="background-color:<?php echo $estilo_color ?>"> 
                <td  valign="top"  ></td>   
                <td  valign="top">                    
                </td>
                <td  valign="top"  ></td>       
                <td width="200" valign="top" >

                </td>
                <td width="120" valign="top" ><b>Totales Factura A:</b></td>                    
                <td width="70" valign="top">
                    <?php echo number_format($total_neto_a, 2, ',', '.'); ?>
                </td>  
                <td width="80" valign="top"> 
                    <?php echo number_format($total_iva_a, 2, ',', '.'); ?>
                </td>  
                <td width="80" valign="top">
                    <?php echo number_format($total_factura_a, 2, ',', '.'); ?>
                </td>                     
            </tr>
            <tr style="background-color:<?php echo $estilo_color ?>"> 
                <td  valign="top"  ></td>   
                <td  valign="top">                    
                </td>
                <td  valign="top"  ></td>       
                <td width="200" valign="top" >

                </td>
                <td width="120" valign="top" ><b>Totales Factura B:</b></td>                    
                <td width="70" valign="top">
                    <?php echo number_format($total_neto_b, 2, ',', '.'); ?>
                </td>  
                <td width="80" valign="top"> 
                    <?php echo number_format($total_iva_b, 2, ',', '.'); ?>
                </td>  
                <td width="80" valign="top">
                    <?php echo number_format($total_factura_b, 2, ',', '.'); ?>
                </td>                     
            </tr>
            <tr style="background-color:<?php echo $estilo_color ?>"> 
                <td  valign="top"  ></td>   
                <td  valign="top">                    
                </td>
                <td  valign="top"  ></td>       
                <td width="200" valign="top" >

                </td>
                <td width="120" valign="top" ><b>Totales Factura A Y B:</b></td>                    
                <td width="70" valign="top">

                </td>  
                <td width="80" valign="top"> 

                </td>  
                <td width="80" valign="top">
                    <?php echo number_format($total_factura_a + $total_factura_b, 2, ',', '.'); ?>
                </td>                     
            </tr>
            <tr style="background-color:<?php echo $estilo_color ?>"> 
                <td  valign="top"  ></td>   
                <td  valign="top">                    
                </td>
                <td  valign="top"  ></td>       
                <td width="200" valign="top" >

                </td>
                <td width="120" valign="top" ><b>Totales Nota de Credito:</b></td>                    
                <td width="70" valign="top">
                    <?php echo number_format($total_neto_nota_credito, 2, ',', '.'); ?>
                </td>  
                <td width="80" valign="top"> 
                    <?php echo number_format($total_iva_n_credito, 2, ',', '.'); ?>
                </td>  
                <td width="80" valign="top">
                    <?php echo number_format($total_neto_n_credito, 2, ',', '.'); ?>
                </td>                     
            </tr>
            <tr style="background-color:<?php echo $estilo_color ?>"> 
                <td  valign="top"  ></td>   
                <td  valign="top">                    
                </td>
                <td  valign="top"  ></td>       
                <td width="200" valign="top" >

                </td>
                <td width="120" valign="top" ><b>Totales:</b></td>                    
                <td width="70" valign="top">
                    <?php echo number_format($total_bruto - $total_neto_nota_credito, 2, ',', '.'); ?>
                </td>
                <td width="80" valign="top">
                    <?php echo number_format($total_iva - $total_iva_n_credito, 2, ',', '.'); ?>
                </td>
                <td width="80" valign="top"> 
                    <?php //echo number_format($total_neto-$total_neto_n_credito, 2, ',', '.');  ?>
                    <?php echo number_format($total_neto, 2, ',', '.'); ?>
                </td>  

            </tr>           
        </tbody>
        <tfoot>
            <tr>
                <th style="">&nbsp;</th>
                <th style=""></th>
            </tr>
        </tfoot>
    </table>

</page>
<?php

function calcular_monto_bruto($id_operacion, $anio, $mes, $tipo_factura) {

    if ($tipo_factura == 'factura A') {
        $id_factura = 1;
    } else {
        $id_factura = 2;
    }

    //$datos = SesionUsuario();
    /*$cadena = "SELECT * FROM (
	SELECT 
	id_item,
	id_operacion,
	anio,
	mes,
	a2_tipos_facturacion.descripcion,
	a2_tipos_facturacion.iva,
	a2_tipos_facturacion.plantilla,	
	monto,
	a2_clientes.nombre,
	a2_clientes.direccion as direccion_cliente,
	a2_clientes.cuil,
	a2_operaciones_inmobiliarias.cod_propiedad,
	a2_noticias.direccion
		
	FROM a2_operaciones_items
	LEFT JOIN a2_tipos_facturacion ON
	a2_tipos_facturacion.id_facturacion=a2_operaciones_items.id_factura
	LEFT JOIN a2_operaciones_inmobiliarias ON
	a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_operaciones_items.id_operacion
	LEFT JOIN  a2_noticias ON
	 a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad	
	
	LEFT JOIN a2_clientes ON
	a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
	
	WHERE a2_operaciones_items.id_operacion=" . $id_operacion . "
	AND a2_operaciones_items.anio=" . $anio . " 
	AND a2_operaciones_items.mes=" . $mes . "
        AND a2_operaciones_items.id_factura='" . $id_factura . "'        
        UNION
	SELECT 
	id_item,
	id_operacion,
	anio,
	mes,
	a2_tipos_facturacion.descripcion,
	a2_tipos_facturacion.iva,
	a2_tipos_facturacion.plantilla,	
	monto,
	a2_clientes.nombre,
	a2_clientes.direccion as direccion_cliente,
	a2_clientes.cuil,
	a2_operaciones_inmobiliarias_copy.cod_propiedad,
	a2_noticias.direccion
		
	FROM a2_operaciones_items_copy
	LEFT JOIN a2_tipos_facturacion ON
	a2_tipos_facturacion.id_facturacion=a2_operaciones_items_copy.id_factura
	LEFT JOIN a2_operaciones_inmobiliarias_copy ON
	a2_operaciones_inmobiliarias_copy.id_operacion_inmobiliaria=a2_operaciones_items_copy.id_operacion
	LEFT JOIN  a2_noticias ON
	 a2_noticias.id_noticia=a2_operaciones_inmobiliarias_copy.cod_propiedad	
	
	LEFT JOIN a2_clientes ON
	a2_clientes.id_cliente=a2_operaciones_inmobiliarias_copy.cod_cliente
        WHERE a2_operaciones_items_copy.id_operacion=" . $id_operacion . "
	AND a2_operaciones_items_copy.anio=" . $anio . " 
	AND a2_operaciones_items_copy.mes=" . $mes . "
        AND a2_operaciones_items_copy.id_factura='" . $id_factura . "'
        ) AS T	ORDER BY anio ASC, mes DESC
	";*/
	
	$cadena = "SELECT * FROM (
	SELECT 
	id_item,
	id_operacion,
	anio,
	mes,
	a2_tipos_facturacion.descripcion,
	a2_tipos_facturacion.iva,
	a2_tipos_facturacion.plantilla,	
	monto,
	a2_clientes.nombre,
	a2_clientes.direccion as direccion_cliente,
	a2_clientes.cuil,
	a2_operaciones_inmobiliarias.cod_propiedad,
	a2_noticias.direccion
		
	FROM a2_operaciones_items
	LEFT JOIN a2_tipos_facturacion ON
	a2_tipos_facturacion.id_facturacion=a2_operaciones_items.id_factura
	LEFT JOIN a2_operaciones_inmobiliarias ON
	a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_operaciones_items.id_operacion
	LEFT JOIN  a2_noticias ON
	 a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad	
	
	LEFT JOIN a2_clientes ON
	a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
	
	WHERE a2_operaciones_items.id_operacion=" . $id_operacion . "
	AND a2_operaciones_items.anio=" . $anio . " 
	AND a2_operaciones_items.mes=" . $mes . "
        AND a2_operaciones_items.id_factura='" . $id_factura . "'        
        ) AS T	ORDER BY anio ASC, mes DESC
	";
	
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
        die();
    }

    $monto_total = 0;
    while ($row = mysql_fetch_array($result)) {
        /* if ($row["iva"] == 1) {
          if ($row["monto"] >= 1501) {
          $monto_sin_iva = $row["monto"];
          $monto = $row["monto"] + ($row["monto"] * IVA) / 100;
          $monto_a_imprimir = $row["monto"] + ($row["monto"] * IVA) / 100;
          } else {
          $monto = $row["monto"];
          $monto_a_imprimir = $row["monto"];
          }
          } else { */

        $monto = $row["monto"];
        $monto_a_imprimir = $row["monto"];
        //}

        $monto_total+=$monto; //acumulo para mostrar en la cabezera	        
    }

    return $monto_total;
}

function calcular_monto_iva($id_operacion, $anio, $mes, $tipo_factura) {

    if ($tipo_factura == 'factura A') {
        $id_factura = 1;
    } else {
        $id_factura = 2;
    }

    //$datos = SesionUsuario();
    $cadena = "
	SELECT 
	id_item,
	id_operacion,
	anio,
	mes,
	a2_tipos_facturacion.descripcion,
	a2_tipos_facturacion.iva,
	a2_tipos_facturacion.plantilla,	
	monto,
	a2_clientes.nombre,
	a2_clientes.direccion as direccion_cliente,
	a2_clientes.cuil,
	a2_operaciones_inmobiliarias.cod_propiedad,
	a2_noticias.direccion
		
	FROM a2_operaciones_items
	LEFT JOIN a2_tipos_facturacion ON
	a2_tipos_facturacion.id_facturacion=a2_operaciones_items.id_factura
	LEFT JOIN a2_operaciones_inmobiliarias ON
	a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_operaciones_items.id_operacion
	LEFT JOIN  a2_noticias ON
	 a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad	
	
	LEFT JOIN a2_clientes ON
	a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
	
	WHERE a2_operaciones_items.id_operacion=" . $id_operacion . "
	AND a2_operaciones_items.anio=" . $anio . " 
	AND a2_operaciones_items.mes=" . $mes . "
        AND a2_operaciones_items.id_factura='" . $id_factura . "'
	ORDER BY anio ASC, mes DESC
	";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
        die();
    }

    $monto_total = 0;
    while ($row = mysql_fetch_array($result)) {
        if ($row["iva"] == 1) {
            if ($row["monto"] >= 1501) {
                $monto_sin_iva = $row["monto"];
                $monto = $row["monto"] + ($row["monto"] * IVA) / 100;
                $monto_a_imprimir = $row["monto"] + ($row["monto"] * IVA) / 100;
            } else {
                $monto = $row["monto"];
                $monto_a_imprimir = $row["monto"];
            }
        } else {

            $monto = $row["monto"];
            $monto_a_imprimir = $row["monto"];
        }

        $monto_total+=$monto; //acumulo para mostrar en la cabezera	        
    }

    return $monto_total;
}
?>

