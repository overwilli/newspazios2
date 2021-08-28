<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model app\models\InmueblesPropietarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inmuebles-propietarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inmueble_id')->hiddenInput()->label(false); ?>

    <?php // $form->field($model, 'propietario_id')->textInput() ?>
    <?php
    $cityDesc = empty($model->propietario) ? '' : $model->propietario->apellido.", ".$model->propietario->nombre;
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
<?= $form->field($model, 'comision')->textInput() ?>

<!--<? //= $var = [ 0 => 'ELIMINADO', 1 => 'ACTIVO'];?>-->

<!-- <? //= $form->field($model, 'estado')->dropDownList($var, ['prompt' => 'Seleccione Uno' ]); ?>-->


<?= $form->field($model, 'porcentaje')->textInput(['readonly' => true, 'value' => $model->isNewRecord ? 100 : $model->porcentaje]) ?>

<?php //= $form->field($model, 'porcentaje')->textInput() ?> 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',null, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
