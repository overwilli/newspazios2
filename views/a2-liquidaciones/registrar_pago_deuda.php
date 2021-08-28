<?php

use yii\helpers\Html;
use app\models\A2Liquidaciones;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */

$this->title = 'Registrar Pago de Expensa ';
$this->params['breadcrumbs'][] = ['label' => 'Expensas Deuda', 'url' => ['expensas-deuda']];
//$this->params['breadcrumbs'][] = ['label' => $model->id_liquidacion, 'url' => ['view', 'id' => $model->id_liquidacion]];
$this->params['breadcrumbs'][] = 'Registrar Pago';
//$dias_mora = A2Liquidaciones::obtener_dias_mora($model->id_liquidacion);
//$arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($model->id_operacion, $model->liq_anio, $model->liq_mes, $dias_mora);

$formatter = \Yii::$app->formatter;
?>
<?php $form = ActiveForm::begin(['id' => 'form_registrar_pago']); ?>
<div class="a2-liquidaciones-update">
    <input type="hidden" id="id_operacion" value="<?php echo $model->id_operacion_inmobiliaria ?>"/>    
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?php
                    if ($model) {
                        echo "<b>CLIENTE: " . $model->cliente->NOMBRE . "</b>";
                    }
                    ?>
                </div>
                <div class="col-xs-12 col-md-6">
                    <?php
                    if ($model) {
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
                    <div class="col-xs-12 col-md-4">

                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php
                        $resultado_periodo = A2Liquidaciones::ObtenerPeriodosDeudaExpensa($model->id_operacion_inmobiliaria);
                        ?>
                        <label style="width: 100px">PERIODO </label>
                        <select id="periodos_deuda" name="periodos_deuda">
                            <?php
                            foreach ($resultado_periodo as $row) {
								$selected="";
								if(($mes. "/" . $year) == ($row['mes'] . "/" . $row['year'])){
									$selected="selected='selected'";
								}
                                ?>
                                <option <?php echo $selected; ?> value="<?php echo $row['mes'] . "/" . $row['year']; ?>"><?php echo $row['mes'] . "/" . $row['year']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-3">

                    </div>
                </div>
                <div id="resultado" class="row">

                </div>                
                <h4 class="page-header">Formas de Pago</h4>
                <div class="row">
                    <div class="col-xs-12 col-md-2">
                        <label style="width: 100px">Efectivo </label>
                        <input style="width: 100px" type="text" id="efectivo_monto" 
                            name="efectivo_monto" class="decimales" value="<?php //echo number_format($arreglo_monto['monto'], 2, '.', '') ?>"/>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <label style="width: 100px">Cheque </label>
                        <input style="width: 100px" class="decimales" type="text" id="cheque_monto" name="cheque_monto" value=""/>
                    </div>
                    <div class="col-xs-12 col-md-2">                    
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <label>Deposito Monto </label>
                                <input id="deposito_monto" class="decimales" style="width: 100px" type="text" name="deposito_monto" value=""/>
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
                                        'onSelect' => new \yii\web\JsExpression('function(dateText, inst) { recalcular_intereses(); }'),
                                        'onChangeMonthYear' => new \yii\web\JsExpression('function(dateText, inst) { recalcular_intereses(); }')
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
                                <input style="width: 100px" class="decimales" type="text" id="retencion_monto" name="retencion_monto" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <label style="width: 100px" class="control-label">Tipo </label>
                                <input style="width: 100px" type="text" name="" value=""  />
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <label id="dias_mora_label">Mora (<?php //echo $dias_mora; ?> d&iacute;as)<?php //echo $arreglo_monto['interes_por_dia']; ?> </label>
                        <label id="monto_interes_label" style="width: 100px"><?php //echo $formatter->asCurrency($arreglo_monto['interes']) ?></label>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <button id="registrar_pago" type="submit" class="btn btn-success">Registrar Pago</button>
                    </div>
                </div>
            </div>


            <?php
            //DETERMINAR LA CUOTA DEL SIGUIENTE MES...
            /* if ($model->liq_mes == 12) {
              $month = 1;
              $year = $model->liq_anio + 1;
              } else {
              $month = $model->liq_mes + 1;
              $year = $model->liq_anio;
              }
              $arreglo_monto_sin_interes = A2Liquidaciones::Calcular_Monto_Periodo($model->id_operacion, $model->liq_anio, $model->liq_mes, 0);
              $arreglo_monto_proximo = A2Liquidaciones::Calcular_Monto_Periodo($model->id_operacion, $month, $year);
              if ($arreglo_monto_sin_interes['monto'] != $arreglo_monto_proximo['monto']) {
              ?>
              <hr/>
              <div class="row">
              <div class="col-xs-12 col-md-12">
              <div class="alert alert-warning alert-dismissible">
              <h4><i class="icon fa fa-warning"></i> Alert!</h4>
              <?php echo utf8_encode("El importe para el pr�ximo vencimiento es, $" . number_format($arreglo_monto_sin_interes['monto'], 2, ',', '.')); ?>
              </div>
              </div>
              </div>
              <?php
              } */
            ?>

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
                        <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-liquidaciones/index'], ['class' => 'btn btn-info',]) ?>
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
                            <?php echo utf8_encode($mensaje_ok); 
                            echo " ".Html::a("<span style='font-size:20px' class='glyphicon glyphicon-print'></span>", ['a2-liquidaciones/imprimir-comprobante-expensas',
                                    'plantilla' => "recibo_comun", 'id_liquidacion' => 0, 
                                    'id_operacion' => $id_operacion,
                                    'monto' => $monto_total,'fecha_pago'=>$fecha_pago, 'monto_sin_iva' => 0], ['title' => 'Imprimir comprobante de pago','target'=>'blank']);
                            ?>
                        </div>
                        <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-liquidaciones/index'], ['class' => 'btn btn-info',]) ?>
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
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Procesando...</h4>
            </div>
            <div class="modal-body">
                <center><img src="<?php echo Yii::getAlias('@web') ?>/images/loader.gif" /></center>
            </div>
            <div class="modal-footer">
                <!--        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>-->
                <!--        <button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/liquidaciones/liquidaciones_deuda_expensa.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>