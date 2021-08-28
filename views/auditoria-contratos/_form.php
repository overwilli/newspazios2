<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AuditoriaContratos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auditoria-contratos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'operacion_id')->textInput() ?>

    <?= $form->field($model, 'fecha_contrato')->textInput() ?>

    <?= $form->field($model, 'fecha_procesamiento')->textInput() ?>

    <?= $form->field($model, 'estado_contrato')->dropDownList([ 'RENOVADO' => 'RENOVADO', 'ELIMINADO' => 'ELIMINADO', 'PENDIENTE' => 'PENDIENTE', 'NUEVO' => 'NUEVO', 'ACTIVO' => 'ACTIVO', 'FINALIZADO' => 'FINALIZADO', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
