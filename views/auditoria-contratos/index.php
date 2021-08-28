<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AuditoriaContratosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auditoria Contratos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auditoria-contratos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Procesar contratos sin procesar.', ['auditoria-contratos/procesar-contratos'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'operacion_id',
            [
                'label' => 'Inmueble',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['prompt' => 'Todos', 'class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    return $data->operacionInmobiliaria->inmueble->direccion;
                },
            ],
            [
                'label' => 'Cliente',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['prompt' => 'Todos', 'class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    return $data->operacionInmobiliaria->cliente->NOMBRE;
                },
            ],
            [
                'label' => 'Contrato Vencimiento',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['prompt' => 'Todos', 'class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    return $data->operacionInmobiliaria->hasta_anio."-".$data->operacionInmobiliaria->hasta_mes."-".
                    $data->operacionInmobiliaria->dia_venc_mensual;
                },
            ],
            [
                'attribute' => 'fecha_contrato',
                'label'=>'Contratos vencidos Hasta',
                'value' =>'fecha_contrato',
            ],
            
            'fecha_procesamiento',
            'estado_contrato',

            ['class' => 'yii\grid\ActionColumn',
            'template' => ''
            ],
        ],
    ]); ?>
</div>
