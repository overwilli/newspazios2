<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//use kartik\select2\Select2;
//use kartik\widgets\Select2;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\A2OperacionesInmobiliarias */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
    window.onload = function () {
        $('#div_deposito_garantia').hide();
        
    };
</script>
<div class="a2-operaciones-inmobiliarias-form">



    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'cod_propiedad')->hiddenInput()->label(false); ?>
    <div class="row">
        <div class="col-xs-12 col-md-4">
        <div class="row">
                <div class="col-xs-12 col-md-12">                                      
                    <?php
                    // Normal select with ActiveForm & model
                    $cityDesc = empty($model->locadorPropietario) ? '' : $model->locadorPropietario->apellido.", ".$model->locadorPropietario->nombre;

                    
                    echo $form->field($model, 'locador')->widget(Select2::classname(), [
                        //'data' => $cityDesc,
                        'initValueText' => $cityDesc,
                       'language' => 'es',
                        'options' => ['placeholder' => 'Seleccione el locador',
                        'disabled' => false,],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['propietarios/get-propietarios']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ],
                        'pluginEvents' => [
                            'change' => 'function(evt) {  
                                var data_id = $(this).val();                                
                                $.ajax({ method: "GET",
                                url: "' . \yii\helpers\Url::to(['propietarios/get-propietario-by-id']) . '",
                                data: { id: data_id }
                                }).done(function( data ) {                                    
                                    $("label[id=\'locador\']").html(data.results.apellido+", "+data.results.nombre);
                                    $("label[id=\'locador_dni\']").html(data.results.cuit);
                                    $("label[id=\'locador_domicilio\']").html(data.results.direccion);
                                    $("label[id=\'locador_localidad\']").html(data.results.localidad);
                                    $("label[id=\'locador_provincia\']").html(data.results.provincia);
                                    //$("label[id=\'locador_telefono\']").html(data.results.telefono);
                                    
                                });
                            }',
                            "select2:unselect" => 'function() { 
                                $("label[id=\'locador_1\']").html("");
                                $("label[id=\'locador_1_dni\']").html("");
                                $("label[id=\'locador_1_domicilio\']").html("");
                                $("label[id=\'locador_1_localidad\']").html("");
                                $("label[id=\'locador_1_provincia\']").html("");
                             }',
                        ],
                    ]);
                    ?>
                    <?php
                   $locador = "";
                    if ($model->cliente) {
                       $locador = $model->cliente->NOMBRE;                    }
                    //echo '<label id="locador" class="form-control" for="locador">' . $locador . '</label>';
                    ?>
                    <?php $form->field($model, 'cod_cliente')->hiddenInput()->label(false);  ?>
                    <?php $form->field($model, 'locador')->hiddenInput()->label(false);  ?>

                    <div class="help-block"></div>
                </div>       



                <div class="col-xs-12 col-md-12">
                    <!--<label class="control-label" for="locador_dni">Cuit</label>-->

                    <?php
                    $locador_dni = "";
                    if ($model->locadorPropietario) {
                        $locador_dni = $model->locadorPropietario->cuit;
                    }
                    //echo '<label id="locador_dni" class="form-control" for="locador_dni">' . $locador_dni . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>
                <div class="col-xs-12 col-md-12">
                    <!--<label class="control-label" for="locador_domicilio">Domicilio</label>-->                                       
                    <?php
                    $locador_domicilio = "";
                    if ($model->locadorPropietario) {
                        $locador_domicilio = $model->locadorPropietario->direccion;
                    }
                    //echo '<label id="locador_domicilio" class="form-control" for="locador_domicilio">' . $locador_domicilio . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_localidad">Localidad</label>                    
                    <?php
                    $locador_localidad = "";
                    if ($model->locadorPropietario) {
                        $locador_localidad = $model->locadorPropietario->localidad;
                    }
                    echo '<label id="locador_localidad" class="form-control" for="locador_localidad">' . $locador_localidad . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->                
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_provincia">Provincia</label>
                    <?php
                    $locador_provincia = "";
                    if ($model->locadorPropietario) {
                        $locador_provincia = $model->locadorPropietario->provincia;
                    }
                    echo '<label id="locador_provincia" class="form-control" for="locador_provincia">' . $locador_provincia . '</label>';
                    ?>                    
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_telefono">Telefono</label>                    
                    <?php
                    /*$locador_telefono = "";
                    if ($model->cliente) {
                        $locador_telefono = $model->cliente->TELEFONO;
                    }
                    echo '<label id="locador_telefono" class="form-control" for="locador_telefono">' . $locador_telefono . '</label>';*/
                    ?>
                    <div class="help-block"></div>
                </div>-->
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
        <div class="row" >
                <!--<div class="col-xs-12 col-md-12">                                      
                    <?php
                    // Normal select with ActiveForm & model
                    $cityDesc = empty($model->locador1Propietario) ? '' : $model->locador1Propietario->apellido.", ".$model->locador1Propietario->nombre;
                    
                    echo $form->field($model, 'locador_1')->widget(Select2::classname(), [
                        //'data' => $cityDesc,
                        'initValueText' => $cityDesc,
                        'language' => 'es',
                        'options' => ['placeholder' => 'Seleccione el locador'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['propietarios/get-propietarios']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ],
                        'pluginEvents' => [
                            'change' => 'function(evt) {  
                                var data_id = $(this).val();                                
                                $.ajax({ method: "GET",
                                url: "' . \yii\helpers\Url::to(['propietarios/get-propietario-by-id']) . '",
                                data: { id: data_id }
                                }).done(function( data ) {                                    
                                    $("label[id=\'locador_1\']").html(data.results.apellido+", "+data.results.nombre);
                                    $("label[id=\'locador_1_dni\']").html(data.results.cuit);
                                    $("label[id=\'locador_1_domicilio\']").html(data.results.direccion);
                                    $("label[id=\'locador_1_localidad\']").html(data.results.localidad);
                                    $("label[id=\'locador_1_provincia\']").html(data.results.provincia);
                                    //$("label[id=\'locador_1_telefono\']").html(data.results.telefono);
                                    
                                });
                            }',
                            "select2:unselect" => 'function() { 
                                $("label[id=\'locador_1\']").html("");
                                $("label[id=\'locador_1_dni\']").html("");
                                $("label[id=\'locador_1_domicilio\']").html("");
                                $("label[id=\'locador_1_localidad\']").html("");
                                $("label[id=\'locador_1_provincia\']").html("");
                             }',
                        ],
                    ]);
                    ?>                    
                    <?php //= $form->field($model, 'cod_cliente')->hiddenInput()->label(false);  ?>
                    <div class="help-block"></div>
                </div>-->       



               <!-- <div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_1_dni">Cuit</label>

                    <?php
                    $locador_1_dni = "";
                    if ($model->locador1Propietario) {
                        $locador_1_dni = $model->locador1Propietario->cuit;
                    }
                    echo '<label id="locador_1_dni" class="form-control" for="locador_1_dni">' . $locador_1_dni . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_1_domicilio">Domicilio</label>                                       
                    <?php
                    $locador_1_domicilio = "";
                    if ($model->locador1Propietario) {
                        $locador_1_domicilio = $model->locador1Propietario->direccion;
                    }
                    echo '<label id="locador_1_domicilio" class="form-control" for="locador_1_domicilio">' . $locador_1_domicilio . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_1_localidad">Localidad</label>                    
                    <?php
                    $locador_1_localidad = "";
                    if ($model->locador1Propietario) {
                        $locador_1_localidad = $model->locador1Propietario->localidad;
                    }
                    echo '<label id="locador_1_localidad" class="form-control" for="locador_1_localidad">' . $locador_1_localidad . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div> -->               
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_1_provincia">Provincia</label>
                    <?php
                    $locador_1_provincia = "";
                    if ($model->locador1Propietario) {
                        $locador_1_provincia = $model->locador1Propietario->provincia;
                    }
                    echo '<label id="locador_1_provincia" class="form-control" for="locador_1_provincia">' . $locador_1_provincia . '</label>';
                    ?>                    
                    <div class="help-block"></div>
                </div> -->               
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
        <div class="row">
                <!--<div class="col-xs-12 col-md-12">                                      
                    <?php
                    // Normal select with ActiveForm & model
                    $cityDesc = empty($model->locador2Propietario) ? '' : $model->locador2Propietario->apellido.", ".$model->locador2Propietario->nombre;
                    
                    echo $form->field($model, 'locador_2')->widget(Select2::classname(), [
                        //'data' => $cityDesc,
                        'initValueText' => $cityDesc,
                        'language' => 'es',
                        'options' => ['placeholder' => 'Seleccione el locador'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['propietarios/get-propietarios']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ],
                        'pluginEvents' => [
                            'change' => 'function(evt) {  
                                var data_id = $(this).val();                                
                                $.ajax({ method: "GET",
                                url: "' . \yii\helpers\Url::to(['propietarios/get-propietario-by-id']) . '",
                                data: { id: data_id }
                                }).done(function( data ) {                                    
                                    $("label[id=\'locador_2\']").html(data.results.apellido+", "+data.results.nombre);
                                    $("label[id=\'locador_2_dni\']").html(data.results.cuit);
                                    $("label[id=\'locador_2_domicilio\']").html(data.results.direccion);
                                    $("label[id=\'locador_2_localidad\']").html(data.results.localidad);
                                    $("label[id=\'locador_2_provincia\']").html(data.results.provincia);
                                    //$("label[id=\'locador_2_telefono\']").html(data.results.telefono);
                                    
                                });
                            }',
                            "select2:unselect" => 'function() { 
                                $("label[id=\'locador_2\']").html("");
                                $("label[id=\'locador_2_dni\']").html("");
                                $("label[id=\'locador_2_domicilio\']").html("");
                                $("label[id=\'locador_2_localidad\']").html("");
                                $("label[id=\'locador_2_provincia\']").html("");
                             }',
                        ],
                    ]);
                    ?>                    
                    <div class="help-block"></div>
                </div>-->       



                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_2_dni">Cuit</label>

                    <?php
                    $locador_2_dni = "";
                    if ($model->locador2Propietario) {
                        $locador_2_dni = $model->locador2Propietario->cuit;
                    }
                    echo '<label id="locador_2_dni" class="form-control" for="locador_2_dni">' . $locador_2_dni . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_2_domicilio">Domicilio</label>                                       
                    <?php
                    $locador_2_domicilio = "";
                    if ($model->locador2Propietario) {
                        $locador_2_domicilio = $model->locador2Propietario->direccion;
                    }
                    echo '<label id="locador_2_domicilio" class="form-control" for="locador_2_domicilio">' . $locador_2_domicilio . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_2_localidad">Localidad</label>                    
                    <?php
                    $locador_2_localidad = "";
                    if ($model->locador2Propietario) {
                        $locador_2_localidad = $model->locador2Propietario->localidad;
                    }
                    echo '<label id="locador_2_localidad" class="form-control" for="locador_2_localidad">' . $locador_2_localidad . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->                
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_2_provincia">Provincia</label>
                    <?php
                    $locador_2_provincia = "";
                    if ($model->locador2Propietario) {
                        $locador_2_provincia = $model->locador2Propietario->provincia;
                    }
                    echo '<label id="locador_2_provincia" class="form-control" for="locador_2_provincia">' . $locador_2_provincia . '</label>';
                    ?>                    
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_telefono">Telefono</label>                    
                    <?php
                    /*$locador_telefono = "";
                    if ($model->cliente) {
                        $locador_telefono = $model->cliente->TELEFONO;
                    }
                    echo '<label id="locador_telefono" class="form-control" for="locador_telefono">' . $locador_telefono . '</label>';*/
                    ?>
                    <div class="help-block"></div>
                </div>-->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">
        <div class="row">
                <div class="col-xs-12 col-md-12">                    
                    <?php
                    
                    $inquilino = empty($model->cliente) ? '' : $model->cliente->NOMBRE;

                    echo $form->field($model, 'cod_cliente')->widget(Select2::classname(), [
                        //'data' => $cityDesc,
                        'initValueText' => $inquilino,
                        'language' => 'es',
                        'options' => ['placeholder' => 'Seleccione el Inquilino I'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['a2-clientes/get-clientes']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ],
                        'pluginEvents' => [
                            'change' => 'function(evt) {  
                                var data_id = $(this).val();                                
                                $.ajax({ method: "GET",
                                url: "' . \yii\helpers\Url::to(['a2-clientes/get-cliente-by-id']) . '",
                                data: { id_cliente: data_id }
                                }).done(function( data ) {                                    
                                    $("label[id=\'inquilino\']").html(data.results.NOMBRE);
                                    $("label[id=\'inquilino_dni\']").html(data.results.CUIL);
                                    $("label[id=\'inquilino_domicilio\']").html(data.results.DIRECCION);
                                    $("label[id=\'inquilino_telefono\']").html(data.results.TELEFONO);                                    
                                    
                                });
                            }',

                            "select2:unselect" => 'function() { 
                                $("label[id=\'inquilino_dni\']").html("");
                               
                             }',
                        ],
                    ]);
                    ?>
                    
                    <?php $form->field($model, 'inquilino_1')->hiddenInput()->label(false);  ?>

                    <div class="help-block"></div>
                </div>       
                <div class="col-xs-12 col-md-12">
                    <label class="control-label" for="inquilino_dni">Cuil</label>

                  
                    <?php
                    $inquilino_cuil = "";
                    if ($model->cliente) {
                        $inquilino_cuil = $model->cliente->CUIL;
                    }                    
                    echo '<label id="inquilino_dni" class="form-control" for="inquilino_dni">' . $inquilino_cuil . '</label>';
                    ?>

                    <div class="help-block"></div>
                </div>
               <!-- <div class="col-xs-12 col-md-12">
                    <label class="control-label" for="inquilino_domicilio">Domicilio</label>
                    <?php
                    $inquilino_domicilio = "";
                    if ($model->cliente) {
                        $inquilino_domicilio = $model->cliente->DIRECCION;
                    }
                    echo '<label id="inquilino_domicilio" class="form-control" for="inquilino_domicilio">' . $inquilino_domicilio . '</label>';
                    ?>

                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="inquilino_localidad">Localidad</label>
                    <?php
                    $inquilino_localidad = "";
                    if ($model->cliente) {
                        $inquilino_localidad = $model->cliente->LOCALIDAD;
                    }
                    echo '<label id="inquilino_localidad" class="form-control" for="inquilino_localidad">' . $inquilino_localidad . '</label>';
                    ?>                    
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="inquilino_provincia">Provincia</label>
                    <?php
                    $inquilino_provincia = "";
                    if ($model->cliente) {
                        $inquilino_provincia = $model->cliente->PROVINCIA;
                    }
                    echo '<label id="inquilino_provincia" class="form-control" for="inquilino_provincia">' . $inquilino_provincia . '</label>';
                    ?>                    
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="inquilino_telefono">Telefono</label>

                    <?php
                    $inquilino_telefono = "";
                    if ($model->cliente) {
                        $inquilino_telefono = $model->cliente->TELEFONO;
                    }
                    echo '<label id="inquilino_telefono" class="form-control" for="inquilino_telefono">' . $inquilino_telefono . '</label>';
                    ?>                                        
                    <div class="help-block"></div>
                </div>-->
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
        <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?php
                    $cityDesc = empty($model->inquilino2) ? '' : $model->inquilino2->NOMBRE;

                    echo $form->field($model, 'inquilino_2')->widget(Select2::classname(), [
                        //'data' => $cityDesc,
                        'initValueText' => $cityDesc,
                        'language' => 'es',
                        'options' => ['placeholder' => 'Seleccione el Inquilino II'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['a2-clientes/get-clientes']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ],
                        'pluginEvents' => [
                            'change' => 'function(evt) {  
                                var data_id = $(this).val();                                
                                $.ajax({ method: "GET",
                                url: "' . \yii\helpers\Url::to(['a2-clientes/get-cliente-by-id']) . '",
                                data: { id_cliente: data_id }
                                }).done(function( data ) {                                    
                                    $("label[id=\'inquilino2\']").html(data.results.NOMBRE);
                                    $("label[id=\'inquilino2_dni\']").html(data.results.CUIL);
                                    $("label[id=\'inquilino2_domicilio\']").html(data.results.DIRECCION);
                                    $("label[id=\'inquilino2_telefono\']").html(data.results.TELEFONO);
                                    $("label[id=\'inquilino2_localidad\']").html(data.results.LOCALIDAD);
                                    $("label[id=\'inquilino2_provincia\']").html(data.results.PROVINCIA);
                                    
                                });
                            }',
                            
                            "select2:unselect" => 'function() { 
                                $("label[id=\'inquilino2_dni\']").html("");
                               
                             }',
                        ],
                    ]);
                    ?>
                    <div class="help-block"></div>
                </div>       
               <div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_dni">Cuil</label>
                    <?php
                    $inquilino2_cuil = "";
                    if ($model->inquilino2) {
                        $inquilino2_dni = $model->inquilino2->CUIL;
                    }
                    echo '<label id="inquilino2_dni" class="form-control" for="inquilino2_dni">' . $inquilino2_cuil . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_domicilio">Domicilio</label>
                    <?php
                    $inquilino2_domicilio = "";
                    if ($model->inquilino2) {
                        $inquilino2_domicilio = $model->inquilino2->DIRECCION;
                    }
                    echo '<label id="inquilino2_domicilio" class="form-control" for="inquilino2_domicilio">' . $inquilino2_domicilio . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_localidad">Localidad</label>
                    <?php
                    $inquilino2_localidad = "";
                    if ($model->inquilino2) {
                        $inquilino2_localidad = $model->inquilino2->LOCALIDAD;
                    }
                    echo '<label id="inquilino2_localidad" class="form-control" for="inquilino2_localidad">' . $inquilino2_localidad . '</label>';
                    ?> 
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="inquilino_provincia">Provincia</label>
                    <?php
                    $inquilino2_provincia = "";
                    if ($model->inquilino2) {
                        $inquilino2_provincia = $model->inquilino2->PROVINCIA;
                    }
                    echo '<label id="inquilino2_provincia" class="form-control" for="inquilino2_provincia">' . $inquilino2_provincia . '</label>';
                    ?>                    
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_telefono">Telefono</label>
                    <?php
                    $inquilino2_telefono = "";
                    if ($model->inquilino2) {
                        $inquilino2_telefono = $model->inquilino2->TELEFONO;
                    }
                    echo '<label id="inquilino2_telefono" class="form-control" for="inquilino2_telefono">' . $inquilino2_telefono . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
        <div class="row">
                <div class="col-xs-12 col-md-12">                    
                    <?php
                    $cityDesc = empty($model->garante) ? '' : $model->garante->NOMBRE;

                    echo $form->field($model, 'cod_garante')->widget(Select2::classname(), [
                        //'data' => $cityDesc,
                        'initValueText' => $cityDesc,
                        'language' => 'es',
                        'options' => ['placeholder' => 'Seleccione el Garante'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['a2-clientes/get-clientes']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ],
                        'pluginEvents' => [
                            'change' => 'function(evt) {  
                                var data_id = $(this).val();                                
                                $.ajax({ method: "GET",
                                url: "' . \yii\helpers\Url::to(['a2-clientes/get-cliente-by-id']) . '",
                                data: { id_cliente: data_id }
                                }).done(function( data ) {                                    
                                    $("label[id=\'garante\']").html(data.results.NOMBRE);
                                    $("label[id=\'garante_dni\']").html(data.results.CUIL);
                                    $("label[id=\'garante_domicilio\']").html(data.results.DIRECCION);
                                    $("label[id=\'garante_telefono\']").html(data.results.TELEFONO);
                                    $("label[id=\'garante_localidad\']").html(data.results.LOCALIDAD);
                                    $("label[id=\'garante_provincia\']").html(data.results.PROVINCIA);
                                    
                                });
                            }',
                            
                            "select2:unselect" => 'function() { 
                                $("label[id=\'garante_dni\']").html("");
                               
                             }',
                        ],
                    ]);
                    ?>
                    <div class="help-block"></div>
                </div>       
                <div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_dni">Cuil</label>
                    <?php
                    $garante_cuil = "";
                    if ($model->garante) {
                        $garante_dni = $model->garante->CUIL;
                    }
                    echo '<label id="garante_dni" class="form-control" for="garante_dni">' . $garante_cuil . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_domicilio">Domicilio</label>
                    <?php
                    $garante_domicilio = "";
                    if ($model->garante) {
                        $garante_domicilio = $model->garante->DIRECCION;
                    }
                    echo '<label id="garante_domicilio" class="form-control" for="garante_domicilio">' . $garante_domicilio . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_localidad">Localidad</label>
                    <?php
                    $garante_localidad = "";
                    if ($model->garante) {
                        $garante_localidad = $model->garante->LOCALIDAD;
                    }
                    echo '<label id="garante_localidad" class="form-control" for="garante_localidad">' . $garante_localidad . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="garante_provincia">Provincia</label>
                    <?php
                    $garante_provincia = "";
                    if ($model->garante) {
                        $garante_provincia = $model->garante->PROVINCIA;
                    }
                    echo '<label id="garante_provincia" class="form-control" for="garante_provincia">' . $garante_provincia . '</label>';
                    ?>                    
                    <div class="help-block"></div>
                </div>-->
                <!--<div class="col-xs-12 col-md-12">
                    <label class="control-label" for="locador_telefono">Telefono</label>
                    <?php
                    $garante_telefono = "";
                    if ($model->garante) {
                        $garante_telefono = $model->garante->TELEFONO;
                    }
                    echo '<label id="garante_telefono" class="form-control" for="garante_telefono">' . $garante_telefono . '</label>';
                    ?>
                    <div class="help-block"></div>
                </div>-->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <center><b>Detalles del Contrato</b></center>
            <hr/>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?php
                    /*if (!$model->isNewRecord) {
                        
                    } else {
                        ?>
                        <?= $form->field($model, 'fecha_ope')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                            'dateFormat' => 'dd/MM/yyyy',])->hint('Indique la fecha de Operacion. '); ?>
                    <?php } */?>

                    <?php
                    $arreglo_mes = [ '1' => '1', '2' => '2', '3' => '3', '4' => '4',
                        '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12',];
                    ?>
                    <?= $form->field($model, 'desde_mes')->dropDownList($arreglo_mes) ?>

                </div>   
                <div class="col-xs-12 col-md-6">                    
                    <?php
                    $arreglo = date('Y') - 5;
                    for ($index = 0; $index < 20; $index++) {
                        $arreglo_anio[$arreglo] = $arreglo;
                        $arreglo++;
                    }
                    ?>

                    <?= $form->field($model, 'desde_anio')->dropDownList($arreglo_anio) ?>
                </div> 
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?= $form->field($model, 'hasta_mes')->dropDownList($arreglo_mes) ?>
                </div>
                <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'hasta_anio')->dropDownList($arreglo_anio) ?>
                </div>
            </div>
                
            <div class="row">                
                <div class="col-xs-12 col-md-6">
                    <?= $form->field($model, 'tipo_contrato')->dropDownList([ 'LOCACION' => 'LOCACION', 'COMODATO' => 'COMODATO',], ['prompt' => '']) ?>
                </div>
                <div class="col-xs-12 col-md-6">
                    <?= $form->field($model, 'conv_desocup')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>
                </div>
                    
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                <?=
                    $form->field($model, 'fecha_firma_contrato')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                        
                        'dateFormat' => 'dd/MM/yyyy',
                    ])->hint('Indique fecha celebración del contrato. ');
                ?>
                </div>
                <div class="col-xs-12 col-md-6">
                <?=
                    $form->field($model, 'fecha_firma_convenio')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                        
                        'dateFormat' => 'dd/MM/yyyy',
                    ])->hint('Indique fecha celebración del convenio. ');
                    ?>
                </div>                    
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?php $arreglo_inmobiliarias = ArrayHelper::map(\app\models\A2Inmobiliarias::find()->orderBy('nombre_inmobiliaria')->all(), 'id_inmobiliaria', 'nombre_inmobiliaria'); ?>
                    <?= $form->field($model, 'id_inmobiliaria')->dropDownList($arreglo_inmobiliarias, ['prompt' => 'Seleccione la inmobiliaria']);
                    ?>
                </div>

                <div class="col-xs-12 col-md-6">                    
                    <?= $form->field($model, 'contrato_firmado')->dropDownList([ '1' => 'SI', '0' => 'NO',],['options' => 
                        [($model->contrato_firmado)?$model->contrato_firmado:0 => ['selected' => TRUE]],]) ?>
                </div>
            </div>
            <div class="row">
                <!--<div class="col-xs-12 col-md-4">                            
                    <?= $form->field($model, 'honorarios')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>
                </div>
                <div class="col-xs-12 col-md-4">
                    <?= $form->field($model, 'excento_monto')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-xs-12 col-md-4">
                    <?= $form->field($model, 'excento_cuotas')->textInput(['maxlength' => true]) ?>
                </div>-->
            </div>
            
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
                </div>
            </div>
            <!--<div id="div_representante" class="row">
                <div class="col-xs-12 col-md-6">
                    <?php /*= $form->field($model, 'firma_representante')->dropDownList([ '1' => 'SI', '0' => 'NO',]) */?>
                    <?php /*= $form->field($model, 'representante')->textInput()*/ ?>
                </div>
                <div class="col-xs-12 col-md-6">
                    <?php /*= $form->field($model, 'representante_cuit')->textInput()*/ ?>
                </div>
            </div>-->
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                <?php //= $form->field($model, 'dia_venc_mensual')->textInput(['readonly' => true, 'value' => $model->isNewRecord ? 10 : $model->dia_venc_mensual]) ?>

                    <?= $form->field($model, 'dia_venc_mensual')->textInput() ?>
                </div>    
                <div class="col-xs-12 col-md-6">
                    <?= $form->field($model, 'interes_dia_mora')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?= $form->field($model, 'destino_contrato')->dropDownList([ 'LOCAL COMERCIAL' => 'LOCAL COMERCIAL', 
                        'DOMESTICO' => 'DOMESTICO', 'OFICINAS' => 'OFICINAS',], ['prompt' => '']) ?>
                </div>
                <div class="col-xs-12 col-md-6">

                    <?= $form->field($model, 'excento')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>
                </div>
            </div>
            <div class="row">
                <div id="div_deposito_garantia" class=" d-none col-xs-12 col-md-4">
                <?php $order_statuses = array(1 => 'SI', 
                0 => 'NO'); ?>
                <?= $form->field($model, 'deposito_garantia')->dropDownList($order_statuses, ['value' => !empty($model->deposito_garantia) ? $model->deposito_garantia : 1]); ?>
                <?php //= $form->field($model, 'deposito_garantia')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>
                </div>

                <div class="col-xs-12 col-md-6">
                    <?= $form->field($model, 'deposito_monto')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-xs-12 col-md-6">                    
                <?php //= $form->field($model, 'some_field')->textInput(['readonly' => true, 'value' => $model->isNewRecord ? 'Your Value' : $model->some_field]) ?>    
                <?= $form->field($model, 'permite_pagos_pendientes')->dropDownList([ '1' => 'SI', '0' => 'NO',],['options' => [0 => ['selected' => TRUE]],]) ?>
                </div>
                
                <div class="col-xs-12 col-md-4">

                <?php //= $form->field($model, 'deposito_cuotas')->textInput(['readonly' => true, 'value' => $model->isNewRecord ? 'Null' : $model->deposito_cuotas]) ?>

                    <?php //= $form->field($model, 'deposito_cuotas')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?php //= $form->field($model, 'deposito_contrato_monto')->textInput(['maxlength' => true]) ?>
                </div>
                
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?= $form->field($model, 'tiene_expensas')->dropDownList([ '1' => 'SI', '0' => 'NO',],['options' => [0 => ['selected' => TRUE]],]) ?>
                </div>
                <div id="div_expensas" class="col-xs-12 col-md-6">  
                    <?php $tipo_expensas = ArrayHelper::map(\app\models\TipoExpensas::find()->where('id =12 OR id=13')->orderBy('descripcion')->all(), 'id', 'descripcion'); ?>                  
                    <?= $form->field($model, 'expensas')->checkboxList($tipo_expensas, 
                            ['separator' => '<br>']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?php 
                    if($model->isNewRecord){
                        $estados=['ACTIVO' => 'ACTIVO','PENDIENTE' => 'PENDIENTE', 'FINALIZADO' => 'FINALIZADO'];
                    }else{
                        $estados=['ACTIVO' => 'ACTIVO','PENDIENTE' => 'PENDIENTE','FINALIZADO' => 'FINALIZADO','ELIMINADO' => 'ELIMINADO'];
                    }
                    ?>
                    <?= $form->field($model, 'estado')->dropDownList($estados) ?>
                </div>
                <div class="col-xs-12 col-md-6">  
                <?= 
                    $form->field($model, 'estado_renovacion')->hiddenInput()->label(false);
                    //$form->field($model, 'estado_renovacion')->dropDownList([ 'RENOVADO' => 'RENOVADO', 'PENDIENTE' => 'PENDIENTE',]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php
        
        if(Yii::$app->request->get('renovar')==1){
            ?>
            <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-noticias/index'], ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
            <?php    
        }else{
        ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        <?php } ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div> 
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/a2-operacion-inmobiliarias/actualizar_form.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>