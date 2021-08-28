<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model app\models\InmueblePorcentajePropietario */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inmueble-porcentaje-propietario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inmueble_id')->hiddenInput()->label(false) ?>

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
    ]);
    ?>
    
    <?= $form->field($model, 'porcentaje')->textInput() ?>  

    <?= $form->field($model, 'estado')->dropDownList([ 'ACTIVO' => 'ACTIVO', 'ELIMINADO' => 'ELIMINADO', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
