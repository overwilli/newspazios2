
<?php
$formatter = \Yii::$app->formatter;
?>
<page>
    <page_footer>
        <table style="width: 100%;border:1px solid black;margin-top:100px ">
            <tr>
                <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y h:i:s') ?></td>
                <td style="text-align: right;    width: 50%"><b>Página [[page_cu]]/[[page_nb]]</b></td>
            </tr>
        </table>
    </page_footer>
    <div class="col-xs-12 col-md-12">
		<table>
			<tbody>
				<tr>
					<td width="500">
						<H2>Expensas Cargadas</H2>                        
					</td>
					<td >                
					
					   <img src="http://<?php echo $_SERVER['SERVER_NAME'].Yii::$app->request->baseUrl?>/images/logo_spazio.png" width="200"/>
					</td>
				</tr>
			</tbody>
		</table>
		<hr/>
        <table width="100%" class="table table-bordered"  border="0" cellspacing="4" cellpadding="0" align="center" >            	
            <tr id="cabezera-tabla"> 
                <th width="100">INMUEBLE</th>
                <th width="100">CLIENTE</th>                               		                                                    
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
            foreach($resultado as $row) {
                echo "<tr style='background-color:#f9f9f9'>";                
                echo "<td>" . $row['direccion'] . "</td>";
                echo "<td>" . $row['NOMBRE'] . "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td colspan='2' >";

                $query = "SELECT * FROM operaciones_expensas INNER JOIN tipo_expensas ON operaciones_expensas.tipo_expensas_id=tipo_expensas.id
                WHERE operacion_id=".$row['id_operacion_inmobiliaria']." AND (".$query_periodo.")
                ORDER BY year,mes,descripcion"; 
    
                $connection = Yii::$app->getDb();
                $resultado_exp = $connection->createCommand($query)->queryAll();
                if($resultado_exp){
                    ?>
                    <table style="margin-left:50px">
                        <tr>
                            <th width="100">PERIODO</th>
                            <th width="100">EXPENSA</th>
                            <th width="100">IMPORTE</th>
                        </tr>
                        <?php 
                        foreach($resultado_exp as $row_exp){
                            ?>
                            <tr>
                                <td><?php echo $row_exp['mes']."/".$row_exp['year']; ?></td>
                                <td><?php echo $row_exp['descripcion'] ?></td>
                                <td><?php echo $formatter->asCurrency($row_exp['importe']) ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <?php
                }else{
                    echo "<i><b>NO EXISTE EXPENSAS PARA LOS PERIODOS SELECCIONADOS</b></i>";
                }
                echo "</td>";
                echo "</tr>";
                //echo "<td>" . $row['mes'] . "/" . $row['year'] . "</td>";
                //echo "<td>" . strtoupper($row['estado']) . "</td>";
                //echo "<td style='text-align:right'><b> " . $formatter->asCurrency($row['importe']) . "</b></td>";                
                //echo "</tr>";
                //$total+=$row['importe'];
            }
            ?>            
        </table>		
    </div>
</page>