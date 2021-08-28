<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InvitacionesMasivas */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Invitaciones Masivas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invitaciones-masivas-view">

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
            array(                
                'attribute' => 'fecha_creacion',
                'value'=>date('d/m/Y',strtotime($model->fecha_creacion)),
            ),
            array(                
                'attribute' => 'fecha_envio',
                'value'=>date('d/m/Y',strtotime($model->fecha_envio)),
            ),
            'estado',
        ],
    ]) ?>

</div>
