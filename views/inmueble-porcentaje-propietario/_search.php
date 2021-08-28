<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InmueblePorcentajePropietarioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inmueble-porcentaje-propietario-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'inmueble_id') ?>

    <?= $form->field($model, 'porcentaje') ?>

    <?= $form->field($model, 'propietario_id') ?>

    <?= $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
