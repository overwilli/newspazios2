<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mailing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mailing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>      

    <?=
    $form->field($model, 'estado')->dropDownList([ 1 => 'Habilitado', 0 => 'Deshabilitado'], ['options' =>
        [
            $model->estado => ['Selected' => true]
        ]
    ]);
    ?>

        <?= $form->field($model, 'imageFile')->fileInput()->hint('Imagen de la plantilla') ?>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php
    if (!$model->isNewRecord) {
        echo Html::img(\Yii::$app->params['directorio_templates'] . $model->url_image, ['alt' => '', 'width' => '600px']);
    }
    ?>

<?php ActiveForm::end(); ?>

</div>
