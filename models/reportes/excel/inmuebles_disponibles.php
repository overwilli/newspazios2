<?php
set_time_limit(0);

header("Content-Type:   application/vnd.ms-excel; charset=Windows-1252");
header("Content-type:   application/x-msexcel; charset=Windows-1252");
header("Content-Disposition: attachment; filename=inmuebles_por_renovar.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

?>
    
<h1>Inmuebles Disponibles</h1>

<table style="border-collapse: collapse"  width="80%"  align="center" >
    <thead><tr> 
            <th width="250" height="36"><b>DIRECCION</b></th>
            <th ><b>CODIGO POSTAL</b></th>            
            <th><b>TIPO DE PROPIEDAD</b></th>
            <th><b>OPERACION</b></th>
            <th><b>PRECIO</b></th>            
        </tr>
    </thead>
    <tbody>        

        <?php        
        $i = 0;
        foreach ($inmuebles_disponibles as $row) {
            ?>
            <tr > 
                <td width="250" valign="top" ><?php print utf8_decode($row->direccion); ?></td>
                <td width="80" valign="top"><?php print $row->codigo_postal; ?></td>
                <td width="250" valign="top"><?php echo utf8_decode($row->secciones->seccion); ?></td>
                <td width="200" valign="top">
                <?php  if ($row->objetoPropiedad) {
                            echo utf8_decode($row->objetoPropiedad->operacion);
                        } 
                ?>
                </td>
                <td width="100" valign="top"><?php echo $row->precio;?></td>                
                                   
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