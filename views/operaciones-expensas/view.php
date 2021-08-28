<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OperacionesExpensas */

$this->title = $model->contrato->inmueble->direccion;
$this->params['breadcrumbs'][] = ['label' => 'Expensas cargadas', 'url' => ['index','propiedad_id'=>$model->inmuebles_id,'operacion_id'=>$model->operacion_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operaciones-expensas-view">

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

        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',['operaciones-expensas/index','propiedad_id'=>$model->inmuebles_id,'operacion_id'=>$model->operacion_id], ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'tipo_expensas_id',
            [
                'attribute'=>'tipo_expensas_id',
                'value'=>$model->tipoExpensas->descripcion,
            ],
            //'inmuebles_id',
            ['attribute' => 'inmuebles_id',
                'value' => $model->contrato->inmueble->direccion,
            ],
            'mes',
            'year',
            'importe',
            'estado',
            //'comprobante_id',
        ],
    ]) ?>

</div>
