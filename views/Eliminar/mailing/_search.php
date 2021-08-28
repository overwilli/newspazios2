<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MailingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mailing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'subject') ?>

    <?= $form->field($model, 'archivo') ?>

    <?= $form->field($model, 'tipo') ?>

    <?= $form->field($model, 'fecha_envio') ?>

    <?php // echo $form->field($model, 'ultimo_envio') ?>

    <?php // echo $form->field($model, 'estado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
