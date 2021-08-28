<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GestionCobranzas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gestion-cobranzas-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'inmueble_id')->hiddenInput()->label(false)?>
	<?= $form->field($model, 'operacion_id')->hiddenInput()->label(false)?>
	<?= $form->field($model, 'cliente_id')->hiddenInput()->label(false)?>
    
	<?=
            $form->field($model, 'fecha_notificacion')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ])->hint('Indique la fecha. ')
            ?>
    
	<?= $form->field($model, 'nivel')->dropDownList([ '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', ]) ?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);return False;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
