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

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?php $arreglo_grupo = ArrayHelper::map(\app\models\A2Grupos::find()->orderBy('descripcion')->all(), 'id_grupo', 'descripcion'); ?>
            <?= $form->field($model, 'grupo_id')->dropDownList($arreglo_grupo, ['options' => ['prompt'=>'seleccione']]);
                    ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3 col-md-3">
        <?php $arreglo_tipo_expensas = ArrayHelper::map(\app\models\TipoExpensas::find()->orderBy('descripcion')->all(), 'id', 'descripcion'); ?>
        <?= $form->field($model, 'tipo_expensa_id')->dropDownList($arreglo_tipo_expensas, ['options' => ['prompt'=>'seleccione']]);
                    ?>
        </div>
        <div class="col-xs-3 col-md-3">
            <?php
            $arreglo_mes = [ '1' => '1', '2' => '2', '3' => '3', '4' => '4',
                    '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12',];
                ?>
            <?= $form->field($model, 'mes')->dropDownList($arreglo_mes) ?> 
        </div>
        <div class="col-xs-3 col-md-2">
            <?php
                $arreglo = date('Y');
                for ($index = 0; $index < 36; $index++) {
                    $arreglo_anio[$arreglo] = $arreglo;
                    $arreglo++;
                }
                ?>
         
            <?= $form->field($model, 'year')->dropDownList($arreglo_anio) ?>
        </div>
        <div class="col-xs-3 col-md-2">
            <?= $form->field($model, 'importe')->textInput(['maxlength' => true,'class'=>['solo_numeros','form-control']]) ?>
        </div>
        <div class="col-xs-3 col-md-2">
        <label class="control-label">&nbsp;</label>
            <?= Html::a('<i class="fa fa-plus"></i>',null, ['id'=>'id_add_expensa',
                'class' => 'btn btn-info',]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
        <table id="tabla_expensas" class="table table-bordered">
                <tbody><tr>
                  <th style="width: 10px">#</th>
                  <th>Expensa</th>
                  <th>Periodo</th>
                  <th style="width: 40px">Importe</th>
                </tr>
                
               
                
                
              </tbody></table>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',null, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
