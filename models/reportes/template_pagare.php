<style>
page{
    padding:100px
}
</style>
<?php
require_once("Enletras.php");
$cantidad=1;
$bandera_sin_cerrar=false;

foreach($resultado as $row){
    if($cantidad==1){
        echo "<page style='text-align:justify;padding-left:100px'>";
    }
    
    //print_r($row);echo "<br/>";
    $V = new EnLetras();
    //echo "<b> Son pesos " . $V->ValorEnLetras(INTVAL($total_calculado), "");

    $fecha=new Datetime($model->desde_anio."-".$model->desde_mes."-".$model->dia_venc_mensual);
    $fecha->add(new DateInterval('P15D'));
    echo "<br/><div style='width:100%;text-align:right'>$".$row['D']."</div><br/>";
    echo "<div style='width:100%;text-align:right'> Santiago del Estero, ".$fecha->format('d/m/Y')."</div><br/><br/>";  
    echo "El día ".$model->dia_venc_mensual."/".$row['mes']."/".$row['anio']." PAGARE sin protesto 
    (Art. 50 de ley 5965/63) a ".(($model->locadorPropietario)?$model->locadorPropietario->apellido.",".
    $model->locadorPropietario->nombre:"")." o 
    a su orden, la cantidad de PESOS ".$V->ValorEnLetras(INTVAL($row['D']), "")." (".$row['D'].") por 
    igual valor recibido a mi entera satisfacción, en ______________________________________en mi carácter de 
    librador dejo constancia que de conformidad con lo establecido en el art. 36 del D.L. 5965/63, extendido el 
    plazo del presente documento hasta 5 años de la fecha de emisión. El presente documento devengará un interés 
    compensatorio del 36 % anual desde la fecha de emisión y un interés punitorio equivalente al 36 % del 
    compensatorio pactado que se adicionará al mismo desde su mora hasta el efectivo pago.<br/><br/>";

    echo "<table>";
    echo "<tr>";
    echo "<td>Apellido y Nombre:</td>";
    echo "<td style='width:300px;height:30px'>".$model->cliente->NOMBRE."</td>";
    echo "<td>Firma:</td>";
    echo "<td>__________________________</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>L.E/L.C/D.N.I N°:</td>";
    echo "<td style='width:300px;height:30px'>".$model->cliente->DNI."</td>";
    echo "<td>Aclaración:</td>";
    echo "<td>__________________________</td>";
    echo "</tr>";
    echo "</table>";

    echo "<table>";
    echo "<tr>";
    echo "<td>Apellido y Nombre:</td>";
    echo "<td style='width:300px;height:30px'>".(($model->inquilino2)?$model->inquilino2->NOMBRE:"")."</td>";
    echo "<td>Firma:</td>";
    echo "<td>__________________________</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>L.E/L.C/D.N.I N°:</td>";
    echo "<td style='width:300px;height:30px'>".(($model->inquilino2)?$model->inquilino2->DNI:"")."</td>";
    echo "<td>Aclaración:</td>";
    echo "<td>__________________________</td>";
    echo "</tr>";
    echo "</table><br/><br/>";

 	
    echo "--------------------------------------------------------------------------------------------------
    ------------------------------------------------------";

    /*echo "<div style='width:100%;text-align:right'>$".$row['D']."</div><br/>";
    echo "<div style='width:100%;text-align:right'> Santiago del Estero, ".$fecha->format('d/m/Y')."</div><br/><br/>";  
    echo "El día ".$model->dia_venc_mensual."/".$row['mes']."/".$row['anio']." PAGARE sin protesto 
    (Art. 50 de ley 5965/63) a ".(($model->locadorPropietario)?$model->locadorPropietario->apellido.",".$model->locadorPropietario->nombre:"")." o 
    a su orden, la cantidad de PESOS ".$V->ValorEnLetras(INTVAL($row['D']), "")." (".$row['D'].") por 
    igual valor recibido a mi entera satisfacción, en ______________________________________en mi carácter de 
    librador dejo constancia que de conformidad con lo establecido en el art. 36 del D.L. 5965/63, extendido el 
    plazo del presente documento hasta 5 años de la fecha de emisión. El presente documento devengará un interés 
    compensatorio del 36 % anual desde la fecha de emisión y un interés punitorio equivalente al 36 % del 
    compensatorio pactado que se adicionará al mismo desde su mora hasta el efectivo pago.<br/><br/>";

    echo "<table>";
    echo "<tr>";
    echo "<td>Apellido y Nombre:</td>";
    echo "<td style='width:300px;height:30px'>".$model->cliente->NOMBRE."</td>";
    echo "<td>Firma:</td>";
    echo "<td>__________________________</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>L.E/L.C/D.N.I N°:</td>";
    echo "<td style='width:300px;height:30px'>".$model->cliente->DNI."</td>";
    echo "<td>Aclaración:</td>";
    echo "<td>__________________________</td>";
    echo "</tr>";
    echo "</table>";

    echo "<table>";
    echo "<tr>";
    echo "<td>Apellido y Nombre:</td>";
    echo "<td style='width:300px;height:30px'>".(($model->inquilino2)?$model->inquilino2->NOMBRE:"")."</td>";
    echo "<td>Firma:</td>";
    echo "<td>__________________________</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>L.E/L.C/D.N.I N°:</td>";
    echo "<td style='width:300px;height:30px'>".(($model->inquilino2)?$model->inquilino2->DNI:"")."</td>";
    echo "<td>Aclaración:</td>";
    echo "<td>__________________________</td>";
    echo "</tr>";
    echo "</table><br/><br/>";*/
    if($cantidad==2){
        $cantidad=1;
        $bandera_sin_cerrar=false;
        echo "</page>";
    }else{
        $bandera_sin_cerrar=true;
        $cantidad++;
    }
    
}
if($bandera_sin_cerrar){
    echo "</page>";
}
?>