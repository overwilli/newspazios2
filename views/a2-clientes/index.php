<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\A2OperacionesInmobiliarias;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\A2ClientesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
th{
    color:#337ab7;
}
</style>
<div class="a2-clientes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php
	$arreglo_inmueble = ArrayHelper::map(\app\models\A2Noticias::find()->orderBy('direccion')->all(), 'id_noticia', 'direccion');

	?>
	
    <p>
        <?= Html::a('Nuevo Cliente', ['create'], ['class' => 'btn btn-success']) ?>
        <?php
        if (Yii::$app->user->identity->permisos != "operador") {
            Html::a('<i class="fa fa-print"></i> Listado', ['listado'], ['class' => 'btn btn-success', 'target' => '_blank']);
        }
        ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id_cliente',
            //'CUIL',
			[
                'attribute' => 'CUIL',
                'value' => function ($data) { 
					return $data->obtener_cuil();
				},
            ],
            'NOMBRE',
            //'NOMBRE_FANTASIA',
            'TELEFONO',
            'DIRECCION',
			
            // 'LOCALIDAD',
            // 'BARRIO',
            // 'PROVINCIA',
            // 'TELEFONO',
            // 'TELEF2',
            // 'TELEF3',
            // 'EMAIL:email',
            // 'NRO_CUENTA',
            // 'CUIL',
            // 'EST_CIVIL',
            // 'FECNAC',
            // 'PRIM_NUPCIAS',
            // 'DNI_CONYUGE',
            // 'NOMBRE_CONYUGE',
            // 'CUIL_CONYUGE',
            // 'OBSERVACIONES:ntext',
            ['attribute' => 'estado',
                'filter' => Html::activeDropDownList($searchModel, 'estado', ['ACTIVO' => 'ACTIVO', 'PENDIENTE' => 'PENDIENTE', 'ELIMINADO' => 'ELIMINADO'], ['prompt' => '', 'class' => 'form-control',]),
            ],
			[
				'attribute' => 'Inmueble',
                //'filter' => Html::activeDropDownList($searchModel, 'id_inmueble', $arreglo_inmueble, ['prompt' => 'Todos','class' => 'form-control',]),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_inmueble',
                    'data' => $arreglo_inmueble,
                    'options' => ['placeholder' => 'Todos'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
				//},
				'value' => function ($data) {
					$model_operacion=A2OperacionesInmobiliarias::find()->where('a2_operaciones_inmobiliarias.estado = :estado AND cod_cliente=:cod_cliente', 
						[':estado' => 'ACTIVO',':cod_cliente' => $data->id_cliente])->one();
					if ($model_operacion) {
						if($model_operacion){
							return $model_operacion->inmueble->direccion;
						}else{
							return null;
						}
					} else {
						return null;
					}
				}
			],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}   {update}   {delete}   {estado_cuenta}',
                'buttons' => [
                    'estado_cuenta' => function ($url, $model) {
                        $url = \yii\helpers\Url::toRoute(['a2-clientes/imp-estado-cuenta', 'cliente_id' => $model->id_cliente,
                                    'nombre' => $model->NOMBRE, 'dni' => $model->DNI]);
                        return Html::a('<span class="fa fa-file-pdf-o"></span>', $url, [
                                    'title' => Yii::t('yii', 'Ver Estado de Cuenta'), 'target' => '_blank'
                        ]);
                    },
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
