<?php
$formatter = \Yii::$app->formatter;
?>
<table class="table" border="0" cellspacing="4" cellpadding="0" align="center" >
    <tr id="cabezera-tabla"> 
        <td height="36">MES</td>
		<td height="36">A&Ntilde;O</td>
		<td height="36">PROPIEDAD</td>
		<td height="36">CLIENTE</td>
        <td height="36">IMPORTE</td>                                         		                	 
        
        <?php if ($model->estado == 'Nuevo') { ?>
            <td height="36">ACCION</td>
        <?php }
        ?>
    </tr>
    <?php
    $expensas_total = 0;
    

    foreach ($model_detalle_expensas as $row) {
        
        $expensas_total+=$row->importe;        
       

        
		$model_detalle_expensa = \app\models\OperacionesExpensas::find()->where(['id' => $row->expensa_id])->one();
        echo "<tr>";
		echo "<td>" . $model_detalle_expensa->mes . "</td>";
		echo "<td>" . $model_detalle_expensa->year . "</td>";        
        echo "<td>" . $model_detalle_expensa->inmueble->direccion . "</td>";
		$cliente="";
		if($model_detalle_expensa->contrato){
			if($model_detalle_expensa->contrato->cliente){
				$cliente=$model_detalle_expensa->contrato->cliente->NOMBRE;
			}
		}
		echo "<td>" . $cliente . "</td>";
        echo "<td>" . $formatter->asCurrency($row->importe) . "</td>";        
		
        if ($model->estado == 'Nuevo') {
            echo "<td><a class='glyphicon glyphicon-trash' onclick='eliminar_item_expensas(" . $row['id'] . ")'>" . '' . "</a></td>";
        }
        echo "</tr>";
    }
    ?>
    <tr>
        <td></td>
        <td></td>
		<td></td>
        <td><b>Subtotales:</b></td>
        <td><b>$<?php echo number_format($expensas_total, 2, ',', '.'); ?></b></td>               
    </tr>
</table>
<input type="hidden" id="field_total_expensas" value="<?php echo number_format($expensas_total, 2, '.', '') ?>" />

 

