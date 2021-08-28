
<page orientation="paysage" >
    <page_footer>
        <table style="width: 100%;border:1px solid black ">
            <tr>
                <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y h:i:s')      ?></td>
                <td style="text-align: right;    width: 50%"><b>Página [[page_cu]]/[[page_nb]]</b></td>
            </tr>
        </table>
    </page_footer>

    <?php
    
    $cadena = "SELECT * FROM a2_noticias ORDER BY a2_noticias.direccion ASC";

    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
        die();
    }
    ?>
    <h1>Listado de Padrones</h1>
    <?php echo "Numero de registros encontrados:" . mysql_num_rows($result); ?>
    <table style="border-collapse: collapse"  width="80%"  align="center" >
        <thead><tr> 
                <th><b>Direcci&oacute;n</b></th>
                <th><b>Imp. Inmobiliario</b></th>
                <th><b>Aguas de Santiago</b></th>
                <th>Impuesto Municipal</th>
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
                    <td valign="top"><?php print $row["padroniibb"]; ?></td>
                    <td valign="top"><?php print $row["padronaguas"]; ?></td>
                    <td width="200" valign="top"><?php print $row["padronmunicipal"]; ?></td>
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

</page>

