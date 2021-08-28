<?php

use app\models\A2Noticias;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\GestionCobranzasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gestion Cobranzas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gestion-cobranzas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $model_inmueble=A2Noticias::find()->where(['id_noticia'=>$searchModel->inmueble_id])->one();
    
     // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php
	$arreglo_cliente = ArrayHelper::map(\app\models\A2Clientes::find()->orderBy('NOMBRE')->all(), 'id_cliente', 'NOMBRE');
	?>
    <p>
        <?= Html::a('Nueva Cobranza', ['create','id'=>$searchModel->inmueble_id,'operacion_id'=>$searchModel->operacion_id], ['prompt'=>'Seleccione el cliente','class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-liquidaciones/morosos'], ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);return False;']) ?>
    </p>
    <?php echo "<h3>Inmueble:".$model_inmueble->direccion."</h3>";?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',			
            [
                'attribute'=>'fecha_notificacion',
                'value' => function ($data) {
                    return date('d/m/Y',strtotime($data->fecha_notificacion));
                }    
            ],      
			/*['attribute'=>'cliente_id',
			'filter'=>'',
			//'filter' => Html::activeDropDownList($searchModel, 'cliente_id', $arreglo_cliente, ['class' => 'form-control',]),
			'value' => function ($data) {
                    if ($data->cliente) {
                        return $data->cliente->NOMBRE;
                    } else {
                        return null;
                    }
                }
			],
			['attribute'=>'inmueble_id',
			'filter'=>'',
			'value' => function ($data) {
                    if ($data->contrato) {
                        return $data->contrato->inmueble->direccion;
                    } else {
                        return null;
                    }
                }
			],*/         
			
            // 'operacion_id',
            'nivel',			
            'observaciones:ntext',
            [
                'attribute'=>'fecha',
                'value' => function ($data) {
                    return date('d/m/Y',strtotime($data->fecha));
                }    
            ],
			['attribute'=>'hora',
			'filter'=>'',
			'value' => function ($data) {
                    return $data->hora;
                }
			],			 

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
