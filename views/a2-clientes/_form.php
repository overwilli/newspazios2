<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\A2Clientes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-clientes-form">

<?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-md-6">			
<?= $form->field($model, 'NOMBRE')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-xs-12 col-md-6">
			<?php
            /*$form->field($model, 'FECNAC')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ])->hint('Indique desde que fecha de Nacimiento. ')*/
            ?>
			<?php //= $form->field($model, 'NOMBRE_FANTASIA')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'EMAIL')->textInput(['maxlength' => true]) ?>
        </div>
    </div>    
    <div class="row">
        <div class="col-xs-12 col-md-6">
			<?= $form->field($model, 'DNI')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-xs-12 col-md-6">
			<?= $form->field($model, 'CUIL')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
<?= $form->field($model, 'DIRECCION')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'BARRIO')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
        <?php
            $arreglo_provincia = ArrayHelper::map(\app\models\Provincias::find()->orderBy('nombre')->all(), 'id', 'nombre');
            echo $form->field($model, 'provincia_id')->dropDownList($arreglo_provincia, ['prompt' => 'Seleccione una provincia',
                'onchange' => '
                        $.get( "' . Url::toRoute('/a2-noticias/localidades') . '", { id: $(this).val() } )
                            .done(function( data ) {                                
                                $( "#' . Html::getInputId($model, 'localidad_id') . '" ).html(data);
                            }
                        );
                    '
            ])
            ?>
<?php //= $form->field($model, 'LOCALIDAD')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-6">
        <?php
            $arreglo_localidad = ArrayHelper::map(\app\models\Localidades::find()->orderBy('nombre')->all(), 'id', 'nombre');
            echo $form->field($model, 'localidad_id')->dropDownList($arreglo_localidad, ['prompt' => 'Seleccione una localidad',]);
            ?>
    <?php //= $form->field($model, 'PROVINCIA')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">        
<?= $form->field($model, 'TELEFONO')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">
<?= $form->field($model, 'TELEF2')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">            

<?= $form->field($model, 'TELEF3')->textInput(['maxlength' => true]) ?>
        </div>
    </div>    
    <div class="row">
        <div class="col-xs-12 col-md-6">

        </div>
        <div class="col-xs-12 col-md-6">
<?php //= $form->field($model, 'NRO_CUENTA')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?php
            //$arreglo_estados = ArrayHelper::map(\app\models\A2EstadoCivil::find()->orderBy('denominacion')->all(), 'id', 'denominacion');
            //echo $form->field($model, 'EST_CIVIL')->dropDownList($arreglo_estados, ['options' => [1 => ['selected' => TRUE]],]);
            ?>

        </div>
        <div class="col-xs-12 col-md-6">
            
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?php //= $form->field($model, 'PRIM_NUPCIAS')->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?php //= $form->field($model, 'DNI_CONYUGE')->textInput(['maxlength' => true]) ?>
        </div>
    </div>       
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?php //= $form->field($model, 'NOMBRE_CONYUGE')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?php //= $form->field($model, 'CUIL_CONYUGE')->textInput(['maxlength' => true]) ?>
        </div>
    </div>            
    <div class="row">
        <div class="col-xs-12 col-md-6">   

            <?= $form->field($model, 'OBSERVACIONES')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-xs-12 col-md-6">   
            <?php if (Yii::$app->user->identity->permisos == "administrador" || Yii::$app->user->identity->permisos == "intermedio") { ?>
                <?= $form->field($model, 'estado')->dropDownList([ 'ACTIVO' => 'ACTIVO', 'PENDIENTE' => 'PENDIENTE', 'ELIMINADO' => 'ELIMINADO',]) ?>
                <?php
            }else{
                echo $form->field($model, 'estado')->hiddenInput(['value'=> 'PENDIENTE'])->label(false);
            }
            ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',['index'], ['class' => 'btn btn-success',]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
