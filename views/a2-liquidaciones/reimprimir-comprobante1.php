<?php

use yii\helpers\Html;
use app\models\A2Liquidaciones;
use yii\widgets\ActiveForm;
use app\models\A2OperacionesInmobiliarias;

/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */

$this->title = 'Reimprimir Comprobante de Pago: ' . $model->id_liquidacion;
$this->params['breadcrumbs'][] = ['label' => 'Liquidaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_liquidacion, 'url' => ['view', 'id' => $model->id_liquidacion]];
$this->params['breadcrumbs'][] = 'Reimprimir comprobante de pago';
//$dias_mora = A2Liquidaciones::obtener_dias_mora($model->id_liquidacion);
$arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($model->id_operacion, $model->liq_anio, $model->liq_mes, $dias_mora);
$mora_pagada= A2Liquidaciones::obtener_mora_pagada($model->id_liquidacion);
$formatter = \Yii::$app->formatter;
?>
<?php $form = ActiveForm::begin(['id' => 'form_registrar_pago']); ?>
<div class="a2-liquidaciones-update">
    <input type="hidden" id="id_liquidacion" value="<?php echo $model->id_liquidacion ?>"/>
    <input name="monto" type="hidden" id="monto" value="<?php echo number_format($arreglo_monto['monto'], 2, '.', '') ?>"/>
    <input name="mora_monto" type="hidden" id="mora_monto" value="<?php echo $mora_pagada ?>"/>
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
        if (empty($mensaje_error)) {
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
                            echo "<b>MONTO: " . $formatter->asCurrency($arreglo_monto['monto']+$mora_pagada) . "</b>";
                        }
                        ?>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-12 col-md-2">
                        <label style="width: 100px">Efectivo </label>
                        <input style="width: 100px" type="text" id="efectivo_monto" name="efectivo_monto" value="<?php echo number_format($arreglo_monto['monto'], 2, '.', '') ?>"/>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <label style="width: 100px">Cheque </label>
                        <input style="width: 100px" type="text" id="cheque_monto" name="cheque_monto" value=""/>
                    </div>
                    <div class="col-xs-12 col-md-2">                    
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <label>Deposito Monto </label>
                                <input id="deposito_monto" style="width: 100px" type="text" name="deposito_monto" value=""/>
                            </div>
                        </div>
                        <div class="row deposito">
                            <div class="col-xs-12 col-md-12">
                                <label>Fecha de Deposito </label>

                                <?php
                                echo yii\jui\DatePicker::widget([
                                    'name' => 'fecha_deposito',
                                    'value' => '',
                                    //'language' => 'ru',
                                    'dateFormat' => 'dd/MM/yyyy',
                                    'options' => ['id' => 'fecha_deposito', 'style' => 'width:100px'],
                                    'clientOptions' => [
                                        'onSelect' => new \yii\web\JsExpression('function(dateText, inst) { recalcular_intereses(); }')
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">                    
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <label>Retencion Monto </label>
                                <input style="width: 100px" type="text" id="retencion_monto" name="retencion_monto" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <label style="width: 100px" class="control-label">Tipo </label>
                                <input style="width: 100px" type="text" name="" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <label id="dias_mora_label">Mora (<?php echo $dias_mora; ?> d&iacute;as)<?php echo $arreglo_monto['interes_por_dia']; ?> </label>
                        <label id="monto_interes_label" style="width: 100px"><?php echo $arreglo_monto['interes'] ?></label>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <button id="registrar_pago" name="registrar_pago" value="pago" type="submit" class="btn btn-success">Registrar Pago</button>                        
                    </div>
                </div>                

            </div>
            <?php
        } else {
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
        ?>
    </div>



</div>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/liquidaciones/liquidaciones.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>