<?php

use app\models\Utils;

set_time_limit(0);

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=planilla_periodos.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);


?>
<table>
    <tr>
        
        <td colspan="4" style="text-align:center"><h1><b><?php echo utf8_decode($model->inmueble->direccion);?></b></h1></td>
        <td><?php echo $model->fecha_ope;?></td>
    </tr>
</table>
<table style="border:1px solid black">
    <tr>
        <td><b>CONTRATO</b></td>
        <td colspan="2"><?php echo $model->tipo_contrato;?></td>
        <td><b>VIGENCIA</b></td>
        <td colspan="2"><?php echo $cantidad_meses;?></td>
    </tr>
    <tr>
        <td><b>DEP.GARANTIA</b></td>
        <td colspan="2"><?php echo $model->deposito_monto;?></td>
        <td></td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td><b>INMOBILIARIA</b></td>
        <td colspan="2"><?php echo utf8_decode($model->inmobiliaria->nombre_inmobiliaria);?></td>
        <td><b>HONORARIOS</b></td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td><b>LOCADOR</b></td>
        <td colspan="2"><?php echo utf8_decode(($model->locadorPropietario)?$model->locadorPropietario->apellido.",".
            $model->locadorPropietario->nombre:"");?></td>
        <td><b>DNI</b></td>
        <td colspan="2"><?php echo ($model->locadorPropietario)?$model->locadorPropietario->cuit:""?></td>
    </tr>
</table>
<br/><br/>
<table style="border:1px solid black">
    <tr>
        <td><b>Titular</b></td>
        <td colspan="2"><?php echo utf8_decode($model->cliente->NOMBRE);?></td>
        <td><b>DNI</b></td>
        <td colspan="2"><?php echo $model->cliente->DNI;?></td>
    </tr>
    <tr>
        <td><b>Domicilio</b></td>
        <td colspan="2"><?php echo utf8_decode($model->cliente->DIRECCION);?></td>
        <td><b>Telefono</b></td>
        <td colspan="2"><?php echo $model->cliente->TELEFONO;?></td>
    </tr>
    <tr>
        <td><b>CoTitular</b></td>
        <td colspan="2"><?php echo utf8_decode(($model->inquilino2)?$model->inquilino2->NOMBRE:"");?></td>
        <td><b>DNI</b></td>
        <td colspan="2"><?php echo ($model->inquilino2)?$model->inquilino2->DNI:"";?></td>
    </tr>
    <tr>
        <td><b>Domicilio</b></td>
        <td colspan="2"><?php echo utf8_decode(($model->inquilino2)?$model->inquilino2->DIRECCION:"");?></td>
        <td><b>Telefono</b></td>
        <td colspan="2"><?php echo ($model->inquilino2)?$model->inquilino2->TELEFONO:"";?></td>
    </tr>
    <tr>
        <td><b>Garante</b></td>
        <td colspan="2"><?php echo utf8_decode(($model->cod_garante)?$model->garante->NOMBRE:"");?></td>
        <td><b>DNI</b></td>
        <td colspan="2"><?php echo ($model->cod_garante)?$model->garante->DNI:"";?></td>
    </tr>
    <tr>
        <td><b>Domicilio</b></td>
        <td colspan="2"><?php echo utf8_decode(($model->cod_garante)?$model->garante->DIRECCION:"");?></td>
        <td><b>Telefono</b></td>
        <td colspan="2"><?php echo ($model->cod_garante)?$model->garante->TELEFONO:"";?></td>
    </tr>
</table>
<br/><br/>

<table style="border:1px solid black"> 
<tr>
    <th>N&deg; Mes</th>
    <th colspan="3">Fecha de Cobro</th>
    <th>Contrato</th>
    <th>Iva</th>
    <th>Otros</th>
    <th>Total</th>
</tr>
<?php
$i=1;
foreach($resultado as $row){
    ?>
    <tr>
        <td><?php echo $i;?></td>
        <td><?php echo $model->dia_venc_mensual;?></td>
        <td style="text-align: center;"><?php
        $obj_u=new Utils;
        echo $obj_u->mes_en_letras($row['anio']."-".$row['mes']."-".$model->dia_venc_mensual);?></td>
        <td style="text-align: center;"><?php echo $row['anio'];?></td>
        <td>
        <?php
        echo $row['A'] + $row['B'] + $row['X'];
        ?>
        </td>
        <td>
        <?php
        $iva=0;
        if ($row['excento']==1) {
            $iva=$row['B'];
        } else {
            $iva=$row['A']*0.21+$row['B']*0.21;
        }
        echo $iva;
        ?>
        </td>
        <td><?php echo $row['D']+ $row['C'];?></td>
        <td>
        <?php
        echo $row['A'] + $row['B'] + $row['X'] + $row['D'] + $row['C'];
        ?>
        </td>
    </tr>
    <?php
    $i++;
}
?>
</table>