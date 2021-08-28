<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\A2Clientes */

$this->title = 'Informe de Gestión de Cobranzas';
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
                                <label class="control-label" for="">Cliente</label>
                                <?php
                                $arreglo_cliente = ArrayHelper::map(($clientes), 'id_cliente', 'NOMBRE');                                
                                ?>
                                <?php
                                /*echo Html::dropDownList('periodo','', $arreglo_periodos, 
                                    ['prompt' => 'Seleccione un periodo','class' => 'form-control',]);*/

                                    echo Select2::widget([
                                        'name' => 'id_cliente',
                                        'value' => [], // initial value
                                        'data' => $arreglo_cliente,
                                        'maintainOrder' => true,
                                        'options' => ['placeholder' => 'Seleccione un cliente', 'multiple' => false],
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
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="">Concepto</label>
                                <?php
                                $arreglo_orden = ['ALQUILER'=>'ALQUILER','EXPENSAS'=>'EXPENSAS','AMBOS'=>'AMBOS'];                                
                                ?>
                                <?php
                                echo Html::dropDownList('concepto','', $arreglo_orden, 
                                    ['prompt' => 'Seleccione un concepto','class' => 'form-control',]);                                   
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="">Formato</label>
                                <?php
                                $arreglo_formato = ['EXCEL'=>'EXCEL','PDF'=>'PDF'];                                
                                ?>
                                <?php
                                echo Html::dropDownList('formato','', $arreglo_formato, 
                                    ['prompt' => 'Seleccione un formato','class' => 'form-control',]);                                   
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Mostrar</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>