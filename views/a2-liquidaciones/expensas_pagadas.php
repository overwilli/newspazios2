<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\A2Liquidaciones;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2LiquidacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expensas Pagadas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-liquidaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);    ?>

    <?php
    $arreglo_periodos = ArrayHelper::map(\app\models\A2Liquidaciones::ObtenerPeriodosLiquidados(), 'periodos', 'periodos');
    //$arreglo_secciones[] = 'Todos';
    //var_dump($dataProvider);exit;
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
//            'id_liquidacion',
//            'id_operacion',
//            'liq_anio',
//            'liq_mes',
//            'fecha_pago',
            [
                'label' => 'Periodo',
                'filter' => Html::activeDropDownList($searchModel, 'periodo', $arreglo_periodos, 
                    ['class' => 'form-control','prompt' => 'Seleccione un periodo']),
                'value' => function ($data) {

                    return $data['mes'] . "/" . $data['year'];
                }
            ],
            [
                'label' => 'Direccion',
                //'filter' => Html::input('text','direccion',"", ['class' => 'form-control',]),
                'filter' => Html::activeInput('text', $searchModel, 'direccion', ['class' => 'form-control',]),
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => 'Direccion_inmueble',                
        
            ],
            [
                'attribute' => 'seccion',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['prompt' => 'Todos', 'class' => 'form-control',]),
                //},
                'value' => 'seccion',     
            ],
            [
                'label' => 'Clientes',
                'filter' => Html::activeInput('text', $searchModel, 'cliente', ['class' => 'form-control',]),
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => 'nombre'
            ],
            [
                'label' => 'Monto',
                'format' => 'Currency',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => 'pagado'
            ],
            // 'monto_pagado',
            // 'monto_intereses',
            // 'usuario',
            // 'timestamp',
            // 'estado',
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'max-width: 100px'],
                'template' => '{registrar_pago} ',
                'buttons' => [
                    'registrar_pago' => function ($url, $model) {
                    //                
                    
                        $url = \yii\helpers\Url::toRoute(['a2-liquidaciones/borrar-pago-expensa', 
                            'id_operacion' => $model['id_operacion_inmobiliaria'],'mes' => $model['mes'],'year' => $model['year'],'monto'=>$model['pagado']
                        ]);
                        return Html::a('<span class="fa fa-usd"></span>', $url, [
                                    'title' => Yii::t('yii', 'Borrar Pago'), 'target' => '_blank'
                        ]);
                    },
                        ]
                    ],
                ],
            ]);
            ?>
</div>
