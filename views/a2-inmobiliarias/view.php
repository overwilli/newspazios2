<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\A2Inmobiliarias */

$this->title = $model->id_inmobiliaria;
$this->params['breadcrumbs'][] = ['label' => 'A2 Inmobiliarias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-inmobiliarias-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-10">
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_inmobiliaria], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Borrar', ['delete', 'id' => $model->id_inmobiliaria], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea borrar este registro?',
                'method' => 'post',
            ],
        ]) ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',null, ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_inmobiliaria',
            'nombre_inmobiliaria',
        ],
    ]) ?>

</div>
