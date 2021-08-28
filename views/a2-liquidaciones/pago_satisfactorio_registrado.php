<?php

use yii\helpers\Html;
use app\models\A2Liquidaciones;
use yii\widgets\ActiveForm;
use app\models\A2OperacionesInmobiliarias;

/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */

$this->title = 'Registrar Pago: ' . $model->id_liquidacion;
$this->params['breadcrumbs'][] = ['label' => 'Liquidaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_liquidacion, 'url' => ['view', 'id' => $model->id_liquidacion]];
$this->params['breadcrumbs'][] = 'Registrar Pago';
$dias_mora = A2Liquidaciones::obtener_dias_mora($model->id_liquidacion);
$arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($model->id_operacion, $model->liq_anio, $model->liq_mes, $dias_mora);

$formatter = \Yii::$app->formatter;
?>
<?php $form = ActiveForm::begin(['id' => 'form_registrar_pago']); ?>
<div class="a2-liquidaciones-update">
    <input type="hidden" id="id_liquidacion" value="<?php echo $model->id_liquidacion ?>"/>
    <input name="monto" type="hidden" id="monto" value="<?php echo $arreglo_monto['monto'] ?>"/>
    <input name="mora_monto" type="hidden" id="mora_monto" value="<?php echo $arreglo_monto['interes'] ?>"/>
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
                        echo "<b>MONTO: " . $formatter->asCurrency($arreglo_monto['monto']) . "</b>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="alert alert-success alert-dismissible">                            
                        <h4><i class="icon fa fa-check"></i> Mensaje</h4>
                        <?php echo utf8_encode($mensaje); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12"><p>
                        <?php
                        if(isset($model_pago_parcial)){
                            echo "Recibido de Pago Parcial:  " . $formatter->asCurrency($model_pago_parcial->monto);
                                echo " ".Html::a("<span style='font-size:20px' class='glyphicon glyphicon-print'></span>", ['a2-liquidaciones/imprimir-comprobante',
                                    'plantilla' => "recibo_comun", 'id_liquidacion' => $model->id_liquidacion, 
                                    'id_operacion' => $model->id_operacion,
                                    'monto' => $model_pago_parcial->monto, 'monto_sin_iva' => 0], ['title' => 'Imprimir comprobante de pago parcial','target'=>'blank']);
                                echo "<hr/>";
                        }
                        if(!isset($model_pago_parcial) || (isset($model_pago_parcial) && $liq_cancelado)){
                            foreach ($arreglo_monto['facturas'] as $fila) {
                                echo "Comprobante <b>{$fila['descripcion']}</b>:  " . $formatter->asCurrency(($fila['monto_iva']==0)?$fila['monto']:$fila['monto_iva']);
                                echo " ".Html::a("<span style='font-size:20px' class='glyphicon glyphicon-print'></span>", ['a2-liquidaciones/imprimir-comprobante',
                                    'plantilla' => $fila['plantilla'], 'id_liquidacion' => $model->id_liquidacion, 'id_operacion' => $model->id_operacion,
                                    'monto' => ($fila['monto_iva']==0)?$fila['monto']:$fila['monto_iva'], 'monto_sin_iva' => $fila['monto']], ['title' => 'Imprimir comprobante de pago','target'=>'blank']);
                                echo "<hr/>";
                            }

                            if ($arreglo_monto['interes'] > 0) {
                                echo "Inter&eacute;s por Mora:  " . $formatter->asCurrency($arreglo_monto['interes']);
                                echo " ".Html::a("<span style='font-size:20px' class='glyphicon glyphicon-print'></span>", ['a2-liquidaciones/imprimir-comprobante',
                                    'plantilla' => "recibo_comun", 'id_liquidacion' => $model->id_liquidacion, 'id_operacion' => $model->id_operacion,
                                    'monto' => $arreglo_monto['interes'], 'monto_sin_iva' => 0], ['title' => 'Imprimir comprobante de pago','target'=>'blank']);
                                echo "<hr/>";
                            }
                        }
                        ?></p>
                        <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-liquidaciones/index'], ['class' => 'btn btn-info',]) ?>
                </div>
            </div>
        </div>

    </div>



</div>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/liquidaciones/liquidaciones.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>