<?php

use app\models\A2Caja;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
?>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Rendici&oacute;n de Caja</h3>
            </div>
            <!-- /.box-header -->
            <div class="row">
                <div class="col-xs-12 col-md-12">                    
                    <div id="errores_cierre_caja" class="callout callout-danger">
                        <h4>Error</h4>

                        <p id="data_errores_cierre_caja"></p>
                    </div>
                </div>
            </div>
            <?php echo  Html::beginForm('index.php?r=a2-caja/cerrar-caja','POST', ['class' => "form-horizontal",'id'=>'form_cierre_caja']); ?>
                <div class="box-body">
                    <div class="form-group">
                        <label for="caja_dia_siguiente" class="col-sm-5 control-label">Dinero en caja: $ </label>

                        <div class="col-sm-7">

                            <input class="form-control" name="caja_dia_siguiente" type="text" id="caja_dia_siguiente" placeholder="Caja del dia siguiente"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="caja_dia_siguiente" class="col-sm-5 control-label">Monto a Rendir: $ </label>

                        <div class="col-sm-7">

                            <input class="form-control" name="rendicion" type="text" id="rendicion" placeholder="Rendici&oacute;n"/>
                        </div>
                    </div>   
                </div>
                <div class="box-footer">                    
                    <input name="caja_inicio" id="caja_inicio"  type="hidden" value="<?php print $model_caja->inicio; ?>"/>
                    <input name="cobranzas_efectivo" id="cobranzas_efectivo" type="hidden" value="<?php print $vector[1]; ?>"/>
                    <input name="intereses_mora"  type="hidden" id="intereses_mora" value="<?php print $vector[7]; ?>"/>
                    <input name="cheques"  type="hidden" id="cheques" value="<?php print $vector[2]; ?>"/>
                    <input name="depositos" type="hidden" id="depositos" value="<?php print $vector[3]; ?>"/>
                    <input name="retenciones"  type="hidden" id="retenciones" value="<?php print $vector[4]; ?>"/>
                    <input name="ingresos_varios"  type="hidden" id="ingresos_varios" value="<?php print $vector[8]; ?>"/>
                    <input name="gastos_varios"  type="hidden" id="gastos_varios" value="<?php print $vector[6]; ?>"/>
                    <input name="supermercado" type="hidden" id="supermercado" value="<?php print $vector[5]; ?>"/>
                    <input name="caja_final"  type="hidden" id="caja_final" value="<?php print $efectivo_en_caja; ?>"/>
                    <input name="fecha_consulta" type="hidden" id="fecha_consulta" value="<?php //print $fecha_consulta;         ?>" />
                    <input name="id_caja" type="hidden" id="id_caja" value="<?php print $model_caja->id_caja; ?>" />
                    <input id="cierre_caja" type="submit" class="btn btn-info pull-right" value="CERRAR CAJA" />
                </div>
            <?php echo Html::endForm(); ?>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Movimientos de Caja</h3>
            </div>
            <!-- /.box-header -->
            <div class="row">
                <div class="col-xs-12 col-md-12">                    
                    <div id="errores_movimientos_caja" class="callout callout-danger">
                        <h4>Error</h4>

                        <p id="data_errores_movimientos_caja"></p>
                    </div>
                </div>
            </div>
            <form class="form-horizontal" name="cierre_caja"  id="cierre_caja"  method="post" target="Iframe1">
                <div class="box-body">
                    <div class="form-group">
                        <label for="caja_dia_siguiente" class="col-sm-4 control-label">Movimiento</label>

                        <div class="col-sm-8">
                            <select class="form-control" name="selectmovimientos" id="selectmovimientos" onchange="cambiar_interface_movimiento()">
                                <option value="8" selected="selected">Ingresos Varios</option>
                                <option value="6">Gastos Varios</option>
                                <!--<option value="5">Supermercado</option>-->
                                <option value="10">Transferencias  entre Cajas</option>
                            </select>
                        </div>
                    </div>
                    <div id="form_cajas_abiertas" class="form-group">
                        <label for="caja_dia_siguiente" class="col-sm-4 control-label">Cajas Abiertas:</label>

                        <div class="col-sm-8">
                            <?php
                            $obj_caja = new A2Caja();
                            $resultado = $obj_caja->obtener_cajas_abiertas();
                            ?>
                            <select class="form-control" name="cajas_abiertas" id="cajas_abiertas" >
                                <?php
                                foreach ($resultado as $row) {
                                    if ($row['numero_caja'] != Yii::$app->user->identity->numero_caja) {
                                        echo "<option value='" . $row['numero_caja'] . "'>" . $row['numero_caja'] . "-" . $row['nikname'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>   
					
					<div id="control_propiedades" class="form-group">
                        <label for="propiedad_id" class="col-sm-4 control-label">Vinculo: </label>

                        <div class="col-sm-8">
                            <?php
							$arreglo_inmuebles = ArrayHelper::map(\app\models\A2OperacionesInmobiliarias::ObtenerInmueblesConContratosActivos(), 'id_operacion_inmobiliaria', 'direccion');
							//echo $form->field($model, 'id_operacion')->dropDownList($arreglo_inmuebles, ['options' => [1 => ['selected' => TRUE]],]);

							echo Select2::widget([
								'data' => $arreglo_inmuebles,
								'id' => 'propiedad_id',
								'name' => 'propiedad_id',
								'language' => 'es',
								'options' => ['placeholder' => 'Seleccione un inmueble'],
								'pluginOptions' => [
									'allowClear' => true
								],
							]);
							?>
                        </div>
                    </div> 
					
                    <div class="form-group">
                        <label for="caja_dia_siguiente" class="col-sm-4 control-label">Importe: $ </label>

                        <div class="col-sm-8">
                            <input class="form-control" name="importe_movimiento" type="text" id="importe_movimiento" />
                        </div>
                    </div>  
                    <div class="form-group">
                        <label for="caja_dia_siguiente" class="col-sm-4 control-label">Observaciones:</label>

                        <div class="col-sm-8">
                            <textarea class="form-control" id="observaciones_ingreso_egreso" name="observaciones_ingreso_egreso" cols="50"></textarea>
                        </div>
                    </div>                      
                </div>
                <div class="box-footer">                                      
                    <input name="fecha_movimiento" type="hidden" id="fecha_movimiento" value="<?php //print $fecha_consulta;         ?>" />
                    <button name='registar_mov' type="button" class="btn btn-info pull-right">REGISTRAR MOVIMIENTO</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Arqueo de Caja</h4>
      </div>
      <div id="content_modal" class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
          <button id="cerrar_modal" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->