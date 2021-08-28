<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GestionCobranzasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gestion-cobranzas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'hora') ?>

    <?= $form->field($model, 'cliente_id') ?>

    <?= $form->field($model, 'inmueble_id') ?>

    <?php // echo $form->field($model, 'operacion_id') ?>

    <?php // echo $form->field($model, 'nivel') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
