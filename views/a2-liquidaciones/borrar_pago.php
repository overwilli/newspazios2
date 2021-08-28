<?php

use yii\helpers\Html;
use app\models\A2Liquidaciones;
use yii\widgets\ActiveForm;
use app\models\A2Movimientos;

/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */

$this->title = 'Borrar Pago de Expensa: ';
$this->params['breadcrumbs'][] = ['label' => 'Expensa Pagadas', 'url' => ['expensas-pagadas']];
//$this->params['breadcrumbs'][] = ['label' => $model->id_liquidacion, 'url' => ['view', 'id' => $model->id_liquidacion]];
$this->params['breadcrumbs'][] = 'Borrar Pago';
//$dias_mora = A2Liquidaciones::obtener_dias_mora($model->id_liquidacion);
//$arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($model->id_operacion, $model->liq_anio, $model->liq_mes, $dias_mora);

$formatter = \Yii::$app->formatter;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="a2-liquidaciones-update">
    <input type="hidden" id="id_operacion" value="<?php echo $model->id_operacion_inmobiliaria ?>"/>
    <input name="mes" type="hidden" id="mes" value="<?php echo $mes ?>"/>
    <input name="year" type="hidden" id="year" value="<?php echo $year ?>"/>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?php
                    if ($model->cliente) {
                        echo "<b>CLIENTE: " . $model->cliente->NOMBRE . "</b>";
                    }
                    ?>
                </div>
                <div class="col-xs-12 col-md-6">
                    <?php
                    if ($model->inmueble) {
                        echo "<b>INMUEBLE: " . $model->inmueble->direccion . "</b>";
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
                        if ($year) {
                            echo "<b>A&Ntilde;O: " . $year . "</b>";
                        }
                        ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php
                        if ($mes) {
                            echo "<b>MES: " . $mes . "</b>";
                        }
                        ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php
                        if ($monto) {
                            echo "<b>MONTO: " . $formatter->asCurrency($monto) . "</b>";
                        }
                        ?>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <?php
                        $resultado_exp = A2Liquidaciones::obtener_expensas_pagadas($model->cliente->NOMBRE, $model->inmueble->direccion, $mes, $year);
                        ?>
                        <table class="table table-bordered">

                            <tr id="cabezera-tabla">
                                <th><b>Usuario de la operacion</b></th>
                                <th><b>Fecha de la operacion</b></th>
                                <th><b>Tipo de Movimiento</b></th>
                                <th><b>Importe</b></th>
                                <th><b>Numero de Caja</b></th>
                                <th><b>Observaciones</b></th>
                            </tr>
                            <?php
                            foreach ($resultado_exp as $fila) {                                
                                ?>
                                <tr>                                
                                    <td ><?php echo $fila['usuario'] ?></td>                                                            
                                    <td ><?php echo date('d-m-Y', strtotime($fila['fecha_movimiento'])) ?></td>                                                    
                                    <td ><?php echo $fila['denominacion'] ?></td>                                                                                    
                                    <td ><?php echo $formatter->asCurrency($fila['monto']); ?></td>                                
                                    <td><?php echo $fila['numero_caja']; ?></td>
                                    <td><?php echo $fila['observaciones'] ?></td>                        
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td ><b>Total:</b></td>
                                <td ><?php echo $formatter->asCurrency($monto) ?></td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>                        
                            </tr>
                        </table>
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
        }
        ?>
    </div>



</div>
<?php ActiveForm::end(); ?>
