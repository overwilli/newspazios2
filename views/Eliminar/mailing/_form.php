<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mailing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mailing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'archivo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo')->dropDownList([ 1 => 'Anual',2=>'Mensual',3=>utf8_encode('Fecha Única'),4=>utf8_encode('Cumpleaños')]) ?>    

    <?= $form->field($model, 'fecha_envio')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ]) ?>    

    <?= $form->field($model, 'estado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
