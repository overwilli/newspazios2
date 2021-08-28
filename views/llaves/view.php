<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Llaves */

$this->title = $model->inmueble->direccion;
$this->params['breadcrumbs'][] = ['label' => 'Llaves', 'url' => ['index', 'id' => $model->inmueble_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="llaves-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-10">
        <?= Html::a('Devolver llave', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Borrar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea borrar este registro?',
                'method' => 'post',
            ],
        ])
        ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);return False;']) ?>
        </div>
    </div>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            ['attribute' => 'inmueble_id',
                'value' => $model->inmueble->direccion],
            'numero_llave',
            ['attribute' => 'inmobiliaria_id',
                'value' => $model->inmobiliaras->nombre_inmobiliaria],            
            [
                'attribute' => 'fecha_solicitud',
                'value'=>date('d/m/Y',strtotime($model->fecha_solicitud)),
            ],
            'tipo_solicitud',
            'persona',
            'observacion:ntext',
            'fecha_devolucion',
            'usuario_devolucion',
        ],
    ])
    ?>

</div>
