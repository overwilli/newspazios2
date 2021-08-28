<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2NoticiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expensas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-noticias-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $arreglo_secciones = ArrayHelper::map(\app\models\A2Secciones::find()->orderBy('seccion')->all(), 'id_seccion', 'seccion');
    //$arreglo_secciones[] = 'Todos';
    ?>    
    <?php echo $this->render('_search_expensas', ['model' => $searchModel]); ?>

    <?php
    echo Html::a('<i class="fa fa-file-excel-o"></i>Excel', ['informes/expensas-consorcio-excel',], 
        ['class' => 'btn btn-app','title'=>'Informe de Expensas por Consorcio','target'=>'_blank']);
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $grid) {

            $aux_fecha_actual = (date("Y") * 10) + date("m");
            //print_r($model->operacionInmobiliaria);
            $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
            if (isset($contrato_activo)) {
                $aux_fecha_vencimiento = ($contrato_activo->hasta_anio * 10) + $contrato_activo->hasta_mes;
            } else {
                $aux_fecha_vencimiento = "";
            }
            if ($aux_fecha_vencimiento != "") {// si tiene operacion vinculada y fecha de vencimiento
                if ((($aux_fecha_vencimiento - $aux_fecha_actual) <= 2) && (($aux_fecha_vencimiento - $aux_fecha_actual) >= 0 ))
                    return ['class' => 'warning'];

                if (($aux_fecha_vencimiento - $aux_fecha_actual) < 0) {
                    if ($contrato_activo->hasta_anio > date("Y")) {
                        return ['class' => 'warning'];
                    } else {
                        return ['class' => 'danger'];
                    }
                } else {
                    return [];
                }
            } else {
                return [];
            }
        },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'id_noticia',
                    'titulo',
                    'fecha',
                    [
                        'attribute' => 'seccion',
                        'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['prompt' => 'Todos', 'class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                    return $data->secciones->seccion;
                },
                    ],
                    [
                        'attribute' => 'operacion',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            if ($data->objetoPropiedad) {
                                return $data->objetoPropiedad->operacion;
                            } else {
                                return null;
                            }
                        }
                    ],
                    [
                        'label' => 'Vencimiento',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            $contrato_activo=$data->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                            if ($contrato_activo) {
                                return $contrato_activo->hasta_mes . "/" . $contrato_activo->hasta_anio;
                            } else {
                                return null;
                            }
                        }
                    ],
                    //'seccion',
                    //'pesoseccion',
                    //'antetitulo',
                    // 'bajada:ntext',
                    // 'cuerpocompleto:ntext',
                    // 'foto',
                    // 'fotopie',
                    // 'clicks',
                    // 'autor',
                    // 'editor',
                    // 'timestamp',
                    // 'fechatimestamp',
                    // 'fechacarga',
                    // 'operacion',
                    // 'alq_vendida',
                    // 'id_estado',
                    // 'id_grupo',
                    // 'direccion',                    
                    ['attribute' => 'precio',
                    'format' => 'Currency',],
                    // 'ambientes',
                    // 'sup_cubierta',
                    // 'sup_terreno',
                    // 'habitaciones',
                    // 'dormitorios',
                    // 'banios',
                    // 'conv_desocup',
                    // 'frente',
                    // 'fondo',
                    // 'padroniibb',
                    // 'padronaguas',
                    // 'padronmunicipal',
                    // 'provincia_id',
                    // 'localidad_id',
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{expensas}',
                        'buttons' => [
                            'expensas' => function ($url, $model) {
                                $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                if($contrato_activo){
                                    $url = \yii\helpers\Url::toRoute(['operaciones-expensas/index', 'propiedad_id' => $model->id_noticia,
                                        'operacion_id'=>$contrato_activo->id_operacion_inmobiliaria,                                            ]);
                                    return Html::a('<span class="fa fa-stack-overflow"></span>', $url, [
                                                'title' => Yii::t('yii', 'Expensas del Contrato'), 'target' => '_blank'
                                    ]);
                                }
                            },                            
                                ]
                            ],
                        ],
                    ]);
                    ?>
</div>
