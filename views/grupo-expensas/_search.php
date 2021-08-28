<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoExpensasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupo-expensas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'grupo_id') ?>

    <?= $form->field($model, 'tipo_expensa_id') ?>

    <?= $form->field($model, 'mes') ?>

    <?= $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'importe') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
