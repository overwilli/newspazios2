<?php

use yii\helpers\Html;
use app\models\A2Liquidaciones;
use yii\widgets\ActiveForm;
use app\models\A2Movimientos;

/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */

$this->title = 'Borrar Pago de Liquidacion: ';
$this->params['breadcrumbs'][] = ['label' => 'Liquidaciones Pagadas', 'url' => ['liquidaciones-pagadas']];
//$this->params['breadcrumbs'][] = ['label' => $model->id_liquidacion, 'url' => ['view', 'id' => $model->id_liquidacion]];
$this->params['breadcrumbs'][] = 'Borrar Pago';
//$dias_mora = A2Liquidaciones::obtener_dias_mora($model->id_liquidacion);
//$arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($model->id_operacion, $model->liq_anio, $model->liq_mes, $dias_mora);
$monto_pagado=A2Movimientos::obtener_total_pagado($model->id_liquidacion);
$registros_pagos=A2Movimientos::obtener_registro_pagado($model->id_liquidacion);

$primer_registro="";
if($registros_pagos){
    $primer_registro=$registros_pagos[0];
}
$formatter = \Yii::$app->formatter;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="a2-liquidaciones-update">
    <input type="hidden" id="id_liquidacion" name="id_liquidacion" value="<?php echo $model->id_liquidacion ?>"/>
    <input name="monto" type="hidden" id="monto" value="<?php echo number_format($monto_pagado, 2, '.', '') ?>"/>
    
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?php
                    if ($model->operacionInmobiliaria) {
                        echo "<b>CLIENTE: " . $model->operacionInmobiliaria->cliente->NOMBRE . "</b>";
                    }
                    ?>
                </div>
                <div class="col-xs-12 col-md-6">
                    <?php
                    if ($model->operacionInmobiliaria) {
                        echo "<b>INMUEBLE: " . $model->operacionInmobiliaria->inmueble->direccion . "</b>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        if (empty($mensaje_error) && empty($mensaje_ok)) {
            ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <?php
                        if ($model->operacionInmobiliaria) {
                            echo "<b>A&Ntilde;O: " . $model->liq_anio . "</b>";
                        }
                        ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php
                        if ($model->operacionInmobiliaria) {
                            echo "<b>MES: " . $model->liq_mes . "</b>";
                        }
                        ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php
                        if ($model->operacionInmobiliaria) {
                            if (empty($mensaje_error) && empty($mensaje_ok)) {
                                echo "<b>MONTO: " . $formatter->asCurrency($monto_pagado) . "</b>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <br/><br/>
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <?php
                        if ($primer_registro) {
                            echo "<b>DENOMINACION: " . $primer_registro['denominacion']. "</b>";
                        }
                        ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php
                        if ($primer_registro) {
                            echo "<b>NUMERO DE CAJA: " . $primer_registro['numero_caja'] . "</b>";
                        }
                        ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php
                        if ($primer_registro) {
                            echo "<b>OBSERVACIONES: " . $primer_registro['observaciones'] . "</b>";
                        }
                        ?>
                    </div>
                </div>
                <hr/>

                                
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <button id="registrar_pago" type="submit" class="btn btn-success">Borrar Pago</button>
                    </div>
                </div>


            </div>
            <?php
        } else {
            if ($mensaje_error) {
                ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="alert alert-danger alert-dismissible">                            
                                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                <?php echo utf8_encode($mensaje_error); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            if ($mensaje_ok) {
                ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="alert alert-success alert-dismissible">                            
                                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                                <?php echo utf8_encode($mensaje_ok); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="box-body">
                <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= Html::a('Volver a Liquidaciones Pagadas', ['a2-liquidaciones/liquidaciones-pagadas'], ['class' => 'btn btn-primary']);?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>



</div>
<?php ActiveForm::end(); ?>
