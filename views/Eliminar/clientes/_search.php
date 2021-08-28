<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClientesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clientes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'dni') ?>

    <?= $form->field($model, 'direccion') ?>

    <?= $form->field($model, 'barrio') ?>

    <?= $form->field($model, 'id_localidad') ?>

    <?php // echo $form->field($model, 'empresa') ?>

    <?php // echo $form->field($model, 'empresa_direccion') ?>

    <?php // echo $form->field($model, 'empresa_puesto') ?>

    <?php // echo $form->field($model, 'empresa_antiguedad') ?>

    <?php // echo $form->field($model, 'telefono_pre') ?>

    <?php // echo $form->field($model, 'telefono') ?>

    <?php // echo $form->field($model, 'telefono_alternativo_pre') ?>

    <?php // echo $form->field($model, 'telefono_alternativo') ?>

    <?php // echo $form->field($model, 'celular_pre') ?>

    <?php // echo $form->field($model, 'celular') ?>

    <?php // echo $form->field($model, 'celular_alternativo_pre') ?>

    <?php // echo $form->field($model, 'celular_alternativo') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'email_alternativo') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'password_temp') ?>

    <?php // echo $form->field($model, 'hash') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'cumpleanios') ?>

    <?php // echo $form->field($model, 'fecha_actualizacion') ?>

    <?php // echo $form->field($model, 'updated_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
