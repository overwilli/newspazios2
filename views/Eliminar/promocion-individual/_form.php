<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PromocionIndividual */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promocion-individual-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'enlace')->textInput(['maxlength' => true])->hint('La direcci&oacute;n a la cual ir&aacute; el cliente al hacer click en la imagen. Use # si desea que no vaya a ningun lugar.') ?>
    
    <?= $form->field($model, 'imageFile')->fileInput()->hint('Imagen de la promoci&oacute;n') ?>
    
    <?= $form->field($model, 'texto_imagen')->textInput(['maxlength' => true])->hint('Este es el texto que visualizar&aacute; el cliente en caso que no se carguen las imag&eacute;n de la promoci&oacute;n.') ?>
    
    <?= $form->field($model, 'orden')->textInput(['maxlength' => true])->hint('El orden indica la posici&oacute;n ascedente en que se visualizar&aacute;n las imagenes. ') ?>

    <?= $form->field($model, 'estado')->dropDownList([ 'ACTIVO' => 'ACTIVO', 'INACTIVO' => 'INACTIVO', ],['options' =>[ 'ACTIVO' => ['Selected'=>'selected']],])->hint('El estado "Activo" indica que se enviar&aacute; en el mail, e "Inactivo" indica que no se enviar&aacute; en el mail.') ?>

    <?= $form->field($model, 'promociones_mails_id')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php
    if(!$model->isNewRecord){
        echo Html::img(\Yii::$app->params['directorio_promocion'].$model->url_image, ['alt' => '','width'=>'600px']);
    }
    ?>
</div>
