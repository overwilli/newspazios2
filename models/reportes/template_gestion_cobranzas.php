
<?php
$formatter = \Yii::$app->formatter;
?>
<page orientation="paysage" >
    <page_footer>
        <table style="width: 100%;border:1px solid black;margin-top:100px ">
            <tr>
                <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y h:i:s') ?></td>
                <td style="text-align: right;    width: 50%"><b>Página [[page_cu]]/[[page_nb]]</b></td>
            </tr>
        </table>
    </page_footer>    
		<table>
			<tbody>
				<tr>
					<td width="500">
						<H2>Informe de Gestión de Cobranzas</H2>                        
					</td>
					<td >                
					
					   <img src="http://<?php echo $_SERVER['SERVER_NAME'].Yii::$app->request->baseUrl?>/images/logo_spazio.png" width="200"/>
					</td>
				</tr>
			</tbody>
		</table>
		<hr/>
        <table border="0">            	
            <tr >
                <th width="120">CLIENTE</th>
                <th width="120">DIRECCION</th>
                <th width="100">LOCADOR</th>
                <th width="70">CONCEPTO</th>
                <th width="100">DESDE QUE PERIODO</th>  		                                                    
                <th width="100">TELEFONO</th>
                <th width="100">CEL.1</th>
                <!--<th width="80">CEL.2</th>-->
                <th width="90">IMPORTE</th>
                <th width="50">ULTIMA GESTION</th>
                <th width="130" >OBSERVACIONES</th>
            </tr>
            <?php            
            foreach($resultado as $row) {
                echo "<tr style='background-color:#f9f9f9'>";     
                echo "<td width='120'>" . $row['NOMBRE'] . "</td>";           
                echo "<td width='120'>" . $row['direccion'] . "</td>";
                echo "<td width='120'>" . $row['NOMBRE'] . "</td>";
                echo "<td width='80'>" . $row['concepto'] . "</td>";
                $reg_cobranzas=\app\models\GestionCobranzas::find()->Where(['operacion_id'=> $row['id_operacion_inmobiliaria']])->orderBy('fecha_notificacion DESC')->one();
                if($row['concepto']=="ALQUILER"){
                    $reg_liq=\app\models\A2Liquidaciones::find()->Where(['id_operacion'=> $row['id_operacion_inmobiliaria'],
                    'fecha_pago'=>'0000-00-00 00:00:00'])->orderBy('liq_anio,liq_mes')->one();
                    echo "<td width='100'>".$reg_liq->liq_mes."/".$reg_liq->liq_anio."</td>";
                }else{
                    $reg_ope_exp=\app\models\OperacionesExpensas::find()->Where(['operacion_id'=> $row['id_operacion_inmobiliaria'],
                    'estado'=>'impago'])->orderBy('year,mes')->one();
                    echo "<td width='100'>".$reg_ope_exp->mes."/".$reg_ope_exp->year."</td>";
                }
                
                
                echo "<td width='100' style='word-wrap: break-word;'>" . $row['telefono_cliente'] . "</td>";
                echo "<td width='100' style='word-wrap: break-word;'>" . $row['TELEF2'] . "</td>";
                //echo "<td width='80' style='word-wrap: break-word;'>" . $row['TELEF3'] . "</td>";

                if($row['concepto']=="ALQUILER"){
                    if($reg_liq){
                        $dias_mora = \app\models\A2Liquidaciones::obtener_dias_mora($reg_liq->id_liquidacion);
                        $arreglo_monto = \app\models\A2Liquidaciones::Calcular_Monto_Periodo($reg_liq->id_operacion, $reg_liq->liq_anio, 
                            $reg_liq->liq_mes, $dias_mora);
                        echo "<td width='90' style='text-align: right;'>".$formatter->asCurrency($arreglo_monto['monto'])."</td>";
                    }else{
                                                    
                        echo "<td width='90'></td>";
                    }
                }else{
                    $model_liq=\app\models\A2Liquidaciones::find()->where(['id_operacion'=>$row['id_operacion_inmobiliaria'],
                    'liq_anio'=>$reg_ope_exp->year,'liq_mes'=>$reg_ope_exp->mes])->one();
                    $dias_atraso = \app\models\A2Liquidaciones::obtener_dias_mora($model_liq->id_liquidacion,"",TRUE);
                    $interes_por_dia = $row['interes_dia_mora'];
                    $interes_por_mora = (($dias_atraso * $interes_por_dia) * $reg_ope_exp->importe) / 100;
                    echo "<td width='90' style='text-align: right;'>".$formatter->asCurrency($reg_ope_exp->importe+$interes_por_mora)."</td>";                    
                }
                if($reg_cobranzas){
                    echo "<td>" . $reg_cobranzas->fecha_notificacion . "</td>";                
                    echo "<td width='100'>" . $reg_cobranzas->observaciones . "</td>";
                }else{
                    echo "<td></td>";
                    echo "<td width='130'></td>";
                }
                echo "</tr>";                                
            }
            ?>            
        </table>		
    
</page>