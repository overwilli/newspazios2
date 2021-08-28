<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\A2OperacionesItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-operaciones-items-form">

    <?php $form = ActiveForm::begin(); ?>
	<?php
	if(isset($errores)){ 
	?>
	<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                <?php print_r($errores); ?>
              </div>
	<?php
	}
	?>
	
	
    <?= $form->field($model, 'id_operacion')->hiddenInput()->label(false) ?>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?php
            $arreglo = date('Y') - 5;
            for ($index = 0; $index < 20; $index++) {
                $arreglo_anio[$arreglo] = $arreglo;
                $arreglo++;
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?= $form->field($model, 'anio')->dropDownList($arreglo_anio) ?>


            <?php
            $arreglo_mes = [ '1' => '1', '2' => '2', '3' => '3', '4' => '4',
                '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12',];
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?= $form->field($model, 'mes')->dropDownList($arreglo_mes) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?php
            $arreglo_tipos = ArrayHelper::map(\app\models\A2TiposFacturacion::find()->orderBy('descripcion')->all(), 'id_facturacion', 'descripcion');
            echo $form->field($model, 'id_factura')->dropDownList($arreglo_tipos, ['options' => [1 => ['selected' => TRUE]],]);
            ?>
        </div>
    </div>    
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?= $form->field($model, 'monto')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
	<?php
	if($model->isNewRecord){
		?>
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<?= $form->field($model, 'cantidad_meses')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
		<?php
	}
	?>
	<div class="row">
		<div class="col-xs-12 col-md-12">   
            <?php if (Yii::$app->user->identity->permisos == "administrador" || Yii::$app->user->identity->permisos == "intermedio") { ?>
                <?php if($estado=='pendiente') { 
                    echo $form->field($model, 'estado')->hiddenInput(['value'=> 'PENDIENTE'])->label(false);
                }else{
                    ?>
                    <?= $form->field($model, 'estado')->dropDownList([ 'ACTIVO' => 'ACTIVO', 'PENDIENTE' => 'PENDIENTE',]) ?>
                    <?php
                }
                
            }else{
                echo $form->field($model, 'estado')->hiddenInput(['value'=> 'PENDIENTE'])->label(false);
            }
            ?>
        </div>
	</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>        
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>