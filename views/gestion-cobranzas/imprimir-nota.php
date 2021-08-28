<?php
?>
<h3>Gestión de Cobranza - Ultimo Contacto</h3>
Impreso el día <?php echo date("d/m/Y")?><br/><br/>
<table>
    <tr>
        <td>Cliente</td>
        <td><?php echo $model->cliente->NOMBRE;?></td>
    </tr>
    <tr>
        <td>Inmueble</td>
        <td><?php echo $model->contrato->inmueble->direccion;?></td>
    </tr>
    <tr>
        <td>Fecha de Notificacion</td>
        <td><?php echo date('d/m/Y',strtotime($model->fecha_notificacion));?></td>
    </tr>
    <tr>
        <td>Nivel</td>
        <td><?php echo $model->nivel;?></td>
    </tr>
    <tr>
        <td>Fecha de Carga</td>
        <td><?php echo date('d/m/Y',strtotime($model->fecha));?></td>
    </tr>
    <tr>
        <td>Hora de Carga</td>
        <td><?php echo date('H:is',strtotime($model->hora));?></td>
    </tr>
</table>
<script language="JavaScript">window.print();</script>