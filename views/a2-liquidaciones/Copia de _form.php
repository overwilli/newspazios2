<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-liquidaciones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_operacion')->textInput() ?>

    <?= $form->field($model, 'liq_anio')->textInput() ?>

    <?= $form->field($model, 'liq_mes')->textInput() ?>

    <?= $form->field($model, 'fecha_pago')->textInput() ?>

    <?= $form->field($model, 'monto_pagado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'monto_intereses')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'timestamp')->textInput() ?>

    <?= $form->field($model, 'estado')->dropDownList([ 'ACTIVO' => 'ACTIVO', 'PENDIENTE' => 'PENDIENTE', 'PREIMPRESO' => 'PREIMPRESO', 'PAGADO' => 'PAGADO', 'ELIMINADO' => 'ELIMINADO', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
