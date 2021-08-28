<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2Caja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-caja-form">

    <?php $form = ActiveForm::begin(); ?>

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Inicializar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
