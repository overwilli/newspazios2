<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConfigMorosidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-morosidad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dias_deuda')->textInput() ?>

    <?= $form->field($model, 'mensaje')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
