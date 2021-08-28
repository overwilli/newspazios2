<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cambios Operaciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cambios-operaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Renovacion Contrato', ['cambios-operaciones/create',
            'operacion_id'=>$model_operacion->id_operacion_inmobiliaria], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Contrato Actual</a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Contratos</a></li>
                <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Pendientes</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
					<div class="row">
						<div class="col-xs-12 col-md-3">
							<label>Desde     <label>
							<?php echo $model_operacion->desde_mes."/".$model_operacion->desde_anio; ?>							
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-3">
							<label>Hasta      <label>
							<?php echo $model_operacion->hasta_mes."/".$model_operacion->hasta_anio; ?>	
						</div>
					</div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            //'id',
                            //'operacion_id',
                            'desde_mes',
                            'desde_anio',
                            'hasta_mes',
                            'hasta_anio',                            
                            'estado',
                            'fecha',
                            'usuario',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
                <div class="tab-pane" id="tab_3">
                <p>
                    <?= Html::a('Nuevo Periodo', ['a2-operaciones-items/create',
                        'id_operacion'=>$model_operacion->id_operacion_inmobiliaria,'renovar'=>TRUE], ['class' => 'btn btn-info']) ?>
                </p>
                <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider_periodos,
                        //'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            
                            'anio',
                            'mes',            
                            [
                                'attribute' => 'id_factura',
                                'value' => function($data) {
                                    return $data->tipoFactura->descripcion;
                                }
                            ],                            
                            
							['attribute' => 'monto',
								'format' => 'Currency',],
                            'estado',                        
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]);
                    ?>
                </div>
            </div>
    </div>    
</div>
