
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
    <div class="col-xs-12 col-md-12">
		<table>
			<tbody>
				<tr>
					<td width="500">
						<H2>Expensas consorcio</H2>
                        <?php
                        $grupo= \app\models\A2Grupos::find()->where(['id_grupo'=>$consorcio])->one();
                        echo $grupo->descripcion;
                        ?>
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
                <th width="150">INMUEBLE</th>
                <th width="150">CLIENTE</th>
                <th width="100" >EXPENSA</th>	  
                <th width="100">PERIODO</th>
                <th width="100">ESTADO</th>
                <th width="100">IMPORTE</th>		                                                    
            </tr>
            <?php
            $total=0;
            foreach($resultado as $row) {
                echo "<tr>";                
                echo "<td>" . $row['direccion'] . "</td>";
                echo "<td>" . $row['NOMBRE'] . "</td>";
                echo "<td>" . $row['descripcion'] . "</td>";
                echo "<td>" . $row['mes'] . "/" . $row['year'] . "</td>";
                echo "<td>" . strtoupper($row['estado']) . "</td>";
                echo "<td style='text-align:right'><b> " . $formatter->asCurrency($row['importe']) . "</b></td>";                
                echo "</tr>";
                $total+=$row['importe'];
            }
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><input type="hidden" id="monto" name="monto" value="<?php echo $total ?>" /></td>
                <td style="text-align: right"><b>Total a pagar:</b></td>
                <td style='text-align:right'><b><?php echo $formatter->asCurrency($total); ?></b></td>
            </tr>
        </table>        		
    </div>
</page>