<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PromocionesMailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promociones-mails-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'asunto') ?>

    <?= $form->field($model, 'fecha_creacion') ?>

    <?= $form->field($model, 'fecha_envio') ?>

    <?= $form->field($model, 'condiciones') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
