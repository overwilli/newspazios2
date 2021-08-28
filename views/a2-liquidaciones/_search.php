<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2LiquidacionesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-liquidaciones-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php //= $form->field($model, 'id_liquidacion') ?>

    <?php //= $form->field($model, 'id_operacion') ?>

    <?php //= $form->field($model, 'liq_anio') ?>

    <?php //= $form->field($model, 'liq_mes') ?>

    <?php //= $form->field($model, 'fecha_pago') ?>

    <?php // echo $form->field($model, 'monto_pagado') ?>

    <?php // echo $form->field($model, 'monto_intereses') ?>

    <?php // echo $form->field($model, 'usuario') ?>

    <?php // echo $form->field($model, 'timestamp') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php echo $form->field($model, 'tipo_filtro')->dropDownList([ ''=>'TODOS', 'sin_vencer' => 'SIN VENCER', 
    'vencidas' => 'VENCIDAS','vencidas_mas_mes'=>'VENCIDAS MAS DE UN MES' ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Limpiar', ['class' => 'btn btn-default']) ?>
    </div>
    

    <?php ActiveForm::end(); ?>

</div>
