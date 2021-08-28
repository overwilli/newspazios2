<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OperacionesExpensasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expensas Cargadas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operaciones-expensas-index">

	<div class="row">
        <div class="col-md-10">
    		<h1><?= Html::encode($this->title) ?></h1>
		</div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-noticias/expensas'], ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <?php
    echo DetailView::widget([
        'model' => $searchModel,
        'attributes' => [            
            ['label' => 'Cliente',
                'value' => $searchModel->contrato->cliente->NOMBRE,
            ],
            ['label' => 'Inmueble',
                'value' => $searchModel->contrato->inmueble->titulo,
            ],
            ['label' => 'Dirección',
                'value' => $searchModel->contrato->inmueble->direccion,
            ],
            //'tipo_expensas_id',
//            'inmuebles_id',
//            'mes',
//            'year',
//            'importe',
//            'estado',
//            'comprobante_id',
        ],
    ])
    ?>
	<?php
            if(isset($mensaje_expensas)){
							?>
							<div class="alert alert-info alert-dismissible">                            
								<h4><i class="icon fa fa-check"></i> Mensaje</h4>
								<?php echo utf8_encode($mensaje_expensas); ?>
							</div>
							<?php
						}
            ?>

    <?php
    //echo $searchModel->tipoExpensas->
    ?>

    <p>
        <?= Html::a('Nueva Expensa', ['create', 'operacion_id' => $searchModel->operacion_id, ], ['class' => 'btn btn-success']) ?>
    </p>
	<div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Expensas</a></li>
                    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Pendientes</a></li>
                    <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Para Aprobar</a></li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
						<?=
						GridView::widget([
							'dataProvider' => $dataProvider,
							//'filterModel' => $searchModel,
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],
								//'id',
								//'operacion_id',            
								[
									'attribute' => 'tipo_expensas_id',
									'value' => function ($data) {
										if ($data->tipoExpensas) {
											return $data->tipoExpensas->descripcion;
										} else {
											return null;
										}
									}
								],
								//'inmuebles_id',
								'mes',
								'year',
								'importe',
								'estado',
								// 'comprobante_id',
								['class' => 'yii\grid\ActionColumn',
									'template' => '{view}  {update}  {delete} ',
									'buttons' => [
										'update' => function ($url, $model) {
											if ($model->estado!='pagado') {
												$url = \yii\helpers\Url::toRoute(['operaciones-expensas/update',
															'id' => $model->id]);
												return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
															'title' => Yii::t('yii', 'Actualizar'),                                    
												]);
											}
										},
										'delete' => function ($url, $model) {
											if ($model->estado!='pagado') {
												$url = \yii\helpers\Url::toRoute(['operaciones-expensas/delete',
															'id' => $model->id]);
												return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
															'title' => Yii::t('yii', 'Eliminar'),                                    
												]);
											}
										},
									],
								],

							],
						]);
						?>
					</div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2">
						<?=
						GridView::widget([
							'dataProvider' => $dataProvider_pendiente,
							//'filterModel' => $searchModel,
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],
								//'id',
								//'operacion_id',            
								[
									'attribute' => 'tipo_expensas_id',
									'value' => function ($data) {
										if ($data->tipoExpensas) {
											return $data->tipoExpensas->descripcion;
										} else {
											return null;
										}
									}
								],
								//'inmuebles_id',
								'mes',
								'year',
								'importe',
								'estado',
								// 'comprobante_id',
								['class' => 'yii\grid\ActionColumn',
									'template' => '{view}  {update}  {delete} ',
									'buttons' => [
										'update' => function ($url, $model) {
											if ($model->estado!='pagado') {
												$url = \yii\helpers\Url::toRoute(['operaciones-expensas/update',
															'id' => $model->id]);
												return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
															'title' => Yii::t('yii', 'Actualizar'),                                    
												]);
											}
										},
										'delete' => function ($url, $model) {
											if ($model->estado!='pagado') {
												$url = \yii\helpers\Url::toRoute(['operaciones-expensas/delete',
															'id' => $model->id]);
												return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
															'title' => Yii::t('yii', 'Eliminar'),                                    
												]);
											}
										},
									],
								],

							],
						]);
						?>
					</div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_3">
						<?php 			
						
						if($expensas_pendientes){
							$form = ActiveForm::begin(['action'=>["operaciones-expensas/confirmar-expensas"]]);
							?>
							<input type="hidden" name="operacion_id" value="<?php echo $searchModel->operacion_id;?>" />
							<input type="hidden" name="propiedad_id" value="<?php echo $searchModel->inmuebles_id;?>" />
							 <table class="table"  border="0" cellspacing="4" cellpadding="0" align="center" >  
								<tr> 
									<td colspan="4"> <div class="seccion">Expensas Pendientes de Aprobación</div>
									</td>	
								</tr>
								<tr id="cabezera-tabla">
									<td><input type="checkbox" value="" id="marcar_todos" /><label>Todos</label></td>
									<td >EXPENSA</td>	  
									<td >PERIODO</td>

									<td >IMPORTE</td>									                    
								</tr>
								<?php
								foreach($expensas_pendientes as $row) {
									echo "<tr>";
									echo "<td><input class='periodos_pendientes' type='checkbox' name='expensas_pendientes[]' value='" . $row['id'] . "' /></td>";
									echo "<td>" . $row['descripcion'] . "</td>";
									echo "<td>" . $row['mes'] . "/" . $row['year'] . "</td>";

									echo "<td><b>$ " . $row['importe'] . "</b></td>";									
									echo "</tr>";
								}
								?>
							</table>							
							<div class="form-group">
								<input class="btn btn-primary" type="submit" name="Guardar" value="Confirmar Pendientes"/>
								<input class="btn btn-danger" type="submit" name="Anular" value="Anular Seleccionados"/>
							</div>
							<?php
							ActiveForm::end();
						}
						?>
					</div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
</div>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/operaciones-expensas/index.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>