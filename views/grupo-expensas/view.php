<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoExpensas */

$this->title = "";
$this->params['breadcrumbs'][] = ['label' => 'Grupo Expensas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupo-expensas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-10">
            <?php
            if($model->expensas_por=='GRUPO'){
                ?>
                <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary',]) ?>
                <?php 
            } ?>
            <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Esta seguro que desea borrar este registro?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'grupo_id',
            [
                'attribute' => 'grupo_id',
                'value' => function ($data) {
                    if ($data->grupos) {
                        return $data->grupos->descripcion;
                    } else {
                        return null;
                    }
                }
            ],
            [
                'attribute' => 'tipo_expensa_id',
                'value' => function ($model) {
                    if ($model->tipoExpensas) {
                        return $model->tipoExpensas->descripcion;
                    } else {
                        return null;
                    }
                }
            ],  
            'mes',
            'year',
            'importe',
        ],
    ]) ?>

</div>
