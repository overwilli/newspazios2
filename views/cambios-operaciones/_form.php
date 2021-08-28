<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CambiosOperaciones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cambios-operaciones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'operacion_id')->hiddenInput()->label(false) ?>

    <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?php
                    $arreglo_mes = [ '1' => '1', '2' => '2', '3' => '3', '4' => '4',
                        '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12',];
                    ?>
                    <?php //= $form->field($model, 'desde_mes',['options'=>['readonly'=>TRUE]])->dropDownList($arreglo_mes) ?>
					<label class="control-label" for="cambiosoperaciones-desde_mes">Desde Mes</label><br/>
					<?php echo $model->desde_mes?>
                </div>   
                <div class="col-xs-12 col-md-6">                    
                    <?php
                    $arreglo = date('Y') - 5;
                    for ($index = 0; $index < 20; $index++) {
                        $arreglo_anio[$arreglo] = $arreglo;
                        $arreglo++;
                    }
                    ?>

                    <?php //= $form->field($model, 'desde_anio')->dropDownList($arreglo_anio) ?>
					<label class="control-label" for="cambiosoperaciones-desde_anio">Desde Año</label><br/>
					<?php echo $model->desde_anio?>
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
    
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
