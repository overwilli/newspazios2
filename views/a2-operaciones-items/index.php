<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2OperacionesItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Periodos de Cobro de ' . $searchModel->operacionInmobiliaria->inmueble->direccion;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-operaciones-items-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Periodo', ['create','id_operacion'=>$searchModel->id_operacion], ['class' => 'btn btn-success']) ?>
        <?=
        Html::a('Volver al contrato', ['a2-operaciones-inmobiliarias/update',
            'propiedad_id' => $searchModel->operacionInmobiliaria->cod_propiedad, 'id' => $searchModel->id_operacion], ['class' => 'btn btn-default'])
        ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id_factura',
                'value' => function($data) {
                    return $data->tipoFactura->descripcion;
                }
            ],
            'anio',
            'mes',                        
            [
                'attribute'=>'monto',
                'format' => 'Currency',
            ],
			'estado',  
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
