<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoExpensas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-expensas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
