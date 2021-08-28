<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-liquidaciones-form">
    <?php
    if (!empty($mensaje)) {
        ?>
        <div class="alert alert-success alert-dismissible">            
            <h4><i class="icon fa fa-check"></i> Informaci&oacute;n!</h4>
    <?php echo $mensaje; ?>
        </div>
        <p>
        <?= Html::a('Volver', ['index'], ['class' => 'btn btn-info']) ?>
        </p>
        <?php
    } else {
        ?>

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <?php
                $mes_actual=(int)date('m');
                $arreglo_mes = [ '1' => '1', '2' => '2', '3' => '3', '4' => '4',
                    '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12',];
                ?>
    <?= $form->field($model, 'mes')->dropDownList($arreglo_mes,['options' => [$mes_actual => ['selected' => TRUE]],]) ?>

            </div>
            <div class="col-xs-12 col-md-6">
                <?php
                $arreglo = date('Y');
                for ($index = 0; $index < 36; $index++) {
                    $arreglo_anio[$arreglo] = $arreglo;
                    $arreglo++;
                }
                ?>            
    <?= $form->field($model, 'anio')->dropDownList($arreglo_anio) ?>

            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <?php
                $arreglo_inmuebles = ArrayHelper::map(\app\models\A2OperacionesInmobiliarias::ObtenerInmueblesConContratosActivosPendiente(), 'id_operacion_inmobiliaria', 'direccion');
                //echo $form->field($model, 'id_operacion')->dropDownList($arreglo_inmuebles, ['options' => [1 => ['selected' => TRUE]],]);

                echo $form->field($model, 'id_operacion')->widget(Select2::classname(), [
                    'data' => $arreglo_inmuebles,
                    'language' => 'es',
                    'options' => ['placeholder' => 'Seleccione un inmueble'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-xs-12 col-md-6">


            </div>
        </div>


        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <?php
    }
    ?>
</div>
