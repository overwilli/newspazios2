<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OperacionesExpensasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operaciones-expensas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'operacion_id') ?>

    <?= $form->field($model, 'tipo_expensas_id') ?>

    <?= $form->field($model, 'inmuebles_id') ?>

    <?= $form->field($model, 'mes') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'importe') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'comprobante_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
