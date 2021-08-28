
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
                <td width="500">
                    <H2>Informe de Expensas Cargadas</H2>                        
                    <H3>Expensas correspondientes al mes xx de </H3>
                </td>                
            </tr>            
        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td width="500">
                    <b>Administraci&oacute;n</b>                        
                    
                </td>                
                <td width="500">
                    <b>Inquilino</b>>
                </td>
            </tr>
        </tbody>
    </table>
    <hr/>
    <table width="100%" class="table table-bordered"  border="0" cellspacing="4" cellpadding="0" align="center" >            	
        <tr id="cabezera-tabla"> 
            <th width="100">INMUEBLE</th>
            <th width="100">CLIENTE</th>
            <th width="100">EXPENSA</th>
            <?php
            foreach($periodos as $periodo){
                $arreglo_periodo=explode('/',$periodo);
                echo "<th>".$arreglo_periodo[0]."/".$arreglo_periodo[1]."</th>";
            }
            ?>                              		                                                    
        </tr>
        <?php
        $query_periodo="";
        foreach($periodos as $periodo){
            $arreglo_periodo=explode('/',$periodo);
            if(!empty($query_periodo)){
                $query_periodo.=" OR ";
            }
            $query_periodo.="(mes=".$arreglo_periodo[0]." AND year=".$arreglo_periodo[1].")";
        }
        $total=0;
        $datos_arreglo=null;
        foreach($resultado as $row) {
           
            //echo "</tr>";
            //echo "<tr>";
            //echo "<td colspan='2' >";

            $query = "SELECT * FROM operaciones_expensas INNER JOIN tipo_expensas ON operaciones_expensas.tipo_expensas_id=tipo_expensas.id
            WHERE operacion_id=".$row['id_operacion_inmobiliaria']." AND (".$query_periodo.")
            ORDER BY descripcion"; 

            $connection = Yii::$app->getDb();
            $resultado_exp = $connection->createCommand($query)->queryAll();
            $datos_arreglo=NULL;
            if($resultado_exp){
                ?>
                <!--<table style="margin-left:50px">
                    <tr>
                        <th width="100">PERIODO</th>
                        <th width="100">EXPENSA</th>
                        <th width="100">IMPORTE</th>
                    </tr>-->
                    <?php 
                    foreach($resultado_exp as $row_exp){
                        $datos_arreglo[$row_exp['descripcion']][$row_exp['mes']."/".$row_exp['year']]=$row_exp['importe'];
                        ?>
                        <!--<tr>
                            <td><?php //echo $row_exp['mes']."/".$row_exp['year']; ?></td>
                            <td><?php //echo $row_exp['descripcion'] ?></td>
                            <td><?php //echo $formatter->asCurrency($row_exp['importe']) ?></td>
                        </tr>-->
                        <?php
                    }
                    $anterior="";
                    foreach($resultado_exp as $row_exp){
                        if($row_exp['descripcion']!=$anterior){
                            foreach($periodos as $periodo){
                                if($row_exp['descripcion']!=$anterior){
                                    if($anterior!=""){
                                        echo "</tr>";
                                    }
                                    $anterior=$row_exp['descripcion'];
                                    echo "<tr>";                
                                    echo "<td>" . utf8_decode($row['direccion']) . "</td>";
                                    echo "<td>" . utf8_decode($row['NOMBRE']) . "</td>";
                                    echo "<td>" . utf8_decode($anterior) . "</td>";
                                }

                                $arreglo_periodo=explode('/',$periodo);
                                echo "<td>";
                                try{
                                    echo  $datos_arreglo[$row_exp['descripcion']][$arreglo_periodo[0]."/".$arreglo_periodo[1]];
                                }catch(Exception $ex){
                                    echo "";
                                }
                                echo "</td>";
                            }
                        }
                    }
                    
                    echo "</tr>";
                    ?>                
                <?php

            }else{
                echo "<tr>";                
                echo "<td>" . utf8_decode($row['direccion']) . "</td>";
                echo "<td>" . utf8_decode($row['NOMBRE']) . "</td>";
                echo "</tr>";
            }/*else{
                echo "<i><b>NO EXISTE EXPENSAS PARA LOS PERIODOS SELECCIONADOS</b></i>";
            }*/
            //echo "</td>";
            
            //echo "<td>" . $row['mes'] . "/" . $row['year'] . "</td>";
            //echo "<td>" . strtoupper($row['estado']) . "</td>";
            //echo "<td style='text-align:right'><b> " . $formatter->asCurrency($row['importe']) . "</b></td>";                
            //echo "</tr>";
            //$total+=$row['importe'];
        }
        ?>            
    </table>		
</div>