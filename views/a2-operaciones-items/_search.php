<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2OperacionesItemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-operaciones-items-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_item') ?>

    <?= $form->field($model, 'id_operacion') ?>

    <?= $form->field($model, 'anio') ?>

    <?= $form->field($model, 'mes') ?>

    <?= $form->field($model, 'id_factura') ?>

    <?php // echo $form->field($model, 'monto') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
