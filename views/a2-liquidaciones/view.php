<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */

$this->title = $model->id_liquidacion;
$this->params['breadcrumbs'][] = ['label' => 'A2 Liquidaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-liquidaciones-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_liquidacion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_liquidacion], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_liquidacion',
            'id_operacion',
            'liq_anio',
            'liq_mes',
            'fecha_pago',
            'monto_pagado',
            'monto_intereses',
            'usuario',
            'timestamp',
            'estado',
        ],
    ]) ?>

</div>
