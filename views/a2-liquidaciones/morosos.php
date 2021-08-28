<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\models\A2Liquidaciones;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Morosos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-operaciones-inmobiliarias-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="a2-liquidaciones-search">

    <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Buscador</h3>
            </div>

    <?php $form = ActiveForm::begin([
        'action' => ['morosos'],
        'method' => 'get',
    ]); ?>

    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label >Cliente</label>            
                    <?= Html::input('text', 'cliente', '', ['class'=>'form-control','placeholder'=>'Cliente']) ?>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Direcci&oacute;n</label>            
                    <?= Html::input('text', 'direccion', '', ['class'=>'form-control','placeholder'=>'Direccion']) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Concepto</label>            
                    <?php echo Html::dropDownList('concepto','',[  'ALQUILER' => 'ALQUILER', 'EXPENSAS' => 'EXPENSAS', ], ['prompt' => 'TODOS','class'=>'form-control']) ;?>
                </div>
                <div>
                    <?php echo Html::a('<i class="fa fa-file-excel-o"></i>Excel', 
                    ['a2-liquidaciones/morosos-liq-expensas',], ['class' => 'btn btn-app']);?>
                </div>
            </div>
        </div>       
    </div>

    

    <?php //echo $form->field($model, 'tipo_filtro')->dropDownList([  'sin_vencer' => 'SIN VENCER', 'por_vencer' => 'POR VENCER', ], ['prompt' => 'TODOS']) ?>      

    <div class="box-footer">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Limpiar', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'NOMBRE',
            'direccion',
            
            
            
            //'liq_anio',            
            //'liq_mes',            
            'tipo',
            [
                'label'=>'Período Adeudado',
                'value'=>function($data){
                    return $data['liq_mes']."/".$data['liq_anio'];
                }
            ],
            [
                
                'label'=>'Ultimo Contacto',
                'value'=>function($data){
                    $model=app\models\GestionCobranzas::find()->where(['operacion_id'=>$data['id_operacion']])->
                        orderBy(['fecha_notificacion' => SORT_DESC])->one();
                    if($model){
                        return date('d/m/Y',strtotime($model->fecha_notificacion));
                    }else{
                        return null;
                    }
                }
            ],
            [
                'label'=>'Importe',
                'format'=>'Currency',
                'value'=>function($data){
                    if($data['tipo']=='ALQUILER'){
                        $dias_mora = A2Liquidaciones::obtener_dias_mora($data['id_liquidacion']);
                        $arreglo_monto = A2Liquidaciones::Calcular_Monto_Periodo($data['id_operacion'], $data['liq_anio'], 
                            $data['liq_mes'],$dias_mora);
                        return $arreglo_monto['monto'];
                    }
                    if($data['tipo']=='EXPENSAS'){
                        return $data['deuda'];
                    }                        
                    return 0;
                }
            ],
            
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
            'template' => '{gestion_cobranzas} ',
            'buttons' => [
                'gestion_cobranzas' => function ($url, $model) {
                    $model_oi=app\models\A2OperacionesInmobiliarias::find()->
                        where(['id_operacion_inmobiliaria' => $model['id_operacion']])->one();

                    $url = \yii\helpers\Url::toRoute(['gestion-cobranzas/index', 
                        'id' => $model_oi->cod_propiedad,'operacion_id'=>$model['id_operacion'],
                                ]);
                    return Html::a('<span class="fa fa-phone"></span>', $url, [
                                'title' => Yii::t('yii', 'Gestion Cobranzas'),
                    ]);
                },
            ],
            ],
        ],
    ]); ?>
</div>