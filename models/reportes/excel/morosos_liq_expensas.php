<?php
set_time_limit(0);

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");

header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=morosos.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

?>
<h1>Listado de Morosos</h1>

<?php //echo "Numero de registros encontrados:" . mysql_num_rows($result);  ?>
<table style="border-collapse: collapse"  width="80%"  align="center" >
    <thead><tr> 
            <th ><b>Direcci&oacute;n</b></th>                
            <th >Nombre</th>
            <th >Tipo</th>
            <th >Periodo</th>
            <th >Ultimo Contacto</th>
            <th >Importe</th>
        </tr>
    </thead>
    <tbody>            

        <?php        
        foreach($resultado as $row) {
            
            ?>
            <tr>
                <td ><?php print utf8_decode($row['NOMBRE']); ?></td>  
                <td ><?php print utf8_decode($row['direccion']); ?></td>  
                <td ><?php print $row['tipo']; ?></td>
                <td ><?php print $row['liq_mes']."/".$row['liq_anio']; ?></td>    
                <?php 
                $model=app\models\GestionCobranzas::find()->where(['operacion_id'=>$row['id_operacion']])->
                 orderBy(['fecha_notificacion' => SORT_DESC])->one();
                $fecha_ultimo="";
                if($model){
                    $fecha_ultimo=date('d/m/Y',strtotime($model->fecha_notificacion));
                }
                ?>
                <td ><?php print $fecha_ultimo; ?></td>
                <?php
                $deuda=0;
                if($row['tipo']=='ALQUILER'){
                    $dias_mora = app\models\A2Liquidaciones::obtener_dias_mora($row['id_liquidacion']);
                    $arreglo_monto = app\models\A2Liquidaciones::Calcular_Monto_Periodo($row['id_operacion'], $row['liq_anio'], 
                        $row['liq_mes'],$dias_mora);
                    $deuda=$arreglo_monto['monto'];
                }
                if($row['tipo']=='EXPENSAS'){
                    $deuda=$row['deuda'];
                }
                ?>
                <td style="text-align:right" ><?php print $deuda; ?></td>
            </tr>
            <?php       
                 
        }
        ?>     
    </tbody>
    <tfoot>
        <tr>
            <th style="">&nbsp;</th>
            <th style=""></th>
        </tr>
    </tfoot>
</table>
