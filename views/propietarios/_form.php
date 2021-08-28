<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Propietarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="propietarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

   

    <?= $form->field($model, 'cuit')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-xs-12 col-md-12">           


            <?php
            $arreglo_provincia = ArrayHelper::map(\app\models\Provincias::find()->orderBy('nombre')->all(), 'id', 'nombre');
            echo $form->field($model, 'provincia')->dropDownList($arreglo_provincia, ['options' => [1 => ['selected' => TRUE]],
                'onchange' => '
                        $.get( "' . Url::toRoute('/a2-noticias/localidades') . '", { id: $(this).val() } )
                            .done(function( data ) {                                
                                $( "#' . Html::getInputId($model, 'localidad') . '" ).html(data);
                            }
                        );
                    '
            ])
            ?>  

        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">         
            <?php
            $arreglo_localidad = ArrayHelper::map(\app\models\Localidades::find()->orderBy('nombre')->all(), 'id', 'nombre');
            echo $form->field($model, 'localidad')->dropDownList($arreglo_localidad, ['options' => [1 => ['selected' => TRUE]],]);
            ?>
        </div>
    </div>
    <div class="row">
        
        <div class="col-xs-12 col-md-12">   
            <?= $form->field($model, 'estado')->dropDownList([ '1' => 'ACTIVO', '0' => 'INACTIVO']) ?>            
        </div>
    </div>        

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
