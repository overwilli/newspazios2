<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\A2Caja */

$this->title = $model->id_caja;
$this->params['breadcrumbs'][] = ['label' => 'A2 Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-caja-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_caja], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_caja], [
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
            'id_caja',
            'inicio_fecha',
            'inicio_hora',
            'inicio_usuario',
            'inicio',
            'caja_final',
            'caja_dia_siguiente',
            'rendicion',
            'sobrante_faltante',
            'caja_cierre',
            'cierre_fecha',
            'cierre_hora',
            'cierre_usuario',
            'cobranzas_efectivo',
            'ingresos_varios',
            'cheques',
            'intereses_mora',
            'depositos',
            'retenciones',
            'gastos_varios',
            'supermercado',
            'timestamp',
            'numero_caja',
        ],
    ]) ?>

</div>
