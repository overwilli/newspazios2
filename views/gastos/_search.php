<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GastosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gastos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'inmueble_id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'importe') ?>

    <?= $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
