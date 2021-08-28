<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;


/* @var $this yii\web\View */
/* @var $model app\models\A2NoticiasImagenes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-noticias-imagenes-form">


<?php
    $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>


<?php echo $form->field($model, 'image')->widget(FileInput::classname(), ['options' => ['accept' => 'image/*'],

]); ?>

    <?php //= $form->field($model, 'id_noticia')->hiddenInput()->label(false) ?>
    
    <?php //= $form->field($model, 'imageFile[]')->fileInput(['multiple' => true])->hint('Imagen') ?>


    

    <div class="form-group">
        <?php     echo Html::submitButton('Submit', ['class'=>'btn btn-primary']); ?>
        <?php //= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',null, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
