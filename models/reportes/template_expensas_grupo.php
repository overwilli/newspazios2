
<?php
$formatter = \Yii::$app->formatter;
?>
<page>
    <page_footer>
        <table style="width: 100%;border:1px solid black ">
            <tr>
                <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y h:i:s') ?></td>
                <td style="text-align: right;    width: 50%"><b>Página [[page_cu]]/[[page_nb]]</b></td>
            </tr>
        </table>
    </page_footer>
    
    
    <h2>Informe de Expensas Por Consorcio</h2>                        
    <H4>Expensas correspondientes al mes <?php echo $periodo; ?> de <?php echo $grupo->descripcion?></h4>
               
    <table width="100%">
        
            <tr>
                <td width="400">
                    <div><b>Administraci&oacute;n</b></div>
                    <div>Nombre: Sp&aacute;zios Gesti&oacute;n Inmobiliaria (Marhe Luis Ricardo)</div>
                    <div>Domicilio: T&uacute;cuman N&deg; 189 - Santiago del Estero</div>
                    <div>Mail: spaziossgi@gmail.com Te: 0385-4226272</div>
                </td>                

                <td  width="300" style="vertical-align:top">
                    <div><b>Inquilino</b></div>
                </td>
                
            </tr>
       
    </table>
    <hr/>
    <b>ABONOS</b>
    <table width="100%">
        <tr>
            <th width="50%">CONCEPTO</th>
            <th width="10%">PERIODO</th>
            <th width="10%">IMPORTE</th>
        </tr>
        <?php
        $total=0;
        foreach ($resultado_abonos as $row) {
            echo "<tr>";
            echo "<td >".$row['descripcion']."</td>";
            echo "<td >".$row['mes']."/".$row['year']."</td>";
            echo "<td > " . $formatter->asCurrency($row['importe']) . "</td>";
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
    <table width="100%">
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
    <table width="100%">
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
    <table width="100%">
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
</page>