<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LiquidacionpagadasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pagos Efectuados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidacionpagadas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  
    ?>

    <?php
    $model_prop = \app\models\Propietarios::find()->orderBy('apellido,nombre')->all();
    $arreglo_propietarios = ArrayHelper::map($model_prop, 'id', function ($model_prop) {
        return $model_prop->apellido . ', ' . $model_prop->nombre;
    });

    ?>
    <div class="row">
        <div class="col-md-10">

        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver', ['a2-noticias/index'], ['class' => 'btn btn-app', 'onclick' => 'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>

    <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete} ',
                    'buttons' => [
                        
                        'delete' => function ($url, $model) {
                            if ($model->estado == 'Pagado') {
                                $url = \yii\helpers\Url::toRoute([
                                    'liquidacionpagadas/comprobante-propietario',
                                    'orden_id' => $model->id,
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, [
                                    'title' => Yii::t('yii', 'Anular orden'),
                                    'onclick' => 'anular_orden_pago(' . $model->id . ')',
                                ]);
                            }
                        },
                    ]
                ],        
                [
                    'attribute' => 'fecha',
                    'value' => function ($data) {
                        return date('d/m/Y', strtotime($data->fecha));
                    }
                ],
                'id',
                [
                    'attribute' => 'propietario_id',
                    'filter' => Html::activeDropDownList(
                        $searchModel,
                        'propietario_id',
                        $arreglo_propietarios,
                        ['class' => 'form-control', 'prompt' => 'Seleccione un propietario']
                    ),
                    'value' => function ($data) {
                        if ($data->propietario) {
                            return $data->propietario->apellido . ", " . $data->propietario->nombre;
                        } else {
                            return null;
                        }
                    }
                ],
                [
                    'label'=>'Conceptos',
                    'value' => function ($data) {
                        return app\models\LiqpagadasDetalle::find()->where(['liquidacionpagadas_id' => $data->id])->count();
                    }
                ],
                [
                    'label'=>'Total a Abonar',
                    'format' => 'Currency',
                    'attribute' => 'total_cobrado'
                ],
                //'usuario',
                //'interes_mora',
                // 'comision',
                // 'iva',
                // 'gastos',            
                [
                    'attribute' => 'estado',
                    'filter' => Html::activeDropDownList($searchModel, 'estado', [
                        'Pagado' => 'Pagado',
                        'Anulado' => 'Anulado'
                    ], ['prompt' => 'Seleccione un estado', 'class' => 'form-control',]),
                    'value' => function ($data) {
                        return $data->estado;
                    }
                ],
                // 'fecha_creacion',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{form_liquidaciones_pago} {comprobante_pdf} {comprobante_excel} ',
                    'buttons' => [
                        'form_liquidaciones_pago' => function ($url, $model) {
                            $url = \yii\helpers\Url::toRoute([
                                'liquidacionpagadas/form-liquidaciones-ordenes',
                                'orden_id' => $model->id,
                            ]);
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('yii', 'Liq. en orden de pago'), 'target' => '_blank'
                            ]);
                        },
                        'comprobante_pdf' => function ($url, $model) {
                            if ($model->estado == 'Pagado') {
                                $url = \yii\helpers\Url::toRoute([
                                    'liquidacionpagadas/comprobante-propietario',
                                    'orden_id' => $model->id,
                                ]);
                                return Html::a('<span class="fa fa-file-pdf-o"></span>', $url, [
                                    'title' => Yii::t('yii', 'Comprobante orden'), 'target' => '_blank'
                                ]);
                            }
                        },
                        'comprobante_excel' => function ($url, $model) {
                            if ($model->estado == 'Pagado') {
                                $url = \yii\helpers\Url::toRoute([
                                    'liquidacionpagadas/comprobante-propietario-excel',
                                    'orden_id' => $model->id,
                                ]);
                                return Html::a('<span class="fa fa-file-excel-o"></span>', $url, [
                                    'title' => Yii::t('yii', 'Comprobante orden'), 'target' => '_blank'
                                ]);
                            }
                        },
                        /*'delete' => function ($url, $model) {
                            if ($model->estado == 'Pagado') {
                                $url = \yii\helpers\Url::toRoute([
                                    'liquidacionpagadas/comprobante-propietario',
                                    'orden_id' => $model->id,
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, [
                                    'title' => Yii::t('yii', 'Anular orden'),
                                    'onclick' => 'anular_orden_pago(' . $model->id . ')',
                                ]);
                            }
                        },*/
                    ]
                ],
            ],
        ]);
    ?>
</div>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';

    function anular_orden_pago(id_orden) {
        if (confirm("Esta seguro que desea anular la orden de pago")) {
            $("#respuesta_modal_pago_orden").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
            $.ajax({
                url: base_url + "/index.php?r=liquidacionpagadas/anular-orden",
                type: "POST",
                dataType: 'json',
                data: {
                    orden_id: id_orden,
                },
                success: function(data) {
                    if (data.estado == 1) {
                        alert(data.mensaje);
                    } else {
                        alert(data.mensaje_error);
                    }
                    location.href = base_url + "/index.php?r=liquidacionpagadas/pagadas";
                }
            });
        }
    }
</script>