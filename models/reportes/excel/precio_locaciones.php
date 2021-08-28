<?php

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=precio_locaciones.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

$cadena = "SELECT a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion as Direccion_inmueble,
				a2_noticias.precio,
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
				a2_clientes.nombre,
				a2_clientes.direccion,
				a2_clientes.telefono	
				
				FROM a2_noticias 
				LEFT JOIN a2_secciones				
				ON a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
				LEFT JOIN a2_operaciones_inmobiliarias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_clientes ON
				 a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente		
                                 WHERE
				a2_secciones.sitio=1 ORDER BY a2_noticias.titulo ASC";

$result = mysql_query($cadena);
if (mysql_errno() > 0) {
    print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
    die();
}
?>
<h1>Listado de Precio de Locaciones</h1>
<?php echo "Numero de registros encontrados:" . mysql_num_rows($result); ?>
<table style="border-collapse: collapse"  width="80%"  align="center" >
    <thead><tr> 
            <th><b>Direcci&oacute;n</b></th>
            <th>Nombre del Inquilino</th>
            <th><b>Precio</b></th>
            <th><b>Tipo de Comprobante</b></th>
            <th><b>Vigencia Contrato</b></th>

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
                <td valign="top" width="300" ><?php print $row["titulo"]; ?></td>
                <td valign="top"><?php print $row["nombre"]; ?></td>
                <!--<td valign="top"><?php //print $row["precio"]; ?></td>-->
                 <?php
                    $precio = "";
                    $descripcion = "";
                    if (!empty($row["id_operacion_inmobiliaria"])) {
                        $year=date("Y");
                        $mes=date("m");
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
                                WHERE anio={$year} AND mes={$mes} AND a2_operaciones_items.id_operacion=" . $row["id_operacion_inmobiliaria"] . "
                                ORDER BY anio DESC, mes DESC 
                                ";
                        $resultado = mysql_query($query);
                        if (mysql_errno() > 0) {
                            print mysql_errno() . " " . mysql_error() . "\n" . $query;
                            die();
                        }

                        $mes = 0;
                        $anio = 0;
                        $j = 0;
                        while ($fila = mysql_fetch_array($resultado)) {
                            if ($j == 0) {
                                $mes = $fila['mes'];
                                $anio = $fila['anio'];
                            }
                            if ($mes == $fila['mes'] && $anio == $fila['anio']) {
                                $precio .= number_format($fila['monto'],CANT_DIGITOS,'.',','). "<br/>";
                                $descripcion .= $fila['descripcion'] . "<br/>";
                            }
                            $j++;
                        }
                    }
                    ?>

                    <td>
                        <?php print $precio; ?>
                    </td>
                    <td>
                        <?php print $descripcion; ?>
                    </td>
                <td valign="top"><?php print ($row["fecha_ope"] == "") ? "" : $row["hasta_mes"] . '/' . $row["hasta_anio"]; ?></td>

            </tr>
            <?php
            $i++;
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

