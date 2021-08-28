<?php
header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=interes_mora.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
$formatter = \Yii::$app->formatter;
    
    
$obj_mov = new Movimiento();
$result = $obj_mov->obtener_movimiento_por_tipo(array('tipo_movimiento' => 7, 'fecha_desde' => $fecha_desde,
    'fecha_hasta' => $fecha_hasta,'numero_caja_desde'=>$numero_caja_desde,
    'numero_caja_hasta'=>$numero_caja_hasta)); //INTERES POR MORA  
?>
<h1>Listado de Intereses por Mora</h1>
<?php //echo "Numero de registros encontrados:" . mysql_num_rows($result);  ?>
<table style="border-collapse: collapse"  width="80%"  align="center" >
    <thead><tr> 
            <th width="100" ><b>FECHA</b></th>                
            <th width="100"><b>USUARIO</b></th>
            <th width="100"><b>MONTO</b></th>
            <th width="280"><b>OBSERVACIONES</b></th>                
        </tr>
    </thead>
    <tbody>            

        <?php
        $total = 0;
        $i = 0;
        while ($row = mysql_fetch_array($result)) {
            if (($i % 2) == 0) {
                $estilo_color = "#e4ecf7";
            } else {
                $estilo_color = "#FFF";
            }
            ?>
            <tr style="background-color:<?php echo $estilo_color ?>"> 
                <td  valign="top"  ><?php print date("d/m/Y",strtotime($row["fecha_movimiento"])); ?></td>                            
                <td  valign="top" ><?php print $row["usuario"]; ?></td>
                <td  valign="top"><?php print number_format($row["monto"], 2, ',', '.'); ?></td>  
                <td  valign="top"><?php print $row["observaciones"]; ?></td>  
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
            <td  valign="top" ><b>TOTAL</b></td>
            <td  valign="top"><?php print number_format($total, 2, ',', '.'); ?></td>  
            <td  valign="top"></td>  
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th style="">&nbsp;</th>
            <th style=""></th>
        </tr>
    </tfoot>
</table>
