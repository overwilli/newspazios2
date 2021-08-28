<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Localidades;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\PromocionesMails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promociones-mails-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?= $form->field($model, 'asunto')->textInput(['maxlength' => true])->hint('El asunto, es texto que visualiza el cliente cuando le llega un mail a su casilla de correo.') ?>       
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?=
            $form->field($model, 'fecha_envio')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ])->hint('Esta fecha indica cuando se enviar&aacute; el mail de promoci&oacute;n. Adem&aacute;s el estado del mail de promoci&oacute;n debe estar en "Enviando". ')
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?= $form->field($model, 'condiciones')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?=
                    $form->field($model, 'segmentacion')->dropDownList([ '0' => 'NINGUNO', '1' => 'POR EDAD',
                        '2' => 'LOCALIDAD', '3' => 'EMPLEO', '4' => 'DEUDA'], ['options' => [ '0' => ['Selected' => 'selected']],
                        'onchange' => 'cambiar_interface_edad()'])
                    ->hint(utf8_encode('Con la opción "Ninguno" el mail no se segmenta, Con la opción "EDAD" se segmenta el mail para la edad máxima y mínima indicada.'))
            ?>
        </div>
    </div>    
    <div id="form_edad" class="row" style="display: <?php echo ($model->segmentacion == 0) ? "none" : "block"; ?>">
        <div class="col-xs-12 col-md-6">
            <?=
            $form->field($model, 'edad_minima')->textInput();
            ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?=
            $form->field($model, 'edad_maxima')->textInput();
            ?>
        </div>
    </div>    
    <div id="form_codigo_postal" class="row" style="display: <?php echo ($model->segmentacion == 0) ? "none" : "block"; ?>">
        <div class="col-xs-12 col-md-8">
            <?php
            $items = ArrayHelper::map(Localidades::find()->where("localidad LIKE '%SGO.DEL ESTERO%' OR id=4200 or id=4300")->orderBy('localidad')->all(), 'id', 'localidad');
            echo $form->field($model, 'codigo_postal')->dropDownList($items, ['prompt' => 'Seleccione una localidad'])
            ?>
            <table id="tabla_localidades" class="table table-bordered">
                <th>Localidad</th>
                <th>C.P.</th>                
                <th>Acci&oacute;n</th>
                <?php
                if (isset($model->arreglo_localidades) > 0) {
                    $i=0;
                    foreach ($model->arreglo_localidades as $row) {
                        
                        if (($i % 2) == 0) {
                            $clase = "odd";
                        } else {
                            $clase = "even";
                        }
                        $localidad_row=Localidades::find()->where(['id'=>$row])->one();
                        
                        $insertar_registro = "<tr class='" . $clase . "' id='registro_" . $row . "'>";
                        $input_registro = "<input type='hidden' name='localidades_promo[]' value='" . $row . "'/>";
                        $insertar_registro .= "<td>".$localidad_row->localidad . $input_registro . "</td>";
                        $insertar_registro .= "<td>" .$row. "</td>";
                        $insertar_registro .= "<td ><a title='Remover' class='glyphicon glyphicon-minus-sign' onclick='borrar_registro_localidad(".$row. ")'></a></td>";
                        $insertar_registro .= "</tr>";
                        if ($model->scenario == 'update') {
                            echo $insertar_registro;
                        } else {
                            echo $insertar_registro;
                        }
                        $i++;
                    }
                }
                ?>
            </table>
        </div>
        <div class="col-xs-12 col-md-4"><br/>
            <a class="btn btn-block btn-default" onclick="agregar_localidad()">Agregar localidad</a>
        </div> 
    </div>

    <div id="form_codigo_empleado" class="row" style="display: <?php echo ($model->segmentacion == 0) ? "none" : "block"; ?>">
        <div class="col-xs-12 col-md-12">
            <?php
            $obj_clientes = new app\models\Clientes;
            $items = ArrayHelper::map($obj_clientes->obtener_empleos(), 'codemp', 'codemp');
            echo $form->field($model, 'codigo_empleado')->dropDownList($items)
            ?>
        </div>       
    </div>
    <div id="form_deuda" class="row" style="display: <?php echo ($model->segmentacion == 0) ? "none" : "block"; ?>">
        <div class="col-xs-12 col-md-6">
            <?=
            $form->field($model, 'deuda_desde')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ])->hint('Indique desde que fecha de deuda desea enviar el mail. ')
            ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?=
            $form->field($model, 'deuda_hasta')->textInput()->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd/MM/yyyy',
            ])->hint('Indique hasta que fecha de deuda desea enviar el mail. ')
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?=
                    $form->field($model, 'estado')->dropDownList([ 'NUEVO' => 'NUEVO', 'ENVIANDO' => 'ENVIANDO', 'ENVIADO' => 'ENVIADO',], ['options' => [ 'NUEVO' => ['Selected' => 'selected']],])
                    ->hint('El estado "Nuevo" indica que el mail de promoci&oacute;n esta en dise&ntilde;o, "Enviando" indica que el mail se esta enviando a los clientes, "Enviado" indica que el mail se termino de enviar a todos los clientes.')
            ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/promocionesmail/create.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<script type="text/javascript">
    function cambiar_interface_edad() {


        if ($('#promocionesmails-segmentacion').val() == 1) {
            $('#form_edad').css('display', 'block');
        } else {
            $('#form_edad').css('display', 'none');
        }
        if ($('#promocionesmails-segmentacion').val() == 2) {
            $('#form_codigo_postal').css('display', 'block');
        } else {
            $('#form_codigo_postal').css('display', 'none');
        }
        if ($('#promocionesmails-segmentacion').val() == 3) {
            $('#form_codigo_empleado').css('display', 'block');
        } else {
            $('#form_codigo_empleado').css('display', 'none');
        }
        if ($('#promocionesmails-segmentacion').val() == 4) {
            $('#form_deuda').css('display', 'block');
        } else {
            $('#form_deuda').css('display', 'none');
        }
    }
    ;

</script>
