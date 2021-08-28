<?php
$formatter = \Yii::$app->formatter;
$model_liq=\app\models\A2Liquidaciones::find()->where(['id_operacion'=>$operacion_id,'liq_anio'=>$arreglo_periodo[1],
    'liq_mes'=>$arreglo_periodo[0]])->one();

$model_operacion = \app\models\A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' => $operacion_id])->one();	

$dias_atraso = \app\models\A2Liquidaciones::obtener_dias_mora($model_liq->id_liquidacion,"",TRUE);

$interes_por_dia = $model_operacion->interes_dia_mora;


?>
    <div class="col-xs-12 col-md-12">
        <input type="hidden" id="operacion_id" name="operacion_id" value="<?php echo $operacion_id ?>" />
        <input type="hidden" id="anio" name="anio" value="<?php echo $arreglo_periodo[1] ?>" />
        <input type="hidden" id="mes" name="mes" value="<?php echo $arreglo_periodo[0] ?>" />
        <table class="table table-bordered"  border="0" cellspacing="4" cellpadding="0" align="center" >            	
            <tr id="cabezera-tabla"> 
                <th >EXPENSA</th>	  
                <th >PERIODO</th>
                <th >ESTADO</th>
                <th >IMPORTE</th>		                                    
            </tr>
            <?php
            $total=0;
            foreach ($model as $row) {
                echo "<tr>";
                echo "<td>" . $row['descripcion'] . "</td>";
                echo "<td>" . $row['mes'] . "/" . $row['year'] . "</td>";
                echo "<td>" . strtoupper($row['estado']) . "</td>";
                echo "<td><b> " . $formatter->asCurrency($row['importe']) . "</b></td>";
                $row['tipo_expensas_id'] . ')"><img src="imagenes/borrar.png" alt="Registrar pago" title="Borrar pago" border="0" width="11"></a></td>';
                echo "</tr>";
                $total+=$row['importe'];
            }
            ?>
            <tr>
                <td></td>
                <td><input type="hidden" id="monto" name="monto" value="<?php echo $total ?>" /></td>
                <td style="text-align: right"><b>Total :</b></td>
                <td><b><label id="label_total_sin_interes"><?php echo $formatter->asCurrency($total); ?></label></b></td>
            </tr>
			<tr>
                <td></td>
                <td></td>
                <td style="text-align: right"><b>Interes:</b></td>
                <td><b>
				<label id="label_interes">
				<?php
				$interes_por_mora = (($dias_atraso * $interes_por_dia) * $total) / 100;
				echo $formatter->asCurrency($interes_por_mora);
				echo "({$dias_atraso} días)";?></label>
                </b>
                <?php
                $bonificado_interes_por_mora=0;
                $bonificado_total=0;
                if(!$model_operacion->tiene_expensas){
                    $bonificado_interes_por_mora=$interes_por_mora;
                    $bonificado_total=$total;
                    ?>
                    <input type="hidden" id="bonificado" name="bonificado" value="<?php echo $bonificado_total+$bonificado_interes_por_mora;?>" />
                    <?php 
                }
                ?>
				<input type="hidden" id="monto_interes" name="monto_interes" value="<?php echo $interes_por_mora-$bonificado_interes_por_mora ?>"/>
				<input type="hidden" id="monto_total" name="monto_total" value="<?php echo ($total+$interes_por_mora)-($bonificado_total+$bonificado_interes_por_mora) ?>"/>
				</td>
            </tr>
            <?php
            $bonificado=0;            
            if(!$model_operacion->tiene_expensas){ 
                $bonificado=$total+$interes_por_mora;
                ?>
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: right"><b>Bonificado:</b></td>
                <td><b><label id="label_bonificado"><?php echo $formatter->asCurrency($bonificado);	?></labe>
				</b></td>
            </tr>
            <?php } ?>
			<tr>
                <td></td>
                <td></td>
                <td style="text-align: right"><b>Total a pagar:</b></td>
                <td><b><label id="label_monto_total"><?php echo $formatter->asCurrency(($total+$interes_por_mora)-$bonificado);	?></label>
				</b></td>
            </tr>
        </table>        
    </div>
