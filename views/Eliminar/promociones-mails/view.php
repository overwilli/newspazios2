<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\Localidades;
use app\models\PromomailLocalidades;
/* @var $this yii\web\View */
/* @var $model app\models\PromocionesMails */

$this->title = $model->asunto;
$this->params['breadcrumbs'][] = ['label' => 'Promociones por Mail', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promociones-mails-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-1"><?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?></div>
        <div class="col-md-2"><?= Html::a('Agregar promocion', ['promocion-individual/create', 'promocion_id' => $model->id], ['class' => 'btn btn-primary']) ?></div>
        <div class="col-md-7"><?= Html::a('Ejemplo', ['ver-mail-promo', 'promocion_id' => $model->id], ['class' => 'btn btn-primary', "target" => '_blank']) ?></div>
        <div class="col-md-1"><?=
            Html::a('Borrar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Esta seguro que desea borrar este registro?',
                    'method' => 'post',
                ],
            ])
            ?></div>
    </div>
    <p>



    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'asunto',
            array(
                'attribute' => 'fecha_envio',
                'value' => date('d/m/Y', strtotime($model->fecha_envio)),
            ),
            'condiciones:ntext',
            array(
                'attribute' => 'segmentacion',
                'value' => call_user_func(function ($model) {
                            if ($model->segmentacion == 0) {
                                return "NINGUNO";
                            }
                            if ($model->segmentacion == 1) {
                                return utf8_encode("POR EDAD->  Edad Mínima:" . $model->edad_minima . "  Edad Máxima:" . $model->edad_maxima);
                            }
                            if ($model->segmentacion == 2) {
                                return strtoupper(utf8_encode("POR LOCALIDAD"));
                                /* return strtoupper(utf8_encode("POR LOCALIDAD->  Codigo Postal:".$model->codigo_postal."  ".
                                  Localidades::find()->where(['id' => $model->codigo_postal])->one()->localidad)); */
                            }
                            return "";
                        }, $model),
            ),
            'estado',
        ],
    ])
    ?>

    <?php
    if ($model->segmentacion == 2) {
        $rows = PromomailLocalidades::find()->where(['promomail_id' => $model->id])->all();
        foreach ($rows as $row) {
            $model->arreglo_localidades[] = $row->localidad_id;
        }
        ?>
        <table id="tabla_localidades" class="table table-bordered">
            <th>Localidad</th>
            <th>C.P.</th>                            
            <?php
            if (isset($model->arreglo_localidades) > 0) {
                $i = 0;
                foreach ($model->arreglo_localidades as $row) {

                    if (($i % 2) == 0) {
                        $clase = "odd";
                    } else {
                        $clase = "even";
                    }
                    $localidad_row = Localidades::find()->where(['id' => $row])->one();

                    $insertar_registro = "<tr class='" . $clase . "' id='registro_" . $row . "'>";
                    $input_registro = "<input type='hidden' name='localidades_promo[]' value='" . $row . "'/>";
                    $insertar_registro .= "<td>" . $localidad_row->localidad . $input_registro . "</td>";
                    $insertar_registro .= "<td>" . $row . "</td>";
                    //$insertar_registro .= "<td ><a onclick='borrar_registro_localidad(" . $row . ")'>Remover</a></td>";
                    $insertar_registro .= "</tr>";
                    if ($model->scenario == 'update') {
                        echo $insertar_registro;
                    } else {
                        echo $insertar_registro;
                    }
                    $i++;
                }
            }
            ?>
        </table>
        <?php
    }
    ?>
    <hr/>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'enlace',
//            array(
//                'format' => 'image',
//                'attribute' => 'url_image',
//            ),
            'orden',
            'estado',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Ver'),
                            'aria-label' => Yii::t('yii', 'See'),
                            'data-pjax' => '0',
                        ];
                        $url = \yii\helpers\Url::toRoute(['promocion-individual/view', 'id' => $key]);

                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                    },
                            'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Actualizar'),
                            'aria-label' => Yii::t('yii', 'See'),
                            'data-pjax' => '0',
                        ];
                        $url = \yii\helpers\Url::toRoute(['promocion-individual/update', 'id' => $key]);

                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                    },
                            'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Borrar'),
                            'aria-label' => Yii::t('yii', 'See'),
                            'data-pjax' => '0',
                            'data' => [
                                'confirm' => 'Esta seguro que desea borrar este registro',
                                'method' => 'post',
                            ],
                        ];
                        $url = \yii\helpers\Url::toRoute(['promocion-individual/delete', 'id' => $key]);

                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                    }
                        ],
                    ],
                ],
            ]);
            ?>

</div>
