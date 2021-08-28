<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\A2Noticias */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="a2-noticias-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <center><b>DATOS GENERALES</b></center>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'precio')->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-6">

            <?php
            $arreglo_estados = ArrayHelper::map(\app\models\A2ObjetoDePropiedad::find()->orderBy('operacion')->all(), 'id_operacion', 'operacion');
            echo $form->field($model, 'operacion')->dropDownList($arreglo_estados, ['options' => [1 => ['selected' => TRUE]],]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'barrio')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">            
            <?php
            $arreglo_grupos = ArrayHelper::map(\app\models\A2Grupos::find()->orderBy('descripcion')->all(), 'id_grupo', 'descripcion');
            echo $form->field($model, 'id_grupo')->dropDownList($arreglo_grupos, ['prompt' => 'SELECCIONE UN GRUPO']);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">           


            <?php
            $arreglo_provincia = ArrayHelper::map(\app\models\Provincias::find()->orderBy('nombre')->all(), 'id', 'nombre');
            echo $form->field($model, 'provincia_id')->dropDownList($arreglo_provincia, ['options' => [1 => ['selected' => TRUE]],
                'onchange' => '
                        $.get( "' . Url::toRoute('/a2-noticias/localidades') . '", { id: $(this).val() } )
                            .done(function( data ) {                                
                                $( "#' . Html::getInputId($model, 'localidad_id') . '" ).html(data);
                            }
                        );
                    '
            ])
            ?>  

        </div>
        <div class="col-xs-12 col-md-4">            
            <?php
            $arreglo_localidad = ArrayHelper::map(\app\models\Localidades::find()->orderBy('nombre')->all(), 'id', 'nombre');
            echo $form->field($model, 'localidad_id')->dropDownList($arreglo_localidad, ['options' => [1 => ['selected' => TRUE]],]);
            ?>
        </div>
        <div class="col-xs-12 col-md-4">
			<?php //= $form->field($model, 'codigo_postal')->textInput() ?>
            
        </div>
    </div>
	<div class="row">
        <div class="col-xs-12 col-md-12">                            
            <?= $form->field($model, 'porcion')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">                            
            <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <center><b>CARACTERISITICAS</b></center>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">                       
            <?php
            $arreglo_seccion = ArrayHelper::map(\app\models\A2Secciones::find()->orderBy('seccion')->all(), 'id_seccion', 'seccion');
            echo $form->field($model, 'seccion')->dropDownList($arreglo_seccion, ['options' => [1 => ['selected' => TRUE]],]);
            ?>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'sup_terreno')->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-4">            
            <?= $form->field($model, 'apto_comercial')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">           

            <?= $form->field($model, 'ambientes')->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'sup_cubierta')->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-4">            
            <?= $form->field($model, 'apto_profesional')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">           
            <?= $form->field($model, 'dormitorios')->textInput() ?>       
        </div>
        <div class="col-xs-12 col-md-2">
            <?= $form->field($model, 'frente')->textInput() ?>            
        </div>
        <div class="col-xs-12 col-md-2">            
            <?= $form->field($model, 'fondo')->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-4">            
            <?= $form->field($model, 'portero_electrico')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">           
            <?= $form->field($model, 'banios')->textInput() ?>     
        </div>
        <div class="col-xs-12 col-md-4">
             
			<?= $form->field($model, 'ascensor')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>
        </div>
        <div class="col-xs-12 col-md-4">                        
            <?= $form->field($model, 'disposicion')->dropDownList([ 'FRENTE' => 'FRENTE', 'CONTRAFRENTE' => 'CONTRAFRENTE',]) ?>
        </div>        
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">                      
            <?= $form->field($model, 'balcon')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>

        </div>
        <div class="col-xs-12 col-md-4">            
            <?= $form->field($model, 'cochera')->textInput() ?> 
        </div>
        <div class="col-xs-12 col-md-4">           

            <?= $form->field($model, 'antiguedad')->textInput() ?> 
        </div>        
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">           

        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'patio')->dropDownList([ '1' => 'SI', '0' => 'NO',]) ?>        
        </div>
        <div class="col-xs-12 col-md-4">            


            <?php
            $arreglo_seccion = ArrayHelper::map(\app\models\A2EstadosInmuebles::find()->orderBy('descripcion')->all(), 'id_estado', 'descripcion');
            echo $form->field($model, 'id_estado')->dropDownList($arreglo_seccion, ['options' => [1 => ['selected' => TRUE]],]);
            ?>
        </div>        
    </div>   

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <center><b>SERVICIOS</b></center>
            <hr/>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, "luz")->checkbox(['value' => "1"]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, "gas")->checkbox(['value' => "1"]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, "cloaca")->checkbox(['value' => "1"]); ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, "agua")->checkbox(['value' => "1"]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, "parrilla")->checkbox(['value' => "1"]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, "salon_u_m")->checkbox(['value' => "1"]); ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, "piscina")->checkbox(['value' => "1"]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, "seguridad")->checkbox(['value' => "1"]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?= $form->field($model, "amueblado")->checkbox(['value' => "1"]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <center><b>PADRONES</b></center>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'padroniibb')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'padronaguas')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'padronmunicipal')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">   
            <?php if (Yii::$app->user->identity->permisos == "administrador" || Yii::$app->user->identity->permisos == "intermedio") { ?>
                <?= $form->field($model, 'estado_reg')->dropDownList([ 'ACTIVO' => 'ACTIVO', 'PENDIENTE' => 'PENDIENTE', 'ELIMINADO' => 'ELIMINADO',]) ?>
                <?php
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
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/inmueble/inmueble.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>

