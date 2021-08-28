<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contratos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-operaciones-inmobiliarias-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo Contrato', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id_operacion_inmobiliaria',
            'nro_ope',
            'desde_anio',
            'desde_mes',
            'hasta_anio',
            'estado',
            // 'hasta_mes',
            // 'fecha_ope',
            // 'fecha_desde',
            // 'fecha_hasta',
            // 'cod_propiedad',
            // 'cod_cliente',
            // 'cod_garante',
            // 'observaciones:ntext',
            // 'confirmada',
            // 'fecha_confirmacion',
            // 'conv_desocup',
            // 'dia_venc_mensual',
            // 'id_inmobiliaria',
            // 'interes_dia_mora',
            // 'editor',
            // 'fechatimestamp',
            // 'permite_pagos_pendientes',
            // 'tiene_expensas',
            // 'plazo',
            // 'tipo_contrato',
            // 'firma_representante',
            // 'inquilino_1',
            // 'inquilino_2',
            // 'deposito_garantia',
            // 'deposito_monto',
            // 'deposito_cuotas',
            // 'deposito_contrato_monto',
            // 'excento',
            // 'honorarios',
            // 'excento_monto',
            // 'excento_cuotas',
            // 'contrato_firmado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
