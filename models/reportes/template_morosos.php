
<page orientation="paysage" >
    <page_footer>
        <table style="width: 100%;border:1px solid black ">
            <tr>
                <td style="text-align: left;    width: 50%"><?php echo date('d/m/Y h:i:s') ?></td>
                <td style="text-align: right;    width: 50%"><b>Página [[page_cu]]/[[page_nb]]</b></td>
            </tr>
        </table>
    </page_footer>
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
                <td style="text-align:center"><?php print $fecha_ultimo; ?></td>
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
                <td style="text-align:right" ><?php print \Yii::$app->formatter->asCurrency($deuda); ?></td>
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
</page>

