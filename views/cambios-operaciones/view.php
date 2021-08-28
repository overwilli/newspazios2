<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CambiosOperaciones */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cambios Operaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cambios-operaciones-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'operacion_id',
            'desde_anio',
            'desde_mes',
            'hasta_anio',
            'hasta_mes',
            'estado',
            'fecha',
            'usuario',
        ],
    ]) ?>

</div>
