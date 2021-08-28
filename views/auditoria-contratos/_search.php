<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AuditoriaContratosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auditoria-contratos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'operacion_id') ?>

    <?= $form->field($model, 'fecha_contrato') ?>

    <?= $form->field($model, 'fecha_procesamiento') ?>

    <?= $form->field($model, 'estado_contrato') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
