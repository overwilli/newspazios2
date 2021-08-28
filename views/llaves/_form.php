<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Llaves */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="llaves-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inmueble_id')->hiddenInput()->label(false); ?>

    <?php 
    if($model->isNewRecord){
    ?>
        <?= $form->field($model, 'numero_llave')->textInput(['maxlength' => true]) ?>
        
        <?php $arreglo_inmobiliarias = ArrayHelper::map(\app\models\A2Inmobiliarias::find()->orderBy('nombre_inmobiliaria')->all(), 'id_inmobiliaria', 'nombre_inmobiliaria'); ?>
        <?= $form->field($model, 'inmobiliaria_id')->dropDownList($arreglo_inmobiliarias, ['options' => [1 => ['selected' => TRUE]],]);
        ?>

        
        <?php /*=
                $form->field($model, 'fecha_solicitud')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                    //'language' => 'ru',
                    'dateFormat' => 'dd/MM/yyyy',
                ])->hint('Indique la fecha. ')*/
                ?>

        <?php //= $form->field($model, 'tipo_solicitud')->dropDownList([ 'PRESTAMO' => 'PRESTAMO', 'DEVOLUCION' => 'DEVOLUCION',]) ?>    

    <?php 
    }
    ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>    


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',null, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
