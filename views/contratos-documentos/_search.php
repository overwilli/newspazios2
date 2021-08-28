<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ContratosDocumentosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contratos-documentos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'operacion_inmobiliaria_id') ?>

    <?= $form->field($model, 'texto') ?>

    <?= $form->field($model, 'estado') ?>

    <?= $form->field($model, 'usuario_create') ?>

    <?php // echo $form->field($model, 'time_create') ?>

    <?php // echo $form->field($model, 'usuario_update') ?>

    <?php // echo $form->field($model, 'time_update') ?>

    <?php // echo $form->field($model, 'plantilla_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
