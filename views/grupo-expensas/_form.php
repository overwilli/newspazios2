<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoExpensas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupo-expensas-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php $arreglo_grupo = ArrayHelper::map(\app\models\A2Grupos::find()->orderBy('descripcion')->all(), 'id_grupo', 'descripcion'); ?>
    <?= $form->field($model, 'grupo_id')->dropDownList($arreglo_grupo, ['options' => ['prompt'=>'seleccione']]);
                    ?>

    <?php $arreglo_tipo_expensas = ArrayHelper::map(\app\models\TipoExpensas::find()->orderBy('descripcion')->all(), 'id', 'descripcion'); ?>
    <?= $form->field($model, 'tipo_expensa_id')->dropDownList($arreglo_tipo_expensas, ['options' => ['prompt'=>'seleccione']]);
                    ?>


<?php
    $arreglo_mes = [ '1' => '1', '2' => '2', '3' => '3', '4' => '4',
                    '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12',];
                ?>
    <?= $form->field($model, 'mes')->dropDownList($arreglo_mes) ?> 

    <?php
                $arreglo = date('Y');
                for ($index = 0; $index < 36; $index++) {
                    $arreglo_anio[$arreglo] = $arreglo;
                    $arreglo++;
                }
                ?>            
    <?= $form->field($model, 'year')->dropDownList($arreglo_anio) ?>

    <?= $form->field($model, 'importe')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',null, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
