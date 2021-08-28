<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidacionpagadas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="liquidacionpagadas-form">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode("Nueva Orden de Pago") ?></h3>
        </div>
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>

            <?php
            /*$form->field($model, 'fecha')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ])->hint('Indique la fecha . ')*/
            ?>

            <?php
            $cityDesc = empty($model->propietario) ? '' : $model->propietario->apellido . ", " . $model->propietario->nombre;
            echo $form->field($model, 'propietario_id')->widget(Select2::classname(), [
                //'data' => $cityDesc,
                'initValueText' => $cityDesc,
                'language' => 'es',
                'options' => ['placeholder' => 'Seleccione el propietario'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['propietarios/get-propietarios']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
//        'pluginEvents' => [
//            'change' => 'function(evt) {  
//                                var data_id = $(this).val();                                
//                                $.ajax({ method: "GET",
//                                url: "' . \yii\helpers\Url::to(['a2-clientes/get-cliente-by-id']) . '",
//                                data: { id_cliente: data_id }
//                                }).done(function( data ) {                                    
//                                    $("label[id=\'locador\']").html(data.results.NOMBRE);
//                                    $("label[id=\'locador_dni\']").html(data.results.DNI);
//                                    $("label[id=\'locador_domicilio\']").html(data.results.DIRECCION);
//                                    $("label[id=\'locador_telefono\']").html(data.results.TELEFONO);
//                                    
//                                });
//                            }',
//        ],
            ]);
            ?>

            <!--
            <?= $form->field($model, 'total_cobrado')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($model, 'interes_mora')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($model, 'comision')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($model, 'iva')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($model, 'gastos')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($model, 'estado')->dropDownList([ 'Nuevo' => 'Nuevo', 'Carga' => 'Carga', 'Cerrado' => 'Cerrado', 'Pagado' => 'Pagado', 'Anulado' => 'Anulado',], ['prompt' => '']) ?>
        
            <?= $form->field($model, 'fecha_creacion')->textInput() ?>
        
            <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>
            -->
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
