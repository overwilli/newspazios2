<?php

use yii\helpers\Html;
use app\models\Utils;

$this->title = 'Arqueo de Caja';
//$this->params['breadcrumbs'][] = ['label' => 'Liquidaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$formatter = \Yii::$app->formatter;
?>
<div class="a2-liquidaciones-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body"> 
            <div class="callout bg-purple-active color-palette">
                <?php
                if ($model_caja->cierre_usuario == "")
                    $estado_caja = 'caja iniciada el d&iacute;a ' . Utils::Fecha_en_Castellano($model_caja->inicio_fecha) . ' <b>SIN CERRAR</b> ';
                else
                    $estado_caja = '' . Utils::Fecha_en_Castellano($model_caja->cierre_fecha) . ' <b>CERRADA</b> ';
                print '<div class="Errores">' . $estado_caja . '</div>';
                ?>
            </div>            
            <?php
            if (Yii::$app->user->identity->permisos == "administrador" || Yii::$app->user->identity->permisos == "intermedio") {
                ?>
                <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Mostrar Movimientos</button><br/><br/>
                <?php
            }
            ?>
            <div id="demo" class="collapse">
                <table class="table table-bordered">

    <!--                <tr> 
                        <td class="bg-purple-active color-palette" colspan="5">
                    <?php
                    if ($model_caja->cierre_usuario == "")
                        $estado_caja = 'caja iniciada el día ' . Utils::Fecha_en_Castellano($model_caja->inicio_fecha) . ' <b>SIN CERRAR</b> ';
                    else
                        $estado_caja = '' . Utils::Fecha_en_Castellano($model_caja->cierre_fecha) . ' <b>CERRADA</b> ';
                    print '<div class="Errores">' . utf8_encode($estado_caja) . '</div>';
                    ?>
                        </td>	
                    </tr>	-->
                    <thead >
                        <tr id="cabezera-tabla"> 
                            <th height="36" width="50">FECHA</th>	  
                            <th height="36">USUARIO</th>
                            <th height="36">MOVIMIENTO</th>		
                            <th>MONTO</th>	 
                            <th width="300" align="left">OBSERVACION</th>		


                        </tr>
                    </thead>
                    <?php
                    $estilo = "filapar";
                    $contador = 0;
                    $total_a_cobrar = 0;
                    $total_cobrado = 0;
                    $movi_tipo_anterior = 0;
                    $indice_vector = 0;
                    while ($indice_vector <= 17) {
                        $vector[$indice_vector] = 0;
                        $indice_vector++;
                    }

                    foreach ($model_movimiento as $row) {
                        if ($estilo == "info")
                            $estilo = "warning";
                        else
                            $estilo = "info";
                        //print $vector[$row["movimiento_tipo"]]."<br>";
                        if ($row['tipo_registro'] != 'pago_pendiente') {
                            $vector[$row["movimiento_tipo"]]+=$row["monto"];
                        }
                        //die($vector[$row["movimiento_tipo"]]." - ".$row["monto"]); 
                        ?>
                        <tr class="<?php print $estilo; ?>" onmouseover="this.className = 'danger';" onmouseout="this.className = '<?php print $estilo; ?>';"> 
                            <td valign="top" > <?php
                                $aux_fecha = explode("-", $row["fecha_movimiento"]);
                                print $aux_fecha[2] . "-" . $aux_fecha[1] . "-" . $aux_fecha[0];
                                ?></td>
                            <td valign="top" ><?php print $row["usuario"]; ?></td>
                            <td valign="top" ><?php print $row->tipoMovimiento->denominacion; ?></td>		
                            <td valign="top" >
                                <?php
                                if ($row['tipo_registro'] == 'pago_pendiente') {
                                    print $formatter->asCurrency(0);
                                } else {
                                    if ($movi_tipo_anterior == 7 &&
                                            $fecha_movimiento_anterior == $row["fecha_movimiento"] &&
                                            $id_liquidacion_anterior = $row["id_liquidacion"] &&
                                            ($row["movimiento_tipo"] == 1 || $row["movimiento_tipo"] == 1
                                            || $row["movimiento_tipo"] == 2|| $row["movimiento_tipo"] == 3)) {
                                        print $formatter->asCurrency(($row["monto"] - $monto_anterior));
                                    } else {
                                        print $formatter->asCurrency($row["monto"]);
                                    }
                                }
                                ?></td>	
                            <td valign="top" >
                                <?php
                                if ($row['tipo_registro'] == 'pago_pendiente') {
                                    echo "<b>Registro de pago pendiente</b><br/>";
                                }
                                print $row["observaciones"];
                                ?>	</td>		


                        </tr>
                        <?php
                        //$total_a_cobrar+=$aux_monto_a_cobrar;
                        if ($row["movimiento_tipo"] == 1 || $row["movimiento_tipo"] == 2 || $row["movimiento_tipo"] == 3 ||
                            $row["movimiento_tipo"] == 4 || $row["movimiento_tipo"] == 8 || $row["movimiento_tipo"] == 11 ||
                                $row["movimiento_tipo"] == 12 || $row["movimiento_tipo"] == 13 || $row["movimiento_tipo"] == 15) {//QUE NO TENGA EN CUENTA INTERESES
                            if ($row['tipo_registro'] != 'pago_pendiente') {
                                $total_cobrado+=$row["monto"];
                            }
                        }
                        if ($row["movimiento_tipo"] == 7) {//INTERES POR MORA
                            $fecha_movimiento_anterior = $row["fecha_movimiento"];
                            $monto_anterior = $row["monto"];
                            $movi_tipo_anterior = $row["movimiento_tipo"];
                            $id_liquidacion_anterior = $row["id_liquidacion"];
                        }
                        $id_liq_aux = $row["id_liquidacion"];
                        $contador++;
                    }
                    ?>
                    <tr> 
                        <td colspan="4"><br/><br /><b><?php print 'Mostrando  ' . $contador . ' movimientos'; ?></b>
                            <br />Recaudaci&oacute;n del d&iacute;a: <?php print $formatter->asCurrency($total_cobrado); ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <?php
            if (!isset($model_caja->cierre_usuario) || $model_caja->cierre_usuario == "") {
                echo $this->render('resumen-arqueo-caja', ['model_caja' => $model_caja, 'vector' => $vector
                ]);
            }
            ?>

            <?php
            if ($model_caja->cierre_usuario != "") {
                echo $this->render('mensaje_cierre_caja', ['caja' => $model_caja, "mostrar_resumen_cierre" => TRUE, 'mensaje_error' => ""
                ]);
            }
            ?>

        </div>	

    </div>
</div>
</div>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/a2-caja/arqueo-caja.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>