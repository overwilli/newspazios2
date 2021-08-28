
<?php
header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=liquidacion_expensas_grupo.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
$formatter = \Yii::$app->formatter;
?>

<div class="col-xs-12 col-md-12">
    <table>
        <tbody>
            <tr>
                <td colspan="3" width="500">
                    <H2>Informe de Expensas Por Consorcio</H2>                        
                    <H3>Expensas correspondientes al mes <?php echo $periodo; ?> de <?php echo $grupo->descripcion?></H3>
                </td>                
            </tr>            
        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td colspan="4" width="500">
                    <div><b>Administraci&oacute;n</b></div>
                    <div>Nombre: Sp&aacute;zios Gesti&oacute;n Inmobiliaria (Marhe Luis Ricardo)</div>
                    <div>Domicilio: T&uacute;cuman N&deg; 189 - Santiago del Estero</div>
                    <div>Mail: spaziossgi@gmail.com Te: 0385-4226272</div>
                </td>                
                <td colspan="2" width="500">
                    <b>Inquilino</b>
                </td>
            </tr>
        </tbody>
    </table>
    <hr/>
    <b>ABONOS</b>
    <table>
        <tr>
            <th>CONCEPTO</th>
            <th>PERIODO</th>
            <th>IMPORTE</th>
        </tr>
        <?php
        $total=0;
        foreach ($resultado_abonos as $row) {
            echo "<tr>";
            echo "<td>".$row['descripcion']."</td>";
            echo "<td>".$row['mes']."/".$row['year']."</td>";
            echo "<td> " . $formatter->asCurrency($row['importe']) . "</td>";
            echo "</tr>";
            $total+=$row['importe'];
        }
        ?>
        <tr>
            <td></td>
            <td style="text-align:right; font-weight:bold;">Total</td>
            <td><?php echo $formatter->asCurrency($total);?></td>
        </tr>
    </table><br/><br/>
    <b>SERVICIOS PUBLICOS E IMPUESTOS</b>
    <table>
        <tr>
            <th>CONCEPTO</th>
            <th>PERIODO</th>
            <th>IMPORTE</th>
        </tr>
        <?php 
        $total=0;
        foreach ($resultado_impuestos as $row) {
            echo "<tr>";
            echo "<td>".$row['descripcion']."-".utf8_decode($row['direccion'])."</td>";
            echo "<td>".$row['mes']."/".$row['year']."</td>";
            echo "<td> " . $formatter->asCurrency($row['importe']) . "</td>";
            echo "</tr>";
            $total+=$row['importe'];
        }
        ?>
        <tr>
            <td></td>
            <td style="text-align:right; font-weight:bold;">Total</td>
            <td><?php echo $formatter->asCurrency($total);?></td>
        </tr>
    </table><br/><br/>
    <b>GASTOS VARIOS</b>
    <table>
        <tr>
            <th>CONCEPTO</th>
            <th>PERIODO</th>
            <th>IMPORTE</th>
        </tr>
        <?php 
        $total=0;
        foreach ($resultado_gastos as $row) {
            echo "<tr>";
            echo "<td>".$row['descripcion']."</td>";
            echo "<td>".$row['mes']."/".$row['year']."</td>";
            echo "<td> " . $formatter->asCurrency($row['importe']) . "</td>";
            echo "</tr>";
            $total+=$row['importe'];
        }
        ?>
        <tr>
            <td></td>
            <td style="text-align:right; font-weight:bold;">Total</td>
            <td><?php echo $formatter->asCurrency($total);?></td>
        </tr>
    </table><br/><br/>
    <b>ADMINISTRACION</b>
    <table>
        <tr>
            <th>CONCEPTO</th>
            <th>PERIODO</th>
            <th>IMPORTE</th>
        </tr>
        <?php 
        foreach ($resultado_honorarios as $row) {
            echo "<tr>";
            echo "<td>".$row['descripcion']."</td>";
            echo "<td>".$row['mes']."/".$row['year']."</td>";
            echo "<td> " . $formatter->asCurrency($row['importe']) . "</td>";
            echo "</tr>";
            $total+=$row['importe'];
        }
        ?>
        <tr>
            <td></td>
            <td style="text-align:right; font-weight:bold;">Total</td>
            <td><?php echo $formatter->asCurrency($total);?></td>
        </tr>
    </table> 	
</div>