<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\A2Liquidaciones;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2LiquidacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alquileres por Cobrar';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="a2-liquidaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
    

    <p>
        <?= Html::a('Liquidar Nuevo Periodo', ['create'], ['class' => 'btn btn-success']) ?>

    <?= Html::a('Liquidar Propiedad', ['create-by-inmueble'], ['class' => 'btn btn-success']) ?>
    </p>
	
	<?php  echo $this->render('_search', ['model' => $searchModel]);   ?>
	
    <?php
    $arreglo_periodos = ArrayHelper::map(\app\models\A2Liquidaciones::ObtenerPeriodosLiquidados(), 'periodos', 'periodos');
    $arreglo_secciones = ArrayHelper::map(\app\models\A2Secciones::find()->orderBy('seccion')->all(), 'id_seccion', 'seccion');
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'rowOptions' => function($model, $key, $index, $grid) {

            $date1 = new DateTime($model['liq_anio']."-".$model['liq_mes']."-".$model['dia_venc_mensual']);
            $date2 = new DateTime(date("Y-m-d"));
            $diff = $date1->diff($date2);
            //echo $diff->format('%R%a');
            if($diff->format('%R%a')>=0){
                if($diff->format('%R%a')>31){
                    return ['class' => 'danger'];
                }else{
                    return ['class' => 'warning'];
                }
            }else{
                return [];
            }

            /*$aux_fecha_actual = (date("Y") * 10) + date("m");
            //print_r($model->operacionInmobiliaria);
            //$contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
            
            if ($model['estado']=='ACTIVO') {				
                $aux_fecha_vencimiento = ($model['liq_anio'] * 10) + $model['liq_mes'];
            } else {
                $aux_fecha_vencimiento = "";
            }
            if ($aux_fecha_vencimiento != "") {// si tiene operacion vinculada y fecha de vencimiento
                if ((($aux_fecha_vencimiento - $aux_fecha_actual) <= 2) && (($aux_fecha_vencimiento - $aux_fecha_actual) >= 0 ))
                    return ['class' => 'warning'];

                if (($aux_fecha_vencimiento - $aux_fecha_actual) < 0) { 
                    if ($model['liq_anio'] > date("Y")) {
                        return ['class' => 'warning'];
                    } else {
                        return ['class' => 'danger'];
                    }
                } else {
                    return [];
                }
            } else {
                return [];
            }*/
        },
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
//            'id_liquidacion',
//            'id_operacion',
//            'liq_anio',
//            'liq_mes',
//            'fecha_pago',
            [
                'label' => 'Periodo',
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
                'label'=>'Tipo de Propiedad',
                'attribute' => 'seccion',
                'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['prompt' => 'Todos', 'class' => 'form-control',]),
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
                    return $data['NOMBRE'];
            /*if ($data->operacionInmobiliaria) {
                return $data->operacionInmobiliaria->cliente->NOMBRE;
            } else {
                return null;
            }*/
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
                'format'=>'Currency',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    if($data['concepto']=='ALQUILER'){
                    /*if ($data->operacionInmobiliaria) {*/
                        $dias_mora = A2Liquidaciones::obtener_dias_mora($data['id_liquidacion']);
                        $arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($data['id_operacion_inmobiliaria'],$data['liq_anio'], $data['liq_mes'],$dias_mora);
                        $monto_pago_parcial=A2Liquidaciones::calcular_pago_parcial($data['id_liquidacion']);
                        return $arreglo_monto['monto']-$monto_pago_parcial;
                    /*} else {
                        return null;
                    }*/
                    }else{
                        return $data['monto'];
                    }
                }
            ],
            // 'monto_pagado',
            // 'monto_intereses',
            // 'usuario',
            // 'timestamp',
            // 'estado',
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'max-width: 100px'],
                'template' => '{registrar_pago_liq} {registrar_pago_exp} ',
                'buttons' => [
                    'registrar_pago_liq' => function ($url, $model) {
                        if($model['concepto']=='ALQUILER'){
                            $url = \yii\helpers\Url::toRoute(['a2-liquidaciones/registrar-pago', 
                                        'id_liquidacion' => $model['id_liquidacion']]);
                            return Html::a('<span class="fa fa-usd"></span>', $url, [
                                        'title' => Yii::t('yii', 'Registrar Pago'),
                            ]);
                        }else{
                            return null;
                        }
                    },
                    'registrar_pago_exp' => function ($url, $model) {
                                        
                        if($model['concepto']=='EXPENSA'){
                            $url = \yii\helpers\Url::toRoute(['a2-liquidaciones/registrar-pago-expensa', 
                                'id_operacion' => $model['id_operacion_inmobiliaria'],'mes' => $model['liq_mes'],'year' => $model['liq_anio'],
                            ]);
                            return Html::a('<span class="fa fa-usd"></span>', $url, [
                                        'title' => Yii::t('yii', 'Registrar Pago'), 
                            ]);
                        }else{
                            return null;
                        }
                    },
                    'imprimir_expensas' => function ($url, $model) {
                        //                
                            if($model['concepto']=='EXPENSA'){
                                $url = \yii\helpers\Url::toRoute(['a2-liquidaciones/imprimir-expensas', 
                                    'operacion_id' => $model['id_operacion_inmobiliaria'],'mes' => $model['liq_mes'],'year' => $model['liq_anio'],
                                ]);
                                return Html::a('<span class="fa fa-print"></span>', $url, [
                                            'title' => Yii::t('yii', 'Imprimir Expensas'), 'target' => '_blank'
                                ]);
                            }else{
                                return null;
                            }
                        },
                        ]
                    ],
                ],
            ]);
            ?>
    
    
</div>
