<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LiquidacionpagadasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="liquidacionpagadas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'propietario_id') ?>

    <?= $form->field($model, 'total_cobrado') ?>

    <?= $form->field($model, 'interes_mora') ?>

    <?php // echo $form->field($model, 'comision') ?>

    <?php // echo $form->field($model, 'iva') ?>

    <?php // echo $form->field($model, 'gastos') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'usuario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
