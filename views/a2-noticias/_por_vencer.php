<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\InmueblesPropietarios;
use app\models\Propietarios;
use yii\helpers\ArrayHelper;

?>

<?php
$arreglo_propietario = ArrayHelper::map(\app\models\Propietarios::find()->orderBy('apellido,nombre')->all(), 'id', 'apellidoNombre');

$arreglo_objeto_propiedad = ArrayHelper::map(\app\models\A2ObjetoDePropiedad::find()->orderBy('operacion')->all(), 'id_operacion', 'operacion');

?>

<?php
if($renovar==FALSE){
    echo Html::a('<i class="fa fa-file-excel-o"></i>Excel', ['a2-noticias/inmuebles-vencer-excel',], ['class' => 'btn btn-app','target'=>'_blank','data-pjax'=>'0']);
}else{
    echo Html::a('<i class="fa fa-file-excel-o"></i>Excel', ['a2-noticias/inmuebles-renovar-excel',], ['class' => 'btn btn-app','target'=>'_blank','data-pjax'=>'0']);
} ?>
<?php \yii\widgets\Pjax::begin(); ?>
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
                        'label' => 'Cliente',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            $contrato_activo=$data->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                            if ($contrato_activo) {
                                return $contrato_activo->cliente->NOMBRE;
                            } else {
                                return null;
                            }
                        },
                        //'visible'=> $renovar
                    ],
                    [
                        'label' => 'Tipo de Contrato',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            $contrato_activo=$data->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                            if ($contrato_activo) {
                                return $contrato_activo->tipo_contrato;
                            } else {
                                return null;
                            }
                        },
                        //'visible'=> $renovar
                    ], 
                    /*[
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
                    ],*/
                    [
                        'label' => 'Vencimiento',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            $contrato_activo=$data->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                            if ($contrato_activo) {
                                return $contrato_activo->hasta_mes."/".$contrato_activo->hasta_anio;
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
                        'format' => 'Currency',
                        'visible'=> $renovar
                    ],
					[
                        'label' => 'Propietario',
                        'filter' => Html::activeDropDownList($searchModel, 'propietario_id', $arreglo_propietario, ['prompt' => 'Todos','class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
							$obj_propietarios=InmueblesPropietarios::find()->where(['inmueble_id'=>$data->id_noticia])->one();                            
                            if ($obj_propietarios) {
                                return $obj_propietarios->propietario->apellido.", ".
									$obj_propietarios->propietario->nombre;
                            } else {
                                return null;
                            }
                        }
                    ],
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
                        'template' => '{view}',
                        'buttons'=>[
                            'view'=>function ($url, $model) {
                                $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                if ($contrato_activo) {
                                    $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/view',
                                                        'id' => $contrato_activo->id_operacion_inmobiliaria]);
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'Contrato Activo'), 'target' => '_blank' ]); 
                                } else {
                                    return null;
                                }                 
                               
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php \yii\widgets\Pjax::end(); ?>