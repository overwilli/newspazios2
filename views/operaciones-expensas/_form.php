<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\OperacionesExpensas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operaciones-expensas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'operacion_id')->hiddenInput()->label(false) ?>

    <?php $arreglo_tipo_expensas = ArrayHelper::map(\app\models\TipoExpensas::find()->orderBy('descripcion')->all(), 'id', 'descripcion'); ?>
    <?= $form->field($model, 'tipo_expensas_id')->dropDownList($arreglo_tipo_expensas, ['options' => ['prompt'=>'seleccione']]);
                    ?>

    <?= $form->field($model, 'inmuebles_id')->hiddenInput()->label(false) ?>

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
    
	<div class="row">
		<div class="col-xs-12 col-md-12">  
			<?= $form->field($model, 'importe')->textInput(['maxlength' => true]) ?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 col-md-12">   
            <?php if (Yii::$app->user->identity->permisos == "administrador" || Yii::$app->user->identity->permisos == "intermedio") { ?>
                <?= $form->field($model, 'estado_reg')->dropDownList([ 'ACTIVO' => 'ACTIVO', 'PENDIENTE' => 'PENDIENTE', 'ELIMINADO' => 'ELIMINADO',]) ?>
                <?php
            }else{
                echo $form->field($model, 'estado_reg')->hiddenInput(['value'=> 'PENDIENTE'])->label(false);
            }
            ?>
        </div>
	</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',null, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
