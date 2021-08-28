<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2CajaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-caja-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_caja') ?>

    <?= $form->field($model, 'inicio_fecha') ?>

    <?= $form->field($model, 'inicio_hora') ?>

    <?= $form->field($model, 'inicio_usuario') ?>

    <?= $form->field($model, 'inicio') ?>

    <?php // echo $form->field($model, 'caja_final') ?>

    <?php // echo $form->field($model, 'caja_dia_siguiente') ?>

    <?php // echo $form->field($model, 'rendicion') ?>

    <?php // echo $form->field($model, 'sobrante_faltante') ?>

    <?php // echo $form->field($model, 'caja_cierre') ?>

    <?php // echo $form->field($model, 'cierre_fecha') ?>

    <?php // echo $form->field($model, 'cierre_hora') ?>

    <?php // echo $form->field($model, 'cierre_usuario') ?>

    <?php // echo $form->field($model, 'cobranzas_efectivo') ?>

    <?php // echo $form->field($model, 'ingresos_varios') ?>

    <?php // echo $form->field($model, 'cheques') ?>

    <?php // echo $form->field($model, 'intereses_mora') ?>

    <?php // echo $form->field($model, 'depositos') ?>

    <?php // echo $form->field($model, 'retenciones') ?>

    <?php // echo $form->field($model, 'gastos_varios') ?>

    <?php // echo $form->field($model, 'supermercado') ?>

    <?php // echo $form->field($model, 'timestamp') ?>

    <?php // echo $form->field($model, 'numero_caja') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
