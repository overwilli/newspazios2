<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\A2NoticiasImagenes;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2NoticiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inmuebles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-noticias-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $arreglo_secciones = ArrayHelper::map(\app\models\A2Secciones::find()->orderBy('seccion')->all(), 'id_seccion', 'seccion');
    $arreglo_objeto_propiedad = ArrayHelper::map(\app\models\A2ObjetoDePropiedad::find()->orderBy('operacion')->all(), 'id_operacion', 'operacion');
    //$arreglo_secciones[] = 'Todos';
    ?>
    <p>
        <?= Html::a('Nuevo Inmueble', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    
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
                    'direccion',
                    //'fecha',
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
                        'filter' => Html::activeDropDownList($searchModel, 'operacion', $arreglo_objeto_propiedad, ['prompt' => 'Todos','class' => 'form-control',]),
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
                                return $contrato_activo->hasta_mes."/".$contrato_activo->hasta_anio;
                                //return $data->operacionInmobiliaria->hasta_mes . "/" . $data->operacionInmobiliaria->hasta_anio;
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
                        'headerOptions' => ['style' => 'max-width: 100px'],
                        'template' => '{view}  {historial} {historial_excel}',
                        //'template' => '{view}  {update}   {delete}  {historial} {historial_excel}',
                        'buttons' => [
                            'historial' => function ($url, $model) {
                                $url = \yii\helpers\Url::toRoute(['a2-noticias/historial', 'propiedad_id' => $model->id_noticia,
                                            'titulo' => $model->direccion]);
                                return Html::a('<span class="fa fa-file-pdf-o"></span>', $url, [
                                            'title' => Yii::t('yii', 'Historial de la propiedad'), 'target' => '_blank'
                                ]);
                            },
                                    'historial_excel' => function ($url, $model) {
                                $url = \yii\helpers\Url::toRoute(['a2-noticias/historial-excel', 'propiedad_id' => $model->id_noticia,
                                            'titulo' => $model->direccion]);
                                return Html::a('<span class="fa fa-file-excel-o"></span>', $url, [
                                            'title' => Yii::t('yii', 'Historial de la propiedad'), 'target' => '_blank'
                                ]);
                            },
                            'imagenes'=>function ($url, $model) {
                                $model_imagenes_inmuebles = A2NoticiasImagenes::find()->where(['id_noticia' => $model->id_noticia])->one();
                                if($model_imagenes_inmuebles){
                                    $url = \yii\helpers\Url::toRoute(['a2-noticias/ver-imagenes', 'id' => $model->id_noticia,]);
                                    return Html::a('<span class="fa fa-camera"></span>', $url, [
                                                'title' => Yii::t('yii', 'Fotos del Inmueble'), 'target' => '_blank']);
                                }
                            },
                                ],
                                'visible' => (Yii::$app->user->identity->permisos != "operador"),
                            ],
                            ['class' => 'yii\grid\ActionColumn',
                                'header' => 'Operaci&oacute;n',
                                'headerOptions' => ['style' => 'max-width: 100px'],
                                'template' => '{contrato_todos}  {contrato_nuevo} {contrato_actual} ',
                                //'template' => '{contrato_todos}  {contrato_nuevo} {contrato_actual} {renovar_contrato_propiedad} ',
                                'buttons' => [
                                    'renovacion_pendiente' => function ($url, $model) {
                                        $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one(); 
                                        if (isset($contrato_activo)) {
                                        //$url = \yii\helpers\Url::toRoute(['a2-noticias/historial', 'propiedad_id' => $model->id_noticia,
                                        //            'titulo' => $model->titulo]);
                                        return Html::a('<span class="fa fa-retweet"></span>', '#', [
                                                    'title' => Yii::t('yii', 'Marcar Pendiente de Renovacion'),
                                                    'onclick'=>'renovacion_pendiente('.$contrato_activo->id_operacion_inmobiliaria.')',
                                        ]);
                                        }
                                    },
                                    'historial' => function ($url, $model) {
                                        $url = \yii\helpers\Url::toRoute(['a2-noticias/historial', 'propiedad_id' => $model->id_noticia,
                                                    'titulo' => $model->titulo]);
                                        return Html::a('<span class="fa fa-file-pdf-o"></span>', $url, [
                                                    'title' => Yii::t('yii', 'Historial de la propiedad'), 'target' => '_blank'
                                        ]);
                                    },
                                            'historial_excel' => function ($url, $model) {
                                        $url = \yii\helpers\Url::toRoute(['a2-noticias/historial-excel', 'propiedad_id' => $model->id_noticia,
                                                    'titulo' => $model->titulo]);
                                        return Html::a('<span class="fa fa-file-excel-o"></span>', $url, [
                                                    'title' => Yii::t('yii', 'Historial de la propiedad'), 'target' => '_blank'
                                        ]);
                                    },
                                            'contrato_nuevo' => function ($url, $model) {
                                                $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                        if (!isset($contrato_activo)) {
                                            $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/create', 'propiedad_id' => $model->id_noticia,
                                            ]);

                                            return Html::a('<span class="fa  fa-plus"></span>', $url, [
                                                        'title' => Yii::t('yii', 'Nuevo Contrato'), 'target' => '_blank'
                                            ]);
                                        }
                                    },
                                            'contrato_todos' => function ($url, $model) {
                                                //$contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                        //if (isset($contrato_activo)) {
                                            $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/index', 'propiedad_id' => $model->id_noticia,
                                                        ]);

                                            return Html::a('<span class="fa fa-bullseye"></span>', $url, [
                                                        'title' => Yii::t('yii', 'Todos los contratos'), 'target' => '_blank'
                                            ]);
                                        //}
                                    },
                                            'contrato_actualizar' => function ($url, $model) {
                                                $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                        if (isset($contrato_activo)) {
                                            $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/update', 'propiedad_id' => $model->id_noticia,
                                                        'id' => $contrato_activo->id_operacion_inmobiliaria]);

                                            return Html::a('<span class="fa fa-edit "></span>', $url, [
                                                        'title' => Yii::t('yii', 'Actualizar Contrato'), 'target' => '_blank'
                                            ]);
                                        }
                                    },
									'contrato_actual' => function ($url, $model) {
                                                $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                        if (isset($contrato_activo)) {
                                            $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/view', 
                                                        'id' => $contrato_activo->id_operacion_inmobiliaria]);

                                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                        'title' => Yii::t('yii', 'Contrato Vigente'), 'target' => '_blank'
                                            ]);
                                        }
                                    },
                                    'renovar_contrato_propiedad' => function ($url, $model) {
                                        $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                        if (isset($contrato_activo)) {
                                            $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/create', 
                                                        'id' => $contrato_activo->id_operacion_inmobiliaria,
                                                        'propiedad_id'=>$model->id_noticia,
                                                        'renovar'=>1]);

                                            return Html::a('<span class="fa fa-hourglass-2"></span>', $url, [
                                                        'title' => Yii::t('yii', 'Renovar Contrato'), 'target' => '_blank'
                                            ]);
                                        }
                                    },
									/*'contrato_documento' => function ($url, $model) {
                                        $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                        if (isset($contrato_activo)) {
                                            $url = \yii\helpers\Url::toRoute(['contratos-documentos/index',
                                                        'id' => $contrato_activo->id_operacion_inmobiliaria]);

                                            return Html::a('<span class="fa fa-file-text-o "></span>', $url, [
                                                        'title' => Yii::t('yii', 'Contrato '), 'target' => '_blank'
                                            ]);
                                        }
                                    },*/
									/*	'renovar_contrato' => function ($url, $model) {
                                        $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                        if (isset($contrato_activo)) {
                                            $url = \yii\helpers\Url::toRoute(['cambios-operaciones/index',
                                                        'operacion_id' => $contrato_activo->id_operacion_inmobiliaria]);

                                            return Html::a('<span class="fa fa-hourglass-2 "></span>', $url, [
                                                        'title' => Yii::t('yii', 'Renovar Contrato'), 'target' => '_blank'
                                            ]);
                                        }
                                    },*/
                                        ],
                                        'visible' => (Yii::$app->user->identity->permisos != "operador"),
                                    ],
									
                                    ['class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'visible' => (Yii::$app->user->identity->permisos == "operador"),
                                    ],
                                ],
                            ]);
                            ?>
</div>
<script type="text/javascript">
        var base_url = '<?php echo Yii::getAlias('@web') ?>';
    </script>
    <?php
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/a2-noticias/index.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
    ?>
