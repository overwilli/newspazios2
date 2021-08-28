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
        <div class="col-md-10">
            <?php //= Html::a('Nuevo Contrato', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-noticias/index'], ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id_operacion_inmobiliaria',
            //'nro_ope',
            ['label' => 'Cliente',
            'value' => function ($data) {
                if ($data->cliente) {
                    return $data->cliente->NOMBRE;
                } else {
                    return null;
                }
            }
            ],
            'desde_anio',
            'desde_mes',
            'hasta_anio',
            'hasta_mes',
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

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}   {update}   {delete}',
            'buttons' => [
                    'update' => function ($url, $model) {
                        $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/update', 'id' => $model->id_operacion_inmobiliaria,
                                    ]);
                        if($model->estado=='ACTIVO'){
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('yii', 'Actualizar'),
                            ]);
                        }else{
                            return null;
                        }
                    },
                    'delete' => function ($url, $model) {
                        $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/delete', 'id' => $model->id_operacion_inmobiliaria,]);
                        
                        if($model->estado=='ACTIVO'){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Eliminar'),
                                'data-confirm' => Yii::t('yii', 'Esta seguro que desea borrar este contrato?'),
                                'data-method' => 'post', 'data-pjax' => '0',
                                 ]);
                        }else{
                            return null;
                        }
                    },
                ],
            ],
        ],
    ]); ?>
</div>
