<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2ParametrosGlobales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-parametros-globales-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_parametro')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'empresa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ultimo_recibo_x')->textInput() ?>

    <?= $form->field($model, 'ultimo_recibo_c')->textInput() ?>

    <?= $form->field($model, 'ultimo_recibo_d')->textInput() ?>

    <?= $form->field($model, 'impresora_host')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'impresora_puerto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'impresora_modelo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'impresora_firmware')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'impresora_directorio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comprobante_pago_unico')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
