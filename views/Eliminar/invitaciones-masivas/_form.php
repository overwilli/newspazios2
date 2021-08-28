<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvitacionesMasivas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invitaciones-masivas-form">

    <?php $form = ActiveForm::begin(); ?>

   

    <?= $form->field($model, 'fecha_creacion')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ]) ?>

    <?= $form->field($model, 'fecha_envio')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ]) ?>

    <?= $form->field($model, 'estado')->dropDownList([ 'NUEVO' => 'NUEVO', 'ENVIANDO' => 'ENVIANDO', 'ENVIADO' => 'ENVIADO',], ['options' => [ 'NUEVO' => ['Selected' => 'selected']],])
                    ->hint('El estado "Nuevo" indica que el mail esta en dise&ntilde;o, "Enviando" indica que el mail se esta enviando a los clientes, "Enviado" indica que el mail se termino de enviar a todos los clientes.') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
