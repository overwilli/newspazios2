<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\A2NoticiasImagenes;
?>
<?php
$arreglo_objeto_propiedad = ArrayHelper::map(\app\models\A2ObjetoDePropiedad::find()->orderBy('operacion')->all(), 'id_operacion', 'operacion');

echo Html::a('<i class="fa fa-file-excel-o"></i>Excel', ['a2-noticias/inmuebles-disponibles-excel',], ['class' => 'btn btn-app']);
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
				'codigo_postal',
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
                    'header'=>'Ficha',
                    'template' => '{view} {gestion_llaves}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            $url = \yii\helpers\Url::toRoute(['a2-noticias/ver-imagenes', 'id' => $model->id_noticia,]);
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'Ver'), /*'target' => '_blank'*/]);
                            },
                        'gestion_llaves' => function ($url, $model) {
                                $url = \yii\helpers\Url::toRoute(['llaves/index', 'id' => $model->id_noticia,]);
                                return Html::a('<span class="fa fa-key"></span>', $url, [
                                            'title' => Yii::t('yii', 'Gestion de Llaves'), 'target' => '_blank']);
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
                        ],
                    ],
                ]);
?>