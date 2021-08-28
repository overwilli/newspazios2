<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\A2Clientes */

$this->title = 'Expensas cargadas';
?>
<div class="a2-clientes-create">
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="box box-success">
                <?php $form = ActiveForm::begin(['options'=>['target'=>'_blank']]); ?>
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="">Periodo desde</label>
                                <?php
                                $arreglo_periodos = ArrayHelper::map(\app\models\A2Liquidaciones::ObtenerPeriodosLiquidados(), 'periodos', 'periodos');                                
                                ?>
                                <?php
                                echo Html::dropDownList('periodo_desde','', $arreglo_periodos, 
                                    ['prompt' => 'Seleccione un periodo desde','class' => 'form-control',])
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="">Periodo Hasta</label>
                                <?php
                                $arreglo_periodos = ArrayHelper::map(\app\models\A2Liquidaciones::ObtenerPeriodosLiquidados(), 'periodos', 'periodos');                                
                                ?>
                                <?php
                                echo Html::dropDownList('periodo_hasta','', $arreglo_periodos, 
                                    ['prompt' => 'Seleccione un periodo hasta','class' => 'form-control',])
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    
                    if($show_inmueble==1){?>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                            <label class="control-label" for="">Inmueble</label>
                                <?php
                                $arreglo_inmuebles = ArrayHelper::map(\app\models\A2OperacionesInmobiliarias::ObtenerInmueblesConContratosActivosPendiente(), 'id_operacion_inmobiliaria', 'direccion');
                                //echo $form->field($model, 'id_operacion')->dropDownList($arreglo_inmuebles, ['options' => [1 => ['selected' => TRUE]],]);

                                echo Select2::widget([
                                    'name' => 'id_operacion',
                                    'value' => [], // initial value
                                    'data' => $arreglo_inmuebles,
                                    'maintainOrder' => true,
                                    'options' => ['placeholder' => 'Seleccione un Inmueble', 'multiple' => false],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                        //'tags' => true,
                                        //'maximumInputLength' => 10
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Mostrar</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>