<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model app\models\A2Clientes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-clientes-form">

<?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?php echo $form->field($model, 'cliente_id')->hiddenInput(['maxlength' => true])->label(FALSE) ?>
			<?= $form->field($model, 'apellido_nombre')->hiddenInput(['maxlength' => true])->label(FALSE) ?>
            <?php
                    // Normal select with ActiveForm & model
            //$cityDesc = empty($model->cliente) ? '' : $model->cliente->NOMBRE;

            echo $form->field($model, 'liquidacion_id')->widget(Select2::classname(), [
                //'data' => $cityDesc,
                'initValueText' => "",
                'language' => 'es',
                'options' => ['placeholder' => 'Seleccione el Cliente - Liq.'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['nota-credito/get-liquidacion']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
                'pluginEvents' => [
                    'change' => 'function(evt) {  
                        var data_id = $(this).val();                                
                        $.ajax({ method: "GET",
                        url: "' . \yii\helpers\Url::to(['nota-credito/get-liquidacion-by-id']) . '",
                        data: { liquidacion_id: data_id }
                        }).done(function( data ) {    
                            $("#notacredito-cliente_id").val(data.results.cliente_id);     
							$("#notacredito-apellido_nombre").val(data.results.apellido_nombre);     
							
                            $("#notacredito-dni").val(data.results.dni);
                            $("#notacredito-cuil").val(data.results.cuil);
                            $("#notacredito-direccion").val(data.results.direccion);                           
                            
                        });
                    }',
                ],
            ]);
            ?>
        </div>
        <div class="col-xs-12 col-md-6">
            
        </div>
    </div>    
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'dni')->textInput(['maxlength' => true]) ?>
            
        </div>
    </div>    
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'cuil')->textInput(['maxlength' => true]) ?>
        </div>
    </div>    
    <div class="row">
        <div class="col-xs-12 col-md-6">
        <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
        <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
        <?= $form->field($model, 'monto')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">        
        <?= Html::submitButton('Grabar', ['class' =>  'btn btn-success' ]) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
