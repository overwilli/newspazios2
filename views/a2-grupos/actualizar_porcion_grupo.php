<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\A2Grupos */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Actualizar Porcion Expensas';
$this->params['breadcrumbs'][] = ['label' => 'Grupos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-clientes-create">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-md-4">            
            <?php echo $model_inmueble->direccion; ?>
            <input type="hidden" value="<?php echo $model_inmueble->id_noticia;?>" name="A2Noticias[id_noticia]" />
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4">            
            <?php
            $arreglo_grupos = ArrayHelper::map(\app\models\A2Grupos::find()->orderBy('descripcion')->all(), 'id_grupo', 'descripcion');
            echo $form->field($model_inmueble, 'id_grupo')->dropDownList($arreglo_grupos, ['prompt' => 'SELECCIONE UN GRUPO']);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4"> 
            <?= $form->field($model_inmueble, 'porcion')->textInput(['maxlength' => true,'class'=>'form-control decimales']) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
    </div>
</div>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/a2-grupos/update-porcion.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>
