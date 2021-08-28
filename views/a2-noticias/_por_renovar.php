<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\InmueblesPropietarios;
use app\models\Propietarios;
use yii\helpers\ArrayHelper;

?>

<?php
	$arreglo_propietario = ArrayHelper::map(\app\models\Propietarios::find()->orderBy('apellido,nombre')->all(), 'id', 'apellidoNombre');

	?>
<?php
$arreglo_objeto_propiedad = ArrayHelper::map(\app\models\A2ObjetoDePropiedad::find()->orderBy('operacion')->all(), 'id_operacion', 'operacion');

?>
<?php
if($renovar==FALSE){
    echo Html::a('<i class="fa fa-file-excel-o"></i>Excel', ['a2-noticias/inmuebles-vencer-excel',], ['class' => 'btn btn-app']);
}else{
    echo Html::a('<i class="fa fa-file-excel-o"></i>Excel', ['a2-noticias/inmuebles-renovar-excel',], ['class' => 'btn btn-app']);
} ?>
<?php \yii\widgets\Pjax::begin(); ?>
<?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $grid) {

            $aux_fecha_actual = (date("Y") * 10) + date("m");
            //print_r($model->operacionInmobiliaria);
            //$contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
            $contrato_activo=$model;
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
                
                    [
                        'attribute' => 'direccion',
                        'filter'=>Html::activeInput('text', $searchModel, 'direccion'),
                        
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            return $data->inmueble->direccion;
                            //$contrato_activo=$data->getOperacionInmobiliaria()->where("estado='PENDIENTE' OR estado='ACTIVO' OR estado='CONFIRMADO'")->one();
                            /*if ($contrato_activo) {
                                return $data->direccion."(".$contrato_activo->estado.")";
                            } else {
                                ret*urn null;
                            }*/
                        },
                        'visible'=> $renovar
                    ],
                    [
                        'attribute' => 'codigo_postal',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            return $data->inmueble->codigo_postal;                            
                        },
                        'visible'=> $renovar
                    ],
                    [
                        'label' => 'Cliente',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            return $data->cliente->NOMBRE;                            
                        },
                        'visible'=> $renovar
                    ],
                    [
                        'label' => 'Teléfono',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            return $data->cliente->TELEFONO;  
                        },
                        'visible'=> $renovar
                    ],
                    [
                        'label' => 'Tipo de Contrato',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            return $data->tipo_contrato;                            
                        },
                        'visible'=> $renovar
                    ],
                    [
                        'attribute' => 'ultimo_contacto',
                        'filter'=>'',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            return ($data->ultimo_contacto)?date('d/m/Y H:i:s',strtotime($data->ultimo_contacto)):"";                            
                        },
                        'visible'=> $renovar
                    ],
                    /*'codigo_postal',
                    [
                        'label' => 'Cliente',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            $contrato_activo=$data->getOperacionInmobiliaria()->where("estado='PENDIENTE'")->one();
                            if ($contrato_activo) {
                                return $contrato_activo->cliente->NOMBRE;
                            } else {
                                return null;
                            }
                        },
                        'visible'=> $renovar
                    ],
                    [
                        'label' => 'Teléfono',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            $contrato_activo=$data->getOperacionInmobiliaria()->where("estado='PENDIENTE'")->one();
                            if ($contrato_activo) {
                                return $contrato_activo->cliente->TELEFONO;
                            } else {
                                return null;
                            }
                        },
                        'visible'=> $renovar
                    ],
                    [
                        'label' => 'Tipo de Contrato',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            $contrato_activo=$data->getOperacionInmobiliaria()->where("estado='PENDIENTE'")->one();
                            if ($contrato_activo) {
                                return $contrato_activo->tipo_contrato;
                            } else {
                                return null;
                            }
                        },
                        'visible'=> $renovar
                    ],                    
                    [
                        'label' => 'Fecha de Renovacion',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            $contrato_activo=$data->getOperacionInmobiliaria()->where("estado='PENDIENTE'")->one();
                            if ($contrato_activo) {
                                return ($contrato_activo->fecha_firma_contrato)?date("d/m/Y",
                                    strtotime($contrato_activo->fecha_firma_contrato)):"";
                            } else {
                                return null;
                            }
                        }
                    ],        */
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
                    /*['attribute' => 'precio',
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
                    ],*/
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
                        'template' => '{view} {contrato_firmado} {nota}',
                        'buttons' => [
                            'view'=>function ($url, $model) {
                                $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/view',
                                                        'id' => $model->id_operacion_inmobiliaria]);
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'Contrato '), 'target' => '_blank' ]); 
                                /*$contrato_pendiente=$model->getOperacionInmobiliaria()->where("estado='PENDIENTE' OR estado='ACTIVO' OR estado='CONFIRMADO'")->one();
                                if ($contrato_pendiente) {
                                    $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/view',
                                                        'id' => $contrato_pendiente->id_operacion_inmobiliaria]);
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'Contrato '), 'target' => '_blank' ]);                                   
                                } else {
                                    return null;
                                }        */         
                               
                            },
                            'contrato_firmado'=>function ($url, $model) {
                                return Html::a('<span class="fa fa-check-square-o"></span>', '#', [
                                    'title' => Yii::t('yii', 'Firmar Contrato'),
                                    'onclick'=>'firmar_contrato('.$model->id_operacion_inmobiliaria.')',
                                        ]);
                                /*$contrato_pendiente=$model->getOperacionInmobiliaria()->where("estado='PENDIENTE'")->one();
                                if ($contrato_pendiente) {
                                    return Html::a('<span class="fa fa-check-square-o"></span>', '#', [
                                        'title' => Yii::t('yii', 'Firmar Contrato'),
                                        'onclick'=>'firmar_contrato('.$contrato_pendiente->id_operacion_inmobiliaria.')',
                                            ]);
                                } else {
                                    return null;
                                }        */         
                               
                            },
                            'nota'=>function ($url, $model) {
                                $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/nota',
                                    'id' => $model->id_operacion_inmobiliaria]);
                                    return Html::a('<span class="glyphicon glyphicon-text-width"></span>', $url, [
                                        'title' => Yii::t('yii', 'Grabar una nota '), 'target' => '_blank' ]);
                                /*$contrato_pendiente=$model->getOperacionInmobiliaria()->where("estado='PENDIENTE'")->one();
                                if ($contrato_pendiente) {                                    
                                    $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/nota',
                                    'id' => $contrato_pendiente->id_operacion_inmobiliaria]);
                                    return Html::a('<span class="glyphicon glyphicon-text-width"></span>', $url, [
                                        'title' => Yii::t('yii', 'Grabar una nota '), 'target' => '_blank' ]);
                                } else {
                                    return null;
                                }*/
                                
                            }
                        ],
                    ],
                ],
            ]);
            ?>
            <?php \yii\widgets\Pjax::end(); ?>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/a2-noticias/inmuebles_vencer.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>
