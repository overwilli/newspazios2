<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InmueblesPropietariosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inmuebles-propietarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'inmueble_id') ?>

    <?= $form->field($model, 'propietario_id') ?>

    <?= $form->field($model, 'comision') ?>

    <?= $form->field($model, 'fecha_carga') ?>

    <?php // echo $form->field($model, 'fecha_actualizacion') ?>

    <?php // echo $form->field($model, 'usuario_carga') ?>

    <?php // echo $form->field($model, 'usuario_actualizacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
