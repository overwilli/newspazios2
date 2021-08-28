<?php
set_time_limit(0);

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=inmuebles_por_vencer.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

?>
    
<h1>Inmuebles por Vencer</h1>

<table style="border-collapse: collapse"  width="80%"  align="center" >
    <thead><tr> 
            <th width="250" height="36"><b>DIRECCION</b></th>
            <th ><b>CODIGO POSTAL</b></th>
            <th><b>TIPO DE PROPIEDAD</b></th>
            <th><b>OPERACION</b></th>
            <th><b>VENCIMIENTO</b></th>
            <th width="200"><b>PROPIETARIO</b></th>                
        </tr>
    </thead>
    <tbody>
        <tr> 
            <td colspan="4">

            </td>
        </tr>

        <?php
        $estilo = "filapar";
        $i = 0;
        foreach ($inmuebles_por_vencer as $row) {
            if (($i % 2) == 0) {
                $estilo_color = "#e4ecf7";
            } else {
                $estilo_color = "#FFF";
            }
            ?>
            <tr style="background-color:<?php echo $estilo_color ?>"> 
                <td width="250" valign="top" ><?php print utf8_decode($row->direccion); ?></td>
                <td width="80" valign="top"><?php print $row->codigo_postal; ?></td>
                <td width="200" valign="top"><?php print utf8_decode($row->secciones->seccion); ?></td>
                <td width="200" valign="top"><?php if ($row->objetoPropiedad) {
                            echo utf8_decode($row->objetoPropiedad->operacion);
                        } else {
                            echo "";
                        }; ?></td>
                <td width="100" valign="top"><?php $contrato_activo=$row->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                        if ($contrato_activo) {
                            echo $contrato_activo->hasta_mes."/".$contrato_activo->hasta_anio;
                        } else {
                            echo "";
                        } ?></td>
                <td width="200" valign="top"><?php $obj_propietarios=app\models\InmueblesPropietarios::find()->where(['inmueble_id'=>$row->id_noticia])->one();                            
                        if ($obj_propietarios) {
                            echo utf8_decode($obj_propietarios->propietario->apellido.", ".
                                $obj_propietarios->propietario->nombre);
                        } else {
                            echo "";
                        } ?></td>                    
            </tr>
            <?php
            $i++;
        }
        ?>     
    </tbody>
    <tfoot>
        <tr>
            <th style="width: 30%; text-align: left;">&nbsp;</th>
            <th style="width: 30%; text-align: left;"></th>
        </tr>
    </tfoot>
</table>

