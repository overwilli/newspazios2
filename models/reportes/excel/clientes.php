<?php
set_time_limit(0);

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=clientes.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

$cadena = "SELECT a2_clientes.*,a2_noticias.titulo  FROM a2_clientes LEFT JOIN 
        a2_operaciones_inmobiliarias ON a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        LEFT JOIN a2_noticias ON a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad ORDER BY a2_clientes.nombre ASC";

$result = mysql_query($cadena);
if (mysql_errno() > 0) {
    print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
    die();
}
?>
<h1>Listado de Clientes</h1>
<?php echo "Numero de registros encontrados:" . mysql_num_rows($result); ?>
<table style="border-collapse: collapse"  width="80%"  align="center" >
    <thead><tr> 
            <th height="36"><b>NOMBRE</b></th>
            <th><b>DNI</b></th>
            <th><b>TELEFONO</b></th>
            <th><b>TELEFONO 2</b></th>
            <th><b>TELEFONO 3</b></th>
            <th><b>DIRECCION</b></th>
            <th width="200"><b>PROPIEDAD</b></th>
        </tr>
    </thead>
    <tbody>
        <tr> 
            <td colspan="4">

            </td>
        </tr>

        <?php
        $estilo = "filapar";
        $i = 0;
        while ($row = mysql_fetch_array($result)) {
            if (($i % 2) == 0) {
                $estilo_color = "#e4ecf7";
            } else {
                $estilo_color = "#FFF";
            }
            ?>
            <tr style="background-color:<?php echo $estilo_color ?>"> 
                <td valign="top" ><?php print $row["NOMBRE"]; ?></td>
                <td valign="top"><?php print $row["DNI"]; ?></td>
                <td valign="top"><?php print $row["TELEFONO"]; ?></td>
                <td width="100" valign="top"><?php print $row["TELEF2"]; ?></td>
                <td width="100" valign="top"><?php print $row["TELEF3"]; ?></td>
                <td valign="top"><?php print $row["DIRECCION"]; ?></td>
                <td width="200" valign="top"><?php print $row["titulo"]; ?></td>
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





