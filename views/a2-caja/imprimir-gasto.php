<?php

use yii\helpers\Html;
use app\models\A2Noticias;
/* @var $this yii\web\View */
/* @var $model app\models\A2Caja */
$formatter = \Yii::$app->formatter;
?>
<table width="400" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF">
    <tr>
        <td colspan="2"><div align="center"><strong>COMPROBANTE DE MOVIMIENTO </strong></div></td>
    </tr>
    <tr>
        <td><div align="right">Fecha:</div></td>
        <td><div align="left"></div>
            <?php print date("d/m/Y h:i:s", strtotime($datos['fecha_movimiento'])); ?></td>
    </tr>
    <tr>
        <td><div align="right">Importe:</div></td>
        <td><?php echo $formatter->asCurrency($datos['importe_movimiento']); ?></td>
    </tr>
    <tr>
        <td><div align="right">Movimiento:</div></td>
        <td>
            <?php
            switch ($datos['tipo_movimiento']) {
                case 6: {
                        echo "Gastos Varios";
                    }break;
                case 5: {
                        echo "Supermercado";
                    }break;
                case 8: {
                        echo "Ingresos Varios";
                    }break;
                case 10: {
                        echo "Transferencias entre Cajas";
                    }break;
            };
            ?>	</td>
    </tr>
    <?php    
    if(isset($datos['propiedad_id'])){
        $model_inmueble=A2Noticias::find()->where(['id_noticia'=>$datos['propiedad_id']])->one();        
        if($model_inmueble){
            ?>
            <tr>
                <td><div align="right">Inmueble:</div></td>
                <td><?php print $model_inmueble->direccion; ?></td>
            </tr>
            <?php
        }
    }
    ?>
    <tr>
        <td><div align="right">Observaciones:</div></td>
        <td><?php print $datos['observaciones_ingreso_egreso']; ?></td>
    </tr>
    <tr>
        <td><div align="right">Usuario:</div></td>
        <td><?php print $datos['usuario']; ?></td>
    </tr>
</table>
<?php if ($datos['tipo_movimiento'] == 10) { ?>
    <hr style="border:1px dotted black;margin-top: 10px;margin-bottom: 10px"/>
    <table width="400" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF">
        <tr>
            <td colspan="2"><div align="center"><strong>COMPROBANTE DE MOVIMIENTO </strong></div></td>
        </tr>
        <tr>
            <td><div align="right">Fecha:</div></td>
            <td><div align="left"></div>
                <?php print date("d/m/Y h:i:s", strtotime($datos['fecha_movimiento'])); ?></td>
        </tr>
        <tr>
            <td><div align="right">Importe:</div></td>
            <td><?php print $formatter->asCurrency($datos['importe_movimiento']); ?></td>
        </tr>
        <tr>
            <td><div align="right">Movimiento:</div></td>
            <td>
                <?php
                switch ($datos['tipo_movimiento']) {
                    case 6: {
                            echo "Gastos Varios";
                        }break;
                    case 5: {
                            echo "Supermercado";
                        }break;
                    case 8: {
                            echo "Ingresos Varios";
                        }break;
                    case 10: {
                            echo "Transferencias entre Cajas";
                        }break;
                };
                ?>	</td>
        </tr>
        <tr>
            <td><div align="right">Observaciones:</div></td>
            <td><?php print $datos['observaciones_ingreso_egreso']; ?></td>
        </tr>
        <tr>
            <td><div align="right">Usuario:</div></td>
            <td><?php print $datos['usuario']; ?></td>
        </tr>
    </table>
    <?php
}
?>

<script language="JavaScript">window.print();</script>