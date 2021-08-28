<?php

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");

header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=morosos.xls");
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
				a2_clientes.telefono,	
                                a2_clientes.TELEFONO as telefono_cliente,
                                a2_clientes.TELEF2,
                                a2_clientes.TELEF3,
				deuda_expensas.deuda,
                                deuda_expensas.mes,
                                deuda_expensas.year
                                
				FROM a2_noticias 
				LEFT JOIN a2_secciones				
				ON a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion			
				LEFT JOIN a2_operaciones_inmobiliarias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_clientes ON
				 a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                LEFT JOIN deuda_expensas ON
				 a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=deuda_expensas.operacion_id

				WHERE fecha_ope<>'' AND a2_secciones.sitio=1 AND deuda_expensas.deuda>0 GROUP BY a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,deuda_expensas.mes,deuda_expensas.year 

ORDER BY a2_noticias.direccion ASC,deuda_expensas.year,deuda_expensas.mes";

$result = mysql_query($cadena);
if (mysql_errno() > 0) {
    print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
    die();
}
?>
<h2>Liq. de Expensas</h2>

<table width="80%" border="0" cellspacing="4" cellpadding="0" align="center" >
    <thead><tr id="cabezera-tabla"> 

            <th height="36">DIRECCION</th>
            <th height="36">CLIENTE</th>   
            <th>PERIODO</th>    
            <th wihth="110">Telefono</th>
            <th width="110">Cel. 1</th>
            <th width="110">Cel. 2</th>    


            <th>MONTO</th>  
        </tr>
    </thead>
    <tbody> 
        <?php
        $estilo = "filapar";
        $i = 0;
        $contador = 0;
        while ($row = mysql_fetch_array($result)) {
            if (($i % 2) == 0) {
                $estilo_color = "#e4ecf7";
            } else {
                $estilo_color = "#FFF";
            }
            if ($estilo == "filapar")
                $estilo = "filaimpar";
            else
                $estilo = "filapar";
            $aux_fecha_actual = (date("Y") * 10) + date("m");
            $aux_fecha_vencimiento = ($row["hasta_anio"] * 10) + $row["hasta_mes"];
            if ($aux_fecha_vencimiento != "") {// si tiene operacion vinculada y fecha de vencimiento
                if ((($aux_fecha_vencimiento - $aux_fecha_actual) <= 2) && (($aux_fecha_vencimiento - $aux_fecha_actual) >= 0 ))
                    $estilo = "filacontratoporvencer";
                if (($aux_fecha_vencimiento - $aux_fecha_actual) < 0)
                    $estilo = "filacontratovencido";
            }

            //if($filtro=="Por_vencer" &&  $estilo!="filacontratoporvencer") //ESTO ESTABA ANTES PERO NO ME MOSTRABA LOS YA VENCIDOS
            if ($filtro == "Por_vencer" && ($estilo != "filacontratoporvencer" && $estilo != "filacontratovencido"))
                continue;
            //--------VERIFICA LA EXISTENCIA DE LA LIQUIDACION PARA MOSTRAR LA EXPENSAS-----
            $obj_liquidacion = new Liquidacion();
            $resultado = $obj_liquidacion->existe_liquidacion(array('id_operacion' => $row["id_operacion_inmobiliaria"], 'year' => $row["year"], 'mes' => $row["mes"]));
            $registro_liq = mysql_fetch_array($resultado);
            if ($registro_liq['cantidad'] > 0) {
                ?>
                <tr style="background-color:<?php echo $estilo_color ?>" > 
                    <td valign="top" width="150"><?php print $row["Direccion_inmueble"]; ?></td> 
                    <td valign="top" width="150"><?php print $row["nombre"]; ?></td>                                      
                    <td valign="top" width="150"><?php print $row["mes"] . "/" . $row["year"]; ?></td> 
                    <td width="110" valign="top"><?php print $row["telefono_cliente"]; ?></td>  
                    <td width="110" valign="top"><?php print $row["TELEF2"]; ?></td>  
                    <td width="100" valign="top"><?php print $row["TELEF3"]; ?></td>                  

                    <td valign="top" width="150">$<?php print $row["deuda"]; ?></td>                                   
                </tr>
                <?php
                $contador++;
                $i++;
            }
        }
        ?>     
    </tbody>
</table>