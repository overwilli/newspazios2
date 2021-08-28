<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\A2Liquidaciones;
use app\models\InmueblesPropietarios;
use app\models\A2Movimientos;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2LiquidacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Informe de Comprobantes Cobrados';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="a2-liquidaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php // echo $this->render('_search', ['model' => $searchModel]);   ?>
    
    <?php
    $arreglo_periodos = ArrayHelper::map(\app\models\A2Liquidaciones::ObtenerPeriodosLiquidados(), 'periodos', 'periodos');
    //$arreglo_secciones[] = 'Todos';
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
                'filter' => Html::activeDropDownList($searchModel, 'periodo', $arreglo_periodos, ['class' => 'form-control', 'prompt' => 'Todos']),
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
                }
            ],
            [
                'attribute' => 'seccion',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['prompt' => 'Todos', 'class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    /*if ($data->operacionInmobiliaria) {
                        return $data->operacionInmobiliaria->inmueble->secciones->seccion;
                    } else {
                        return null;
                    }*/
                    return $data['seccion'];
                },
            ],
            [
                'label' => 'Clientes',
                'filter' => Html::activeInput('text', $searchModel, 'cliente', ['class' => 'form-control',]),
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    /*if ($data->operacionInmobiliaria) {
                        return $data->operacionInmobiliaria->cliente->NOMBRE;
                    } else {
                        return null;
                    }*/
                    return $data['NOMBRE'];
                }
            ],
            [
                'label'=>'Concepto',
                'filter' => Html::activeDropDownList($searchModel, 'concepto', ['ALQUILER' => 'ALQUILER', 
                    'EXPENSA' => 'EXPENSA', ], ['prompt' => '', 'class' => 'form-control',]),
                'attribute' => 'concepto',
            ],
            [
                'label' => 'Monto',
                'format' => 'Currency',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => function ($data) {

                    /*if ($data->operacionInmobiliaria) {
                        $dias_mora = A2Liquidaciones::obtener_dias_mora($data->id_liquidacion);
                        $arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($data->id_operacion, $data->liq_anio, $data->liq_mes, 0);
                        return $arreglo_monto['monto'];
                    } else {
                        return null;
                    }*/
                    if($data['concepto']=='ALQUILER'){
                        /*if ($data->operacionInmobiliaria) {*/
                            
                            /*$dias_mora = A2Liquidaciones::obtener_dias_mora($data['id_liquidacion']);
                            $arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($data['id_operacion_inmobiliaria'], 
                                $data['liq_anio'], $data['liq_mes'],$dias_mora);
                            return $arreglo_monto['monto'];*/

                            $dias_mora = A2Liquidaciones::obtener_dias_mora($data['id_liquidacion']);
                        $arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($data['id_operacion_inmobiliaria'], 
                        $data['liq_anio'], $data['liq_mes'],0);
                        $mora_pagada= A2Liquidaciones::obtener_mora_pagada($data['id_liquidacion']);
                        return $arreglo_monto['monto']+$mora_pagada;
                            //return $data['monto'];
                        /*} else {
                            return null;
                        }*/
                    }else{
                        return $data['monto'];
                    }
                }
            ],
            'fecha_pago',
            // 'monto_pagado',
            // 'monto_intereses',
            // 'usuario',
            // 'timestamp',
            // 'estado',
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'max-width: 100px'],
                'template' => '{reimprimir_comprobante}',
                'buttons' => [
                    'registrar_pago' => function ($url, $model) {
                        if($model['concepto']=='ALQUILER'){
                            $resultado = A2Movimientos::existe_movimiento_tipo_pendiente($model['id_liquidacion']);
                            if (!$resultado) {//SI NO ES UN REGISTRO DE TIPO PENDIENTE....
                                $url = \yii\helpers\Url::toRoute(['a2-liquidaciones/borrar-pago-liquidacion', 
                                    'id_liquidacion' => $model['id_liquidacion'],
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                            'title' => Yii::t('yii', 'Borrar Pago'), 'target' => '_blank'
                                ]);
                            } else {
                                return "";
                            }
                        } else {
                            $url = \yii\helpers\Url::toRoute(['a2-liquidaciones/borrar-pago-expensa', 
                            'id_operacion' => $model['id_operacion_inmobiliaria'],'mes' => $model['liq_mes'],'year' => $model['liq_anio'],'monto'=>$model['monto']
                            ]);
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Yii::t('yii', 'Borrar Pago'), 'target' => '_blank'
                            ]);
                        }
                    },
					'reimprimir_comprobante' => function ($url, $model) {
                        if($model['concepto']=='ALQUILER'){
                            $resultado = A2Movimientos::existe_movimiento_tipo_pendiente($model['id_liquidacion']);
                            if (!$resultado) {//SI NO ES UN REGISTRO DE TIPO PENDIENTE....
                                $url = \yii\helpers\Url::toRoute(['a2-liquidaciones/reimprimir-comprobante', 
                                    'id_liquidacion' => $model['id_liquidacion'],
                                        ]);
                                return Html::a('<span class="glyphicon glyphicon-print"></span>', $url, [
                                            'title' => Yii::t('yii', 'Reimprimir comprobante'), 'target' => '_blank'
                                ]);
                                /*$url = \yii\helpers\Url::toRoute(['a2-liquidaciones/borrar-pago-liquidacion', 
                                    'id_liquidacion' => $model->id_liquidacion,
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-print"></span>', $url, [
                                            'title' => Yii::t('yii', 'Reimprimir comprobante'), 'target' => '_blank'
                                ]);*/
                            } else {
                                return "";
                            }
                        } else {
                            return "";
                        }
                    },
					
                        ]
                    ],
                ],
            ]);
            ?>
        
</div>
