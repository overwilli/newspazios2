<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ANoticiasUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="anoticias-usuarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nikname')->textInput(['maxlength' => true]) ?>

    <?php
    if ($model->isNewRecord) {
        echo $form->field($model, 'password')->passwordInput(['maxlength' => true]);
    }
    ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'sitio')->textInput() ?>

    <?= $form->field($model, 'permisos')->dropDownList([ 'administrador' => 'administrador', 'intermedio' => 'intermedio', 'operador' => 'operador',]) ?>

    <?php //= $form->field($model, 'timestamp')->textInput() ?>

    <?= $form->field($model, 'numero_caja')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
