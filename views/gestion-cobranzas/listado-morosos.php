<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\A2Liquidaciones;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2LiquidacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gestión de Cobranzas';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="a2-liquidaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php  //echo $this->render('_search', ['model' => $searchModel]);   
    ?>

    <?php
    $arreglo_periodos = ArrayHelper::map(\app\models\A2Liquidaciones::ObtenerPeriodosLiquidados(), 'periodos', 'periodos');
    $arreglo_secciones = ArrayHelper::map(\app\models\A2Secciones::find()->orderBy('seccion')->all(), 'id_seccion', 'seccion');
    ?>

    <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            
            'columns' => [
                
                [
                    'label' => 'Periodo Desde',
                    //'filter' => Html::activeDropDownList($searchModel, 'periodo', $arreglo_periodos, ['prompt' => 'Seleccione un periodo','class' => 'form-control',]),
                    'value' => function ($data) {
                        return $data['liq_mes'] . "/" . $data['liq_anio'];
                    }
                ],
                [
                    'label' => 'Direccion',
                    //'filter' => Html::input('text','direccion',"", ['class' => 'form-control',]),
                    'filter' => Html::activeInput('text', $searchModel, 'direccion', ['class' => 'form-control',]),
                    //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                    //},
                    'value' => function ($data) {
                        return $data['direccion'];
                        /*if ($data->id_operacion_inmobiliaria) {
                return $data->operacionInmobiliaria->inmueble->direccion;
            } else {
                return null;
            }*/
                    }
                ],
                
                [
                    'label' => 'Clientes',
                    'filter' => Html::activeInput('text', $searchModel, 'cliente', ['class' => 'form-control',]),
                    //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                    //},
                    'value' => function ($data) {
                        return $data['NOMBRE'];
                        /*if ($data->operacionInmobiliaria) {
                return $data->operacionInmobiliaria->cliente->NOMBRE;
            } else {
                return null;
            }*/
                    }
                ],
                [
                    'label' => 'Concepto',
                    'filter' => Html::activeDropDownList($searchModel, 'concepto', [
                        'ALQUILER' => 'ALQUILER',
                        'EXPENSA' => 'EXPENSA',
                    ], ['prompt' => '', 'class' => 'form-control',]),
                    'attribute' => 'tipo',
                ],
                /*[
                'label' => 'Monto',
                'format'=>'Currency',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    if($data['tipo']=='ALQUILER'){
                    /*if ($data->operacionInmobiliaria) {*/
                /*$dias_mora = A2Liquidaciones::obtener_dias_mora($data['id_liquidacion']);
                        $arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($data['id_operacion_inmobiliaria'], 
                            $data['liq_anio'], $data['liq_mes'],$dias_mora);
                        return $arreglo_monto['monto'];
                    /*} else {
                        return null;
                    }*/
                /*}else{
                        return $data['monto'];
                    }
                }
            ],*/
                // 'monto_pagado',
                // 'monto_intereses',
                // 'usuario',
                // 'timestamp',
                // 'estado',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'max-width: 100px'],
                    'template' => '{view} {nota}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            $url = \yii\helpers\Url::toRoute([
                                'gestion-cobranzas/index',
                                'id' => $model['id_noticia'], 'operacion_id' => $model['id_operacion']
                            ]);
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('yii', 'Gestion'), 'target' => '_blank'
                            ]);
                        },
                        'nota' => function ($url, $model) {
                            $url = \yii\helpers\Url::toRoute([
                                'gestion-cobranzas/create',
                                'id' => $model['id_noticia'], 'operacion_id' => $model['id_operacion']
                            ]);
                            return Html::a('<span class="glyphicon glyphicon-text-width"></span>', $url, [
                                'title' => Yii::t('yii', 'Grabar una nota '),
                            ]);
                        }

                    ]
                ],
            ],
        ]);
    ?>


</div>