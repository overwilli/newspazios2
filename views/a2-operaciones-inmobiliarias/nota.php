<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\A2OperacionesInmobiliarias */

$this->title = $model->inmueble->direccion;
$this->params['breadcrumbs'][] = ['label' => 'Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-operaciones-inmobiliarias-view">


<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12 col-md-8">             
            <label class="control-label" for="">ULTIMO CONTACTO</label><br/>
            <?php echo '<label id="" class="form-control" for="">' . 
                ($model->ultimo_contacto)?date('d/m/Y H:i:d',strtotime($model->ultimo_contacto)):'' . '</label>';?><br/>
            <label class="control-label" for="">USUARIO</label><br/>
            <?php echo '<label id="" class="form-control" for="">' . 
                ($model->usuario_contacto)?$model->usuario_contacto:'' . '</label>';?>                         
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-8">                    
                <?= $form->field($model, 'nota')->textArea(['class'=>'form-control']) ?>                    
            </div>
        </div>    
        <div class="form-group">
            <?= Html::submitButton('Grabar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-noticias/inmuebles-vencer'], ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>
        <?php ActiveForm::end(); ?>
</div>
