<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\ContratosDocumentos */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- CK Editor -->
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<div class="contratos-documentos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'operacion_inmobiliaria_id')->hiddenInput()->label(false) ?>
    
    <?php $arreglo_plantillas = ArrayHelper::map(\app\models\Plantillas::find()->where(['estado' => 'ACTIVO'])->orderBy('titulo')->all(), 'id', 'titulo'); ?>
    <?= $form->field($model, 'plantilla_id')->dropDownList($arreglo_plantillas, ['prompt'=>'Seleccione una plantilla']);
    ?>

    <?= $form->field($model, 'texto')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'estado')->dropDownList([ 'ACTIVO' => 'ACTIVO', 'PENDIENTE' => 'PENDIENTE', 'ELIMINADO' => 'ELIMINADO',], ['prompt' => '']) ?>




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
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/contratos-documentos/contratos_documentos.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>