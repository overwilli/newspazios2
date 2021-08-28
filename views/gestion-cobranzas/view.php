<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GestionCobranzas */

$this->title = $model->contrato->inmueble->direccion;
$this->params['breadcrumbs'][] = ['label' => 'Gestion Cobranzas', 'url' => ['index',]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gestion-cobranzas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Imprimir', ['imprimir-nota', 'id' => $model->id], ['class' => 'btn btn-primary','target'=>'_blank']) ?>
        <?= Html::a('Borrar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea borrar este registro?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);return False;']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',            
            [
                
                'attribute' => 'cliente_id',
                'value' => date('d/m/Y',strtotime($model->fecha_notificacion)),
            ],          
            [
                
                'attribute' => 'cliente_id',
                'value' => $model->cliente->NOMBRE,
            ],
            [
                
                'attribute' => 'inmueble_id',
                'value' => $model->contrato->inmueble->direccion,
            ],            
            [
                
                'attribute' => 'cliente_id',
                'value' => date('d/m/Y',strtotime($model->fecha)),
            ],
            'hora',  
            'nivel',
            'observaciones:ntext',
        ],
    ]) ?>

</div>
