<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidacionpagadas */

$this->title = 'Liquidaciones en Orden de Pago';
$this->params['breadcrumbs'][] = ['label' => 'Ordenes de Pago', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidacionpagadas-create">
    <?php
    //echo Html::hiddenInput 
    echo Html::hiddenInput('orden_id', $model->id);
    ?>
    <div class="row">
        <div class="col-xs-12 col-md-9">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <label class="col-sm-5 control-label">Estado de la Orden:</label> <?php echo $model->estado ?>
                        </div>
						<div class="col-xs-12 col-md-6">
                            <label class="col-sm-5 control-label">Fecha de la Orden:</label> <?php echo date('d/m/Y', strtotime($model->fecha)) ?>
                        </div>
                    </div>                   
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <label class="col-sm-5 control-label">Propietario:</label> <?php echo $model->propietario->apellido . ", " . $model->propietario->nombre ?>
                        </div>
						<div class="col-xs-12 col-md-6">
						<label class="col-sm-5 control-label">Cuit:</label> <?php echo $model->propietario->cuit; ?>
                        </div>
                    </div>                    
                </div>
            </div>

        </div>
        
    </div>
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Liquidaciones</a></li>
			<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Expensas</a></li>
			<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Gastos</a></li>
        </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<?php
								if ($model->estado == "Nuevo") {
									?>
									<div class="box box-success form-horizontal">
										<div class="box-header with-border">
											<h3 class="box-title"><?= Html::encode("Buscador de Propiedades Pagadas") ?></h3>
										</div>
										<div class="box-body">
											<div class="form-group">
												<div class="col-xs-12 col-md-12">
													<label class="col-sm-4 control-label">Fecha de Pago:</label> 
													<?php
													$fecha = new DateTime(date('Y-m-d'));
													$fecha->modify('-1 day');
													echo \yii\jui\DatePicker::widget([
														'name' => 'fecha_pago_liq',
														'value' => $fecha->format('d-m-Y'),
														'dateFormat' => 'dd/MM/yyyy',
													]);
													?>
												</div>
											</div>
											<div class="form-group">
												<div class="col-xs-12 col-md-12">
													<label class="col-sm-4 control-label">Tener en cuenta:</label> 
													<?=
													Html::dropDownList('incluir_depositos', '', [ '' => 'Todos', '1' => 'Excluir depósitos',
														'2' => 'Solo depositos',])
													?>
												</div>
											</div>
											<div class="box-footer">
												<button id="agregar_todas_liq" type="button" class="btn btn-primary">Agregar todas Liq.</button>
												<button id="eliminar_todas_liq" type="button" class="btn btn-primary">Eliminar Liq.</button>
											</div>

										</div>
									</div>
									<?php
								}
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-md-12">
								<div class="box box-success">
									<div class="box-header with-border">
										<h3 class="box-title"><?= Html::encode("Liquidaciones...") ?></h3>
									</div>
									<div id="resultado_items" class="box-body">

									</div>
									<div class="box-footer">
										<?php
										if ($model->estado == "Nuevo") {
											?>
											<button id="cerrar_orden" type="button" class="btn btn-default">Cerrar Orden</button>  
											<!--<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
												Actualizar Gastos
											</button>-->                   
											<?php
										}
										if ($model->estado == "Cerrado" && Yii::$app->user->identity->permisos == "administrador") { ?>
											<button id="btn_registrar_pago" data-toggle="modal" data-target="#id_modal_pagar_orden" type="button" class="btn btn-default">Registrar Pago</button>
											<?php
										}
										if ($model->estado == "Pagado" && Yii::$app->user->identity->permisos == "administrador") { ?>
											<button id="btn_anular_orden" type="button" class="btn btn-default">Anular Orden Pago</button>
										
										<?php
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2">
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<?php
								if ($model->estado == "Nuevo") {
									?>
									<div class="box box-success form-horizontal">
										<div class="box-header with-border">
											<h3 class="box-title"><?= Html::encode("Buscador de Expensas") ?></h3>
										</div>
										<div class="box-body">											
											<div class="box-footer">
												<button id="agregar_todas_expensas" type="button" class="btn btn-primary">Agregar todas Expensas</button>
												<button id="eliminar_todas_expensas" type="button" class="btn btn-primary">Eliminar Expensas</button>
											</div>											

										</div>
									</div>
									<?php
								}
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-md-12">
								<div class="box box-success">
									<div class="box-header with-border">
										<h3 class="box-title"><?= Html::encode("Expensas...") ?></h3>
									</div>
									<div id="resultado_items_expensas" class="box-body">
									</div>
								</div>
							</div>
						</div>
					</div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_3">
                        <div class="row">
							<div class="col-xs-12 col-md-6">
								<?php
								if ($model->estado == "Nuevo") {
									?>
									<div class="box box-success form-horizontal">
										<div class="box-header with-border">
											<h3 class="box-title"><?= Html::encode("Buscador de Gastos") ?></h3>
										</div>
										<div class="box-body">											
											<div class="box-footer">
												<button id="agregar_todas_gastos" type="button" class="btn btn-primary">Agregar todos Gastos</button>
												<button id="eliminar_todas_gastos" type="button" class="btn btn-primary">Eliminar Gastos</button>
											</div>											

										</div>
									</div>
									<?php
								}
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-md-12">
								<div class="box box-success">
									<div class="box-header with-border">
										<h3 class="box-title"><?= Html::encode("Gastos...") ?></h3>
									</div>
									<div id="resultado_items_gastos" class="box-body">
									</div>
								</div>
							</div>
						</div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
				
				</div>
				<table>
					<tr>
						<td><label class="ancho_label totales">Alquileres Cobrados:</label></td>
						<td style="text-align: right;"><label id="label_total_dinero" class="totales"></label></td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid black;"><label class="ancho_label totales">Expensas Cobradas:</label></td>
						<td style="border-bottom:1px solid black;text-align: right;"><label id="label_total_expensas" class="totales"></label></td>
					</tr>
					<tr>
						<td><label class="ancho_label totales">Cobranzas Efectuadas:</label></td>
						<td style="text-align: right;"><label id="label_total_dinero1" class="totales"></label> </td>
					</tr>
					<tr>
						<td><label class="ancho_label totales">Importe de Comisiones:</label></td>
						<td style="text-align: right;"><label id="label_total_comision" class="totales"></label></td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid black;"><label class="ancho_label totales">Otros Gastos:</label></td>
						<td style="border-bottom:1px solid black;text-align: right;"><label id="label_total_gastos" class="totales"></label></td>
					</tr>
					<tr>
						<td><label class="ancho_label totales">Importe a Percibir:</label></td>
						<td style="text-align: right;"><label id="label_total_percibir" class="totales"></label></td>
					</tr>
				</table>
				<div>
					<?= Html::a('<i class="fa fa-history"></i> Volver',['liquidacionpagadas/index'], ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);return False;']) ?>
				</div>
    <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Otros gastos</h4>
              </div>
              <div class="modal-body">
                    <div id="respuesta_modal"></div>
                <input id='otros_gastos' type='text' />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button id="guardar_otros_gastos" type="button" class="btn btn-primary">Guardar</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div class="modal fade" id="id_modal_pagar_orden">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Registrar pago de la orden</h4>
              </div>
              <div class="modal-body">
                    <div id="respuesta_modal_pago_orden"></div>                
              </div>
              <div class="modal-footer">
                <button id="btn_cerrar" data-id="<?php echo Yii::$app->request->baseUrl.'/?r=liquidacionpagadas/para-pagar';?>" type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    <script type="text/javascript">
        var base_url = '<?php echo Yii::getAlias('@web') ?>';
	</script>
    <?php
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/ordenes_pago/ordenes_pago_detalle.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
    ?>