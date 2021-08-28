<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2ClientesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-clientes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'DNI') ?>

    <?= $form->field($model, 'NOMBRE') ?>

    <?= $form->field($model, 'NOMBRE_FANTASIA') ?>

    <?= $form->field($model, 'DIRECCION') ?>

    <?php // echo $form->field($model, 'LOCALIDAD') ?>

    <?php // echo $form->field($model, 'BARRIO') ?>

    <?php // echo $form->field($model, 'PROVINCIA') ?>

    <?php // echo $form->field($model, 'TELEFONO') ?>

    <?php // echo $form->field($model, 'TELEF2') ?>

    <?php // echo $form->field($model, 'TELEF3') ?>

    <?php // echo $form->field($model, 'EMAIL') ?>

    <?php // echo $form->field($model, 'NRO_CUENTA') ?>

    <?php // echo $form->field($model, 'CUIL') ?>

    <?php // echo $form->field($model, 'EST_CIVIL') ?>

    <?php // echo $form->field($model, 'FECNAC') ?>

    <?php // echo $form->field($model, 'PRIM_NUPCIAS') ?>

    <?php // echo $form->field($model, 'DNI_CONYUGE') ?>

    <?php // echo $form->field($model, 'NOMBRE_CONYUGE') ?>

    <?php // echo $form->field($model, 'CUIL_CONYUGE') ?>

    <?php // echo $form->field($model, 'OBSERVACIONES') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
