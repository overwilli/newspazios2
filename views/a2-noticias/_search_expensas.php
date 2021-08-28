<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2NoticiasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-noticias-search">

    <?php $form = ActiveForm::begin([
        'action' => ['a2-noticias/expensas'],
        'method' => 'get',
    ]); ?>

    <?php //$form->field($model, 'id_noticia') ?>

    <?php //$form->field($model, 'fecha') ?>

    <?php //$form->field($model, 'seccion') ?>

    <?php //$form->field($model, 'pesoseccion') ?>

    <?php //$form->field($model, 'antetitulo') ?>

    <?php // echo $form->field($model, 'titulo') ?>

    <?php // echo $form->field($model, 'bajada') ?>

    <?php // echo $form->field($model, 'cuerpocompleto') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'fotopie') ?>

    <?php // echo $form->field($model, 'clicks') ?>

    <?php // echo $form->field($model, 'autor') ?>

    <?php // echo $form->field($model, 'editor') ?>

    <?php // echo $form->field($model, 'timestamp') ?>

    <?php // echo $form->field($model, 'fechatimestamp') ?>

    <?php // echo $form->field($model, 'fechacarga') ?>

    <?php // echo $form->field($model, 'operacion') ?>

    <?php // echo $form->field($model, 'alq_vendida') ?>

    <?php // echo $form->field($model, 'id_estado') ?>

    <?php // echo $form->field($model, 'id_grupo') ?>

    <?php // echo $form->field($model, 'direccion') ?>

    <?php // echo $form->field($model, 'precio') ?>

    <?php // echo $form->field($model, 'ambientes') ?>

    <?php // echo $form->field($model, 'sup_cubierta') ?>

    <?php // echo $form->field($model, 'sup_terreno') ?>

    <?php // echo $form->field($model, 'habitaciones') ?>

    <?php // echo $form->field($model, 'dormitorios') ?>

    <?php // echo $form->field($model, 'banios') ?>

    <?php // echo $form->field($model, 'conv_desocup') ?>

    <?php // echo $form->field($model, 'frente') ?>

    <?php // echo $form->field($model, 'fondo') ?>

    <?php // echo $form->field($model, 'padroniibb') ?>

    <?php // echo $form->field($model, 'padronaguas') ?>

    <?php // echo $form->field($model, 'padronmunicipal') ?>

    <?php // echo $form->field($model, 'provincia_id') ?>

    <?php // echo $form->field($model, 'localidad_id') ?>
    
    <?php echo $form->field($model, 'tipo_filtro')->dropDownList([ 'por_vencer' => 'POR VENCER', 'administradas' => 'ADMINISTRADAS', 
                'disponibles' => 'DISPONIBLES', 'renovacion_pendientes' => 'RENOVACION PENDIENTE',], ['prompt' => 'TODOS']) ?>

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Limpiar', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
