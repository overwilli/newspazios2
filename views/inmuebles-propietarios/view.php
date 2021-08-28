<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InmueblesPropietarios */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inmuebles Propietarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inmuebles-propietarios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Borrar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea borrar este registro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'inmueble_id',
            'propietario_id',
            'comision',
            'porcentaje',
            'fecha_carga',
            'fecha_actualizacion',
            'usuario_carga',
            'usuario_actualizacion',
            'estado'
        ],
    ]) ?>

</div>
