<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\models\Localidades;
use app\models\Provincias;

/* @var $this yii\web\View */
/* @var $model app\models\A2OperacionesInmobiliarias */

$obj_localidad=Localidades::find()->where(['id'=>$model->inmueble->localidad_id])->one();
$localidad="";
if($obj_localidad){
	$localidad=$obj_localidad->nombre;
}
$obj_provincia=Provincias::find()->where(['id'=>$model->inmueble->provincia_id])->one();
$provincia="";
if($obj_localidad){
	$provincia=$obj_localidad->nombre;
}
$this->title = $model->inmueble->direccion." - ".$provincia." - ".$localidad;
$this->params['breadcrumbs'][] = ['label' => 'Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_operacion_inmobiliaria, 'url' => ['view', 'id' => $model->id_operacion_inmobiliaria]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="a2-operaciones-inmobiliarias-update">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body"> 
			<?php
            if(isset($mensaje_periodos)){
							?>
							<div class="alert alert-info alert-dismissible">                            
								<h4><i class="icon fa fa-check"></i> Mensaje</h4>
								<?php echo utf8_encode($mensaje_periodos); ?>
							</div>
							<?php
						}
            ?>
            <?php 
            $clase1="";
            $clase2="";
            $clase3="";
            $clase1_body="";
            $clase2_body="";
            $clase3_body="";
        
            switch($tab){
                case 'periodos':{
                    $clase2='class="active"';
                    $clase2_body="active";
                };break;
                default:{$clase1='class="active"'; $clase1_body="active";}break;
            }
            ?>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li <?php echo $clase1;?>><a href="#tab_1" data-toggle="tab" aria-expanded="true">Datos Generales</a></li>
                    <li <?php echo $clase2;?>><a href="#tab_2" data-toggle="tab" aria-expanded="false">Per&iacute;odo de Cobro</a></li>
                    <li <?php echo $clase3;?>><a href="#tab_3" data-toggle="tab" aria-expanded="false">Periodo Pendientes</a></li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane <?php echo $clase1_body?>" id="tab_1">
                        <?=
                        $this->render('_form', [
                            'model' => $model
                        ]);
                        ?>
                    </div>
                    <!-- /.tab-pane -->
                    <<div class="tab-pane <?php echo $clase2_body?>" id="tab_2">
                    
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <?php echo "<label  class='control-label'>LOCATARIO:".(empty($model->cliente) ? '' : $model->cliente->NOMBRE)."</label>";?>
                            </div>
                            <div class="col-xs-12 col-md-6">
                            <?php echo '<label id="locador_dni" class="control-label" for="locador_dni">Propietario:';                           
                            echo empty($model->locadorPropietario) ? '' : $model->locadorPropietario->apellido.", ".$model->locadorPropietario->nombre;
                            echo '</label>'; ?>
                            </div>
                        </div>
                        <?= Html::a('Nuevo Periodo', ['a2-operaciones-items/create', 'id_operacion' => $model->id_operacion_inmobiliaria], ['class' => 'btn btn-primary flat']) ?>

                        <?= Html::a('<i class="glyphicon glyphicon-print"></i> Pagaré', 
                        ['a2-operaciones-inmobiliarias/todos-pagare', 
                        'id' => $model->id_operacion_inmobiliaria], ['class' => 'btn btn-primary flat','target'=>'_blank']) ?>

                        <?= Html::a('<i class="fa fa-file-excel-o"></i> Resumen de Contrato', 
                        ['a2-operaciones-inmobiliarias/planilla-periodos-excel', 
                        'id' => $model->id_operacion_inmobiliaria], ['class' => 'btn btn-primary flat','target'=>'_blank']) ?>
                        <?php //\yii\widgets\Pjax::begin(); ?>
                        <?=
                        GridView::widget([
                            
                            'dataProvider' => $dataprovider_periodos,
                            
                            'columns' => [
                                //['class' => 'yii\grid\SerialColumn'],
                                'mes',
                                'anio',
                                [
                                    'attribute' => 'A',
                                    'format' => 'Currency',
                                    'value' => function($data) {
                                        if (empty($data['A'])) {
                                            return 0;
                                        } else {
                                            return $data['A'];
                                        }
                                    }
                                ],
                                [
                                    'attribute' => 'B',
                                    'format' => 'Currency',
                                    'value' => function($data) {
                                        if (empty($data['B'])) {
                                            return 0;
                                        } else {
                                            return $data['B'];
                                        }
                                    }
                                ],
                                [
                                    'attribute' => 'X',
                                    'format' => 'Currency',
                                    'value' => function($data) {
                                        if (empty($data['X'])) {
                                            return 0;
                                        } else {
                                            return $data['X'];
                                        }
                                    }
                                ],
                                /*[
                                    'label' => 'IVA',
                                    'format' => 'Currency',
                                    'value' => function($data) {
                                        if ($data['excento']==1) {
                                            return $data['B'];
                                        } else {
                                            return $data['A']*0.21+$data['B']*0.21;
                                        }
                                    }
                                ],*/
                                [
                                    'attribute' => 'D',
                                    'format' => 'Currency',
                                    'value' => function($data) {
                                        if (empty($data['D'])) {
                                            return 0;
                                        } else {
                                            return $data['D'];
                                        }
                                    }
                                ],
                                [
                                    'attribute' => 'C',
                                    'format' => 'Currency',
                                    'value' => function($data) {
                                        if (empty($data['C'])) {
                                            return 0;
                                        } else {
                                            return $data['C'];
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Total',
                                    'format' => 'Currency',
                                    'value' => function($data) {
                                        return $data['A'] + $data['B'] + $data['X'] + $data['D'] + $data['C'];
                                    }],
                                ['class' => 'yii\grid\ActionColumn',
                                    'template' => '{view} {pagare_mes} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            $url = \yii\helpers\Url::toRoute(['a2-operaciones-items/index',
                                                        'id_operacion' => $model['id_operacion'],
                                                        'anio' => $model['anio'], 'mes' => $model['mes']]);
                                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                        'title' => Yii::t('yii', 'Ver'),
                                            ]);
                                        },
                                        'pagare_mes' => function ($url, $model) {
                                            if($model['D']){
                                                $url = \yii\helpers\Url::toRoute(['a2-operaciones-inmobiliarias/pagare-mes',
                                                            'id' => $model['id_operacion'],
                                                            'anio' => $model['anio'], 'mes' => $model['mes']]);
                                                return Html::a('<span class="glyphicon glyphicon-print"></span>', $url, [
                                                            'title' => Yii::t('yii', 'Pagare del Mes'), 'target' => '_blank'
                                                ]);
                                            }
                                        },
                                        'delete' => function ($url, $model) {
                                            
                                                $url = \yii\helpers\Url::toRoute(['a2-operaciones-items/eliminar-todos-periodos',
                                                            'id_operacion' => $model['id_operacion'],
                                                            'anio' => $model['anio'], 'mes' => $model['mes']]);
                                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                                            'title' => Yii::t('yii', 'Pagare del Mes'), 
                                                ]);
                                            
                                        },
                                            ],
                                        ],
                                    ],
                                ]);
                                ?>
                            <?php //\yii\widgets\Pjax::end(); ?>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane <?php echo $clase3_body?>" id="tab_3">
                        <?= Html::a('Nuevo Periodo', ['a2-operaciones-items/create', 
                        'id_operacion' => $model->id_operacion_inmobiliaria,'estado'=>'pendiente'], ['class' => 'btn btn-primary flat']) ?>
                        <?php 	
                        $bandera_contrato_pendiente=TRUE;		
						
						if($models_items){
                            $form = ActiveForm::begin(['action'=>["a2-operaciones-inmobiliarias/confirmar-periodos"]]);
                            if($model_co){                            
                                ?>
                                
                                <input class="periodos_pendientes" type="checkbox" 
                                    value="<?php echo $model_co->id ?>" name="cambio_operacion_id" />                            
                                <?php
                                
                                echo '<label class="control-label" for="a2operacionesinmobiliarias-locador">Contrato a renovar desde '.
                                $model_co->desde_mes.'/'.$model_co->desde_anio.' hasta '.$model_co->hasta_mes.'/'.$model_co->hasta_anio.'</label>';
                                                           
    
                            }
							?>
							<input type="hidden" name="operacion_id" value="<?php echo $model->id_operacion_inmobiliaria;?>" />
							<input type="hidden" name="propiedad_id" value="<?php echo $model->inmueble->id_noticia;?>" />
							<table class="table"  width="650" border="0" cellspacing="4" cellpadding="0" align="center" >
								<tr> 
									<td colspan="4"> <div class="seccion">Períodos de cobro Pendientes de Aprobación</div>
									</td>	
								</tr>		

								<tr id="cabezera-tabla1"> 
									<th><input type="checkbox" value="" id="marcar_todos" />TODOS</th>
									<th height="36">Período</th>
									<th>Tipo Doc</th>
									<th>Monto</th>
								</tr>
								<?php
								$estilo = "filapar";

								$contador = 1;
								$periodo_mostrar = "";
								foreach($models_items as $row) {
									if ($estilo == "filapar")
										$estilo = "filaimpar";
									else
										$estilo = "filapar";
									?>
									<tr class="<?php print $estilo; ?>" onmouseover="this.className = 'filaresaltada';" onmouseout="this.className = '<?php print $estilo; ?>';"> 
										<td width="50"> &nbsp; 
											<input class="periodos_pendientes" data-periodo="<?php echo $row["id_item"] ?>" type="checkbox" value="<?php echo $row["id_item"] ?>" name="periodos_pendientes_id[]" />                            
										</td>
										<td valign="top">
											<?php
											$aux_periodo = $row["anio"] . '/' . $row["mes"];
											if ($periodo_mostrar != $aux_periodo) {
												print $row["mes"] . '/<b>' . $row["anio"] . '</b>';
												$periodo_mostrar = $aux_periodo;
											} else
												print '';
											?>
										</td>

										<td valign="top"><?php print $row["descripcion"]; ?></td>
										<td valign="top"><?php print $row["monto"]; ?></td>                        
										<td width="200"> &nbsp; 

											<?php
											$url1 = \yii\helpers\Url::toRoute(['a2-operaciones-items/update',
                                                        'id' => $row["id_item"]]);
											echo Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url1, [
                                                        'title' => Yii::t('yii', ''), 'target' => '_blank'
                                            ]);
											$url2 = \yii\helpers\Url::toRoute(['a2-operaciones-items/delete',
                                                        'id' => $row["id_item"]]);
											echo Html::a('<span class="glyphicon glyphicon-trash"></span>', $url2, [
                                                        'title' => Yii::t('yii', ''), 'target' => '_blank'
                                            ]);
											//print' <a href="periodos_form_renovaciones.php?id_item=' . $row["id_item"] . '&id_operacion=' . $row["id_operacion"] . '&accion=editar&formulario=periodos_pendientes"><span class="glyphicon glyphicon-pencil"></span></a> ';											
											//print' <a href="periodos_form_renovaciones.php?id_item=' . $row["id_item"] . '&id_operacion=' . $row["id_operacion"] . '&accion=borrar&formulario=periodos_pendientes"><span class="glyphicon glyphicon-trash"></span></a> ';
											?>

										</td>
									</tr>
									<?php
									$contador++;
								}
								?>                  
							</table>
							<div class="form-group">
								<input class="btn btn-primary" type="submit" name="Guardar" value="Confirmar Pendientes"/>
								<input class="btn btn-danger" type="submit" name="Anular" value="Anular Seleccionados"/>
							</div>
							<?php
							ActiveForm::end(); 
						}else{
							echo "<p><b>No existen periodos pendientes de aprobar.</b></p>";
						}
						?>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/a2-operacion-inmobiliarias/update.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>