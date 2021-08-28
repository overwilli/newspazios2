<?php
set_time_limit(0);

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=informe_grupos.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);


?>
<h1>Informe de Grupos</h1>
<?php 
$model=\app\models\A2Grupos::find()->orderBy(['descripcion' => SORT_ASC])->all();
?>
<table >
    <thead><tr> 
            <th><b>GRUPO</b></th>
            <th><b>INMUEBLE</b></th>
            <th><b>PORCION EXPENSAS</b></th>
            
        </tr>
    </thead>
    <tbody>
        

        <?php
        
        $i = 0;
        foreach($model as $fila) {
            $model_inmueble=\app\models\A2Noticias::find()->where(['id_grupo'=>$fila->id_grupo])->
                orderBy(['direccion' => SORT_ASC])->all();
            $grupo=$fila->descripcion;  
            $total=0;          
            foreach($model_inmueble as $row){
                
                ?>
                <tr> 
                    <td ><?php print utf8_decode($grupo); ?></td>
                    <td ><?php print utf8_decode($row->direccion); ?></td>
                    <td ><?php print $row->porcion; ?></td>                    
                </tr>
                <?php
                $grupo="";
                $total+=$row->porcion;
            }?>
            <tr> 
                <td ></td>
                <td style="text-align:right;font-weight:bold">Total:</td>
                <td ><?php print $total; ?></td>                    
            </tr>
            <?php
        }
        ?>     
    </tbody>
</table>





