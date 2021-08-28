<?php
set_time_limit(0);

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");

header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=morosos.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

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
                                a2_clientes.TELEF2,
                                a2_clientes.TELEF3
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
                                LEFT JOIN  a2_movimientos 
                                ON a2_liquidaciones.id_liquidacion=a2_movimientos.id_liquidacion
				WHERE 1								
				 
				AND a2_secciones.sitio=1 AND a2_liquidaciones.fecha_pago='0000-00-00 00:00:00' UNION
SELECT DISTINCT
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
                                a2_clientes.TELEF2,
                                a2_clientes.TELEF3
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
                                LEFT JOIN  a2_movimientos 
                                ON a2_liquidaciones.id_liquidacion=a2_movimientos.id_liquidacion
				WHERE 1								
				 
				AND a2_secciones.sitio=1 AND (tipo_registro='pago_pendiente'AND estado_pago_pendiente='pendiente')
) as t1 ORDER BY t1.titulo,t1.liq_anio DESC, t1.liq_mes DESC";

$result = mysql_query($cadena);
if (mysql_errno() > 0) {
    print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
    die();
}
?>
<h1>Listado de Morosos</h1>
<h2>Liq. de Inmuebles</h2>
<?php //echo "Numero de registros encontrados:" . mysql_num_rows($result);  ?>
<table style="border-collapse: collapse"  width="80%"  align="center" >
    <thead><tr> 
            <th width="150" ><b>Direcci&oacute;n</b></th>                
            <th width="150">Nombre del Inquilino</th>
            <th width="110">Telefono</th>
            <th width="110">Cel. 1</th>
            <th width="110">Cel. 2</th>

<!--                <th><b>Vigencia Contrato</b></th>-->


            <th>Per&iacute;odo Adeudado</th>
            <th><b>Importe Adeudado</b></th>
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
            //if ($row["fecha_pago"] == "0000-00-00 00:00:00") { // si está pagadoo controlo si coloreo los vencidos
            $zz_fecha_venc_liquidacion = $row["liq_anio"] . '-' . $row["liq_mes"] . '-' . $row["dia_venc_mensual"];
            $zz_fecha_actual = date("Y") . '-' . date("m") . '-' . date("d");
            $zz_dias_mora = (strtotime($zz_fecha_actual) - strtotime($zz_fecha_venc_liquidacion)) / 86400;

            if ($zz_dias_mora > 0) {

                $aux_fecha_actual = (date("Y") * 10) + date("m");
                $aux_fecha_vencimiento = ($row["liq_anio"] * 10) + $row["liq_mes"];
                if (($aux_fecha_vencimiento - $aux_fecha_actual) <= 0) {
                    ?>
                    <tr style="background-color:<?php echo $estilo_color ?>"> 
                        <td valign="top" width="250"  ><?php print $row["titulo"]; ?></td>                            
                        <td valign="top" width="250"><?php print $row["nombrecliente"]; ?></td>
                        <td valign="top"><?php print $row["telefono_cliente"]; ?></td>
                        <td  valign="top"><?php print $row["TELEF2"]; ?></td>  
                        <td valign="top"><?php print $row["TELEF3"] ?></td>  
            <!--                        <td valign="top"><?php //print ($row["fecha_ope"] == "") ? "" : $row["hasta_mes"] . '/' . $row["hasta_anio"];     ?></td>-->

                        <td valign="top"><?php print $row["liq_mes"] . '/' . $row["liq_anio"] . ' Vencimiento: ' . $row["dia_venc_mensual"]; ?></td>                            

                        <td valign="top" style="text-align: right">
                            <?php
                            $aux_monto_a_cobrar = Calcular_Monto_Periodo($row["id_operacion_inmobiliaria"], $row["liq_anio"], $row["liq_mes"], 0);
                            if (round($zz_dias_mora) > 0) {
                                $interes_por_dia = Interes_por_dia_para_una_operacion($row["id_operacion_inmobiliaria"]);
                                $interes_por_mora = (($zz_dias_mora * $interes_por_dia) * $aux_monto_a_cobrar) / 100;
                                //$interes_por_mora=(($dias_atraso*INTERES_DIA_MORA)*$monto_a_facturar)/100;
                                $interes_por_mora = number_format($interes_por_mora, CANT_DIGITOS, '.', '');
                                $aux_monto_a_cobrar = $aux_monto_a_cobrar + $interes_por_mora;
                            }
                            if ($aux_monto_a_cobrar == 0)
                                print '<div class="Errores">$' . number_format($aux_monto_a_cobrar, CANT_DIGITOS, '.', ',') . '</div>';
                            else
                                print '$' . number_format($aux_monto_a_cobrar, CANT_DIGITOS, '.', ',');
                            ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            //}
        }
        ?>     
    </tbody>
    <tfoot>
        <tr>
            <th style="">&nbsp;</th>
            <th style=""></th>
        </tr>
    </tfoot>
</table>
