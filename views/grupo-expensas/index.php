<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\GrupoExpensasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$arreglo_tipo_expensas = ArrayHelper::map(\app\models\TipoExpensas::find()->orderBy('descripcion')->all(), 'id', 'descripcion');

$this->title = 'Grupo Expensas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupo-expensas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Expensas por Grupo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',            
            [
                'attribute' => 'grupo_id',
                'value' => function ($data) {
                    if ($data->grupos) {
                        return $data->grupos->descripcion;
                    } else {
                        return null;
                    }
                }
            ],
            [
                'attribute' => 'tipo_expensa_id',
                'filter' => Html::activeDropDownList($searchModel, 'tipo_expensa_id', $arreglo_tipo_expensas, ['prompt' => 'Todos','class' => 'form-control',]),
                'value' => function ($data) {
                    if ($data->tipoExpensas) {
                        return $data->tipoExpensas->descripcion;
                    } else {
                        return null;
                    }
                }
            ],            
            'mes',
            'year',            
            [            
                'attribute' => 'importe',
                'format'=>'Currency',
            ],
            'expensas_por',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}  {update}   {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        $url = \yii\helpers\Url::toRoute(['grupo-expensas/view', 'id' => $model->id,
                                    ]);
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('yii', 'Ver'), 
                        ]);
                    },
                    'update' => function ($url, $model) {

                        if($model->expensas_por=='GRUPO'){
                            $url = \yii\helpers\Url::toRoute(['grupo-expensas/update', 'id' => $model->id,]);
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('yii', 'Actualizar'), 
                            ]);
                        }else{
                            return false;
                        }
                    },
                    'delete' => function ($url, $model) {
                        
                            $url = \yii\helpers\Url::toRoute(['grupo-expensas/delete', 'id' => $model->id,]);
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Yii::t('yii', 'Eliminar'),'data' => [
                                            'confirm' => 'Esta seguro que desea borrar este registro?',
                                            'method' => 'post',
                                        ],
                            ]);
                        
                    },
                ],
            ],
        ],
    ]); ?>
</div>
