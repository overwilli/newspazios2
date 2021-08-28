<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Gastos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gastos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inmueble_id')->hiddenInput()->label(false)?>
	<?= $form->field($model, 'operacion_id')->hiddenInput()->label(false)?>

    
	<?=
            $form->field($model, 'fecha')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ])->hint('Indique la fecha. ')
            ?>

    <?= $form->field($model, 'importe')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'estado')->dropDownList([ 'PENDIENTE' => 'PENDIENTE', 'PAGADO' => 'PAGADO', ]) ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
