<?php

use yii\helpers\Html;
use app\models\Utils;

$this->title = utf8_encode('Cierre de Caja');
$formatter = \Yii::$app->formatter;
?>
<div class="a2-liquidaciones-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <?php
            if (isset($mostrar_resumen_cierre) && !$mostrar_resumen_cierre) {
                $class = "";
                $titulo = "Mensaje";
                if (!empty($mensaje_error)) {
                    $class = "callout callout-danger";
                    $mensaje = $mensaje_error;
                } else {
                    $class = "callout callout-success";
                    $mensaje = $mensaje;
                }
                ?>
                <div id="errores_cierre_caja" class="<?php echo $class; ?>">
                    <h4><?php echo $titulo; ?></h4>

                    <p id="data_errores_cierre_caja">
                        <?php echo $mensaje; ?>
                    </p>
                </div>

                <?php
            }
            if (empty($mensaje_error)) {
                ?>
                <div class="col-xs-12 col-md-6">
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-yellow">
                            <div class="widget-user-image">


                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username">Datos de Cierre</h3>
                            <h5 class="widget-user-desc">Usuario <?php echo $caja->cierre_usuario ?></h5>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <!--<li><a href="#">Caja Final Sistema: <span class="pull-right "><b><?php //echo $formatter->asCurrency($caja->caja_final); ?></b></span></a></li>-->
                                <li><a href="#">Sobrante/Faltante: <span class="pull-right "><b><?php echo $formatter->asCurrency($caja->sobrante_faltante); ?></b></span></a></li>
                                <li><a href="#">Rendici&oacute;n:  <span class="pull-right "><b><?php echo $formatter->asCurrency($caja->rendicion); ?></b></span></a></li>
                                <li><a href="#">Caja D&iacute;a Siguiente: <span class="pull-right "><b><?php echo $formatter->asCurrency($caja->caja_cierre); ?></b></span></a></li>
                                <li><a href="#">Fecha Cierre: <span class="pull-right "><b><?php echo date('d/m/Y',strtotime($caja->cierre_fecha)); ?></b></span></a></li>
                                <li><a href="#">Hora Cierre: <span class="pull-right "><b><?php echo $caja->cierre_hora; ?></b></span></a></li>
                                <li><a href="#">Usuario que cerr&oacute;:<span class="pull-right "><b><?php echo $caja->cierre_usuario; ?></b></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>	

    </div>
</div>