<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ContratosDocumentos */

$this->title = $model->operacionInmobiliaria->inmueble->direccion;
$this->params['breadcrumbs'][] = ['label' => 'Contratos Documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contratos-documentos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-10">
            <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Borrar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Esta seguro que desea borrar este registro?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Imprimir', ['imprimir-documento', 'id' => $model->id], ['class' => 'btn btn-primary','target'=>'_blank']) ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);return False;']) ?>
        </div>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'operacion_inmobiliaria_id', 
             [
                'label' => 'Direccion',
                'value' => $model->operacionInmobiliaria->inmueble->direccion,
            ],
            [
                'attribute' => 'plantilla_id',
                'value' => $model->plantillas->titulo,
            ],
            'estado',
            'texto:html',
                        
            
        ],
    ]) ?>

</div>
