<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Clientes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clientes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dni')->textInput() ?>

    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'barrio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_localidad')->textInput() ?>

    <?= $form->field($model, 'empresa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'empresa_direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'empresa_puesto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'empresa_antiguedad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono_pre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono_alternativo_pre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono_alternativo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular_pre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular_alternativo_pre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular_alternativo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email_alternativo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_temp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <?= $form->field($model, 'cumpleanios')->textInput() ?>

    <?= $form->field($model, 'fecha_actualizacion')->textInput() ?>

    <?= $form->field($model, 'updated_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
