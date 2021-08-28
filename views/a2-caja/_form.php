<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2Caja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-caja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inicio_fecha')->textInput() ?>

    <?= $form->field($model, 'inicio_hora')->textInput() ?>

    <?= $form->field($model, 'inicio_usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inicio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'caja_final')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'caja_dia_siguiente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rendicion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sobrante_faltante')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'caja_cierre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cierre_fecha')->textInput() ?>

    <?= $form->field($model, 'cierre_hora')->textInput() ?>

    <?= $form->field($model, 'cierre_usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cobranzas_efectivo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ingresos_varios')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cheques')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'intereses_mora')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'depositos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'retenciones')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gastos_varios')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supermercado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'timestamp')->textInput() ?>

    <?= $form->field($model, 'numero_caja')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
