<?php
$formatter = \Yii::$app->formatter;
?>
<table class="table" border="0" cellspacing="4" cellpadding="0" align="center" >
    <tr id="cabezera-tabla"> 
        <td height="36">FECHA DE GASTO</td>
		<td height="36">PROPIEDAD</td>
		<td height="36">CLIENTE</td>
        <td height="36">IMPORTE</td>        
        <td height="36">OBSERVACION</td>	                         		                	 
        
        <?php if ($model->estado == 'Nuevo') { ?>
            <td height="36">ACCION</td>
        <?php }
        ?>
    </tr>
    <?php
    $gasto_total = 0;
    

    foreach ($model_detalle_gastos as $row) {
        //($row = mysql_fetch_array($resultado)) {
        
                
         

        //$model_detalle_gastos->inmueble->direccion .
        //$model_detalle_gastos->operacionInmobiliaria->cliente->NOMBRE
        $model_mov = \app\models\A2Movimientos::find()->where(['id_movimiento' => $row['movimientos_id']])->one();
        if($model_mov->movimiento_tipo==8){
            $gasto_total-=$row->importe;
        }else{
            $gasto_total+=$row->importe;
        }
        $model_operacion=\app\models\A2OperacionesInmobiliarias::find()->where(['id_operacion_inmobiliaria' =>  $model_mov->operacion_id])->one();
        if($model_operacion){
            
        echo "<tr>";
        echo "<td>" . date('d/m/Y',strtotime($model_mov->fecha_movimiento)) . "</td>";
        echo "<td>" . $model_operacion->inmueble->direccion. "</td>";
        
        echo "<td>" . $model_operacion->cliente->NOMBRE. "</td>";
        
        echo "<td>" . $formatter->asCurrency($row['importe']) . "</td>";   
        
        echo "<td>" . (($model_mov->movimiento_tipo==8)? "Ingreso:":"Egreso:".$model_mov->observaciones) . "</td>";
        
        if ($model->estado == 'Nuevo') {
            echo "<td><a href='#' class='glyphicon glyphicon-trash' onclick='eliminar_item_gasto(" . $row['id'] . ")'>" . '' . "</a></td>";
        }
        
        echo "</tr>";
        }
    }
    ?>
    <tr>
        <td></td>
        <td></td>
        <td><b>Subtotales:</b></td>
        <td><b>$<?php echo number_format($gasto_total, 2, ',', '.'); ?></b></td>               
    </tr>
</table>
<input type="hidden" id="field_total_gastos" value="<?php echo number_format($gasto_total, 2, '.', '') ?>" />


 

