<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

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
    //$arreglo_secciones[] = 'Todos';
    ?>
    <p>
        <?= Html::a('Nuevo Inmueble', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $grid) {
            //print_r($model);
            $aux_fecha_actual = (date("Y") * 10) + date("m");
            //print_r($model->operacionInmobiliaria);
            if (isset($model['hasta_anio'])) {
                $aux_fecha_vencimiento = ($model['hasta_anio'] * 10) + $model['hasta_mes'];
            } else {
                $aux_fecha_vencimiento = "";
            }
            if ($aux_fecha_vencimiento != "") {// si tiene operacion vinculada y fecha de vencimiento
                if ((($aux_fecha_vencimiento - $aux_fecha_actual) <= 2) && (($aux_fecha_vencimiento - $aux_fecha_actual) >= 0 ))
                    return ['class' => 'warning'];

                if (($aux_fecha_vencimiento - $aux_fecha_actual) < 0) {
                    if ($model['hasta_anio'] > date("Y")) {
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
                    return $data['seccion'];
                },
                    ],
                    [
                        'attribute' => 'operacion',
                        //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                        //},
                        'value' => function ($data) {
                            if ($data['operacion']) {
                                return $data['operacion'];
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
                            if ($data['hasta_anio']) {
                                return $data['hasta_mes']."/".$data['hasta_anio'];
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
                    'precio',
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
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
</div>
