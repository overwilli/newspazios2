<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\A2OperacionesInmobiliarias */

$this->title = $model->inmueble->direccion;
$this->params['breadcrumbs'][] = ['label' => 'Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-operaciones-inmobiliarias-view">

<div class="row">
    <div class="col-md-10">
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_operacion_inmobiliaria], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Borrar', ['delete', 'id' => $model->id_operacion_inmobiliaria], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Documentos del Contrato', ['contratos-documentos/index', 'id' => $model->id_operacion_inmobiliaria], ['class' => 'btn btn-primary']) ?>
        <?php
        
                    
        if($model->estado=='ACTIVO'){ ?>            
            <?= Html::a('<i class="fa fa-hourglass-2"></i>Renovar Contrato', ['a2-operaciones-inmobiliarias/create', 
                'id' => $model->id_operacion_inmobiliaria,'propiedad_id'=>$model->cod_propiedad,'renovar'=>1
                ], ['class' => 'btn btn-app']) ?>
            
        <?php
        }
        ?>
        
        <?= Html::a('<i class="fa  fa-plus"></i>Nuevo Contrato', ['a2-operaciones-inmobiliarias/create', 
                'propiedad_id'=>$model->cod_propiedad,
                ], ['class' => 'btn btn-app']) ?>

    </div>
    <div class="col-md-2 offset-md-10">
        <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-noticias/index'], ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>    
    </div>
</div>

<div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">

                            <?php
                            echo '<label  class="control-label" for="locador_telefono">Locador</label>'; 
                            echo '<label id="locador_dni" class="form-control" for="locador_dni">';
                            //$cityDesc = empty($model->locadorPropietario) ? '' : $model->cliente->apellido.", ".$model->locadorPropietario->nombre;
                            //print_r($cityDesc);                          
                            echo empty($model->locadorPropietario) ? '' : $model->locadorPropietario->apellido.", ".$model->locadorPropietario->nombre;
                            echo '</label>'; 
                            ?>                        
                        </div>       
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_dni">Dni</label>

                            <?php
                            $locador_dni = "";
                            if ($model->locadorPropietario) {
                                $locador_dni = $model->locadorPropietario->cuit;
                            }
                            echo '<label id="locador_dni" class="form-control" for="locador_dni">' . $locador_dni . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>-->

                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_domicilio">Domicilio</label>                                       
                            <?php
                            $locador_domicilio = "";
                            if ($model->locadorPropietario) {
                                $locador_domicilio = $model->locadorPropietario->direccion;
                            }
                            echo '<label id="locador_domicilio" class="form-control" for="locador_domicilio">' . $locador_domicilio . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>-->
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <label class="control-label" for="locador_telefono">Fecha Alta</label>
                            <?php
                                $arreglo_fecha = explode('-', $model->fecha_ope);
                                echo '<label class="form-control" for="locador_telefono">' . $arreglo_fecha[1] . "/" . $arreglo_fecha[0] . '</label>';
                                ?>
                                <div class="help-block"></div>                
                        
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <?php
                            echo '<label  class="control-label" for="locador_telefono">Desde</label>';
                            ?>
                            <?= '<label class="form-control" for="locador_telefono">' .$model->desde_mes."/".$model->desde_anio. '</label>';?>

                        </div>                        
                                            
                        <div class="col-xs-12 col-md-6">
                            <?php
                            echo '<label  class="control-label" for="locador_telefono">Hasta</label>';
                            ?>
                            <?= '<label class="form-control" for="locador_telefono">' .$model->hasta_mes."/".$model->hasta_anio. '</label>';?>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <?php
                            echo '<label  class="control-label" for="locador_telefono">Plazo</label>';
                            
                           
                         
                            ?>
                             <?= '<label class="form-control" for="locador_telefono">'.$model->obtener_plazo(). ' meses</label>';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Contrato</label>';
                            ?>
                             <?= '<label class="form-control" for="locador_telefono">'. $model->tipo_contrato. '</label>';?>
                        </div>
                        <div class="col-xs-12 col-md-6">    
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Convenio</label>';
                            ?>                
                             <?= '<label class="form-control" for="locador_telefono">'.( ($model->conv_desocup==1)?'SI':'NO'). '</label>';?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Fecha de Celebracion del Contrato</label>';
                            ?>
                             <?= '<label class="form-control" for="locador_telefono">'. date('d/m/Y',strtotime($model->fecha_firma_contrato)). '</label>';?>                            
                        </div>
                        <div class="col-xs-12 col-md-6">
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Fecha de Celebracion del Convenio</label>';
                            ?>
                             <?= '<label class="form-control" for="locador_telefono">'. date('d/m/Y',strtotime($model->fecha_firma_convenio)). '</label>';?>                                             
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Firma Representante</label>';
                            ?>
                            <?= '<label class="form-control" for="locador_telefono">'.(($model->firma_representante==1)?'SI':'NO'). '</label>';?>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <?php
                            echo '<label  class="control-label" for="locador_telefono">Locador 2</label>'; 
                            echo '<label id="locador_dni" class="form-control" for="locador_dni">';                           
                            echo empty($model->locador1Propietario) ? '' : $model->locador1Propietario->apellido.", ".$model->locador1Propietario->nombre;
                            echo '</label>'; 
                            ?>                        
                        </div>       
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_dni">Dni</label>

                            <?php
                            $locador_dni = "";
                            if ($model->locador1Propietario) {
                                $locador_dni = $model->locador1Propietario->cuit;
                            }
                            echo '<label id="locador_dni" class="form-control" for="locador_dni">' . $locador_dni . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_domicilio">Domicilio</label>                                       
                            <?php
                            $locador_domicilio = "";
                            if ($model->locador1Propietario) {
                                $locador_domicilio = $model->locador1Propietario->direccion;
                            }
                            echo '<label id="locador_domicilio" class="form-control" for="locador_domicilio">' . $locador_domicilio . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <?php
                            echo '<label  class="control-label" for="locador_telefono">Locador 3</label>'; 
                            echo '<label id="locador_dni" class="form-control" for="locador_dni">';                           
                            echo empty($model->locador2Propietario) ? '' : $model->locador2Propietario->apellido.", ".$model->locador2Propietario->nombre;
                            echo '</label>'; 
                            ?>                        
                        </div>       
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_dni">Dni</label>

                            <?php
                            $locador_dni = "";
                            if ($model->locador2Propietario) {
                                $locador_dni = $model->locador2Propietario->cuit;
                            }
                            echo '<label id="locador_dni" class="form-control" for="locador_dni">' . $locador_dni . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_domicilio">Domicilio</label>                                       
                            <?php
                            $locador_domicilio = "";
                            if ($model->locador2Propietario) {
                                $locador_domicilio = $model->locador2Propietario->direccion;
                            }
                            echo '<label id="locador_domicilio" class="form-control" for="locador_domicilio">' . $locador_domicilio . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!--<div id="div_representante" class="row">
                <div class="col-xs-12 col-md-6">
                <?php
                            echo '<label  class="control-label" for="locador_telefono">Representante</label>';
                            ?>
                             <?= '<label class="form-control" for="locador_telefono">'. $model->representante. '</label>';?>                     
                </div>
                <div class="col-xs-12 col-md-6">
                <?php
                            echo '<label  class="control-label" for="locador_telefono">Representante Cuit</label>';
                            ?>
                             <?= '<label class="form-control" for="locador_telefono">'. $model->representante_cuit. '</label>';?>                   
                </div>
            </div>-->
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="row">
                        <div class="col-xs-12 col-md-12"> 
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Inquilino Titular</label>';
                            ?>
                         <?= '<label class="form-control" for="locador_telefono">'. (empty($model->cliente) ? '' : $model->cliente->NOMBRE). '</label>';?>                                    
                        </div>       
                        <div class="col-xs-12 col-md-12">
                                <label class="control-label" for="inquilino_dni">Cuil</label>
                                <?php
                                $inquilino_dni = "";
                                if ($model->cliente) {
                                    $inquilino_dni = $model->cliente->CUIL;
                                }                    
                                echo '<label id="inquilino_dni" class="form-control" for="inquilino_dni">' . $inquilino_dni . '</label>';
                                ?>
                                        
                        </div>
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="inquilino_domicilio">Domicilio</label>
                            <?php
                            $inquilino_domicilio = "";
                            if ($model->cliente) {
                                $inquilino_domicilio = $model->cliente->DIRECCION;
                            }
                            echo '<label id="inquilino_domicilio" class="form-control" for="inquilino_domicilio">' . $inquilino_domicilio . '</label>';
                            ?>

                            <div class="help-block"></div>
                        </div>-->
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="inquilino_localidad">Localidad</label>
                            <?php
                            $inquilino_localidad = "";
                            if ($model->cliente) {
                                $inquilino_localidad = $model->cliente->LOCALIDAD;
                            }
                            echo '<label id="inquilino_localidad" class="form-control" for="inquilino_localidad">' . $inquilino_localidad . '</label>';
                            ?>                    
                            <div class="help-block"></div>
                        </div>-->
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="inquilino_telefono">Telefono</label>

                            <?php
                            $inquilino_telefono = "";
                            if ($model->cliente) {
                                $inquilino_telefono = $model->cliente->TELEFONO;
                            }
                            echo '<label id="inquilino_telefono" class="form-control" for="inquilino_telefono">' . $inquilino_telefono . '</label>';
                            ?>                                        
                            <div class="help-block"></div>
                        </div>-->
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Inquilino Cotitular</label>';
                            ?>
                         <?= '<label class="form-control" for="locador_telefono">'. (empty($model->inquilino2) ? '' : $model->inquilino2->NOMBRE). '</label>';?>                                                                
                            <div class="help-block"></div>
                        </div>       
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_dni">Cuil</label>
                            <?php
                            $inquilino2_dni = "";
                            if ($model->inquilino2) {
                                $inquilino2_dni = $model->inquilino2->CUIL;
                            }
                            echo '<label id="inquilino2_dni" class="form-control" for="inquilino2_dni">' . $inquilino2_dni . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_domicilio">Domicilio</label>
                            <?php
                            $inquilino2_domicilio = "";
                            if ($model->inquilino2) {
                                $inquilino2_domicilio = $model->inquilino2->DIRECCION;
                            }
                            echo '<label id="inquilino2_domicilio" class="form-control" for="inquilino2_domicilio">' . $inquilino2_domicilio . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>-->
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_localidad">Localidad</label>
                            <?php
                            $inquilino2_localidad = "";
                            if ($model->inquilino2) {
                                $inquilino2_localidad = $model->inquilino2->LOCALIDAD;
                            }
                            echo '<label id="inquilino2_localidad" class="form-control" for="inquilino2_localidad">' . $inquilino2_localidad . '</label>';
                            ?> 
                            <div class="help-block"></div>
                        </div>-->
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_telefono">Telefono</label>
                            <?php
                            $inquilino2_telefono = "";
                            if ($model->inquilino2) {
                                $inquilino2_telefono = $model->inquilino2->TELEFONO;
                            }
                            echo '<label id="inquilino2_telefono" class="form-control" for="inquilino2_telefono">' . $inquilino2_telefono . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>-->
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Garante</label>';
                            ?>
                            <?= '<label class="form-control" for="locador_telefono">'. (empty($model->garante) ? '' : $model->garante->NOMBRE). '</label>';?>                                                       
                                                    
                            <div class="help-block"></div>
                        </div>       
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_dni">Cuil</label>
                            <?php
                            $garante_dni = "";
                            if ($model->garante) {
                                $garante_dni = $model->garante->CUIL;
                            }
                            echo '<label id="garante_dni" class="form-control" for="garante_dni">' . $garante_dni . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_domicilio">Domicilio</label>
                            <?php
                            $garante_domicilio = "";
                            if ($model->garante) {
                                $garante_domicilio = $model->garante->DIRECCION;
                            }
                            echo '<label id="garante_domicilio" class="form-control" for="garante_domicilio">' . $garante_domicilio . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>-->
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_localidad">Localidad</label>
                            <?php
                            $garante_localidad = "";
                            if ($model->garante) {
                                $garante_localidad = $model->garante->LOCALIDAD;
                            }
                            echo '<label id="garante_localidad" class="form-control" for="garante_localidad">' . $garante_localidad . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>-->
                        <!--<div class="col-xs-12 col-md-12">
                            <label class="control-label" for="locador_telefono">Telefono</label>
                            <?php
                            $garante_telefono = "";
                            if ($model->garante) {
                                $garante_telefono = $model->garante->TELEFONO;
                            }
                            echo '<label id="garante_telefono" class="form-control" for="garante_telefono">' . $garante_telefono . '</label>';
                            ?>
                            <div class="help-block"></div>
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <center><b>Detalles del Contrato</b></center>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Dia Vencimiento Mensual</label>';
                            ?>
                             <?= '<label class="form-control" for="locador_telefono">'. $model->dia_venc_mensual. '</label>';?>
                            
                        </div>
                        <div class="col-xs-12 col-md-6">
                        <?php
                            echo '<label  class="control-label" for="locador_telefono">Interes por Dia de Mora</label>';
                            ?>
                             <?= '<label class="form-control" for="locador_telefono">'. $model->interes_dia_mora. '%</label>';?>
                           
                        </div>
                        <div class="col-xs-12 col-md-12">

                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                <?php
                                    echo '<label  class="control-label" for="locador_telefono">Deposito Garantia</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'.(($model->deposito_garantia==1)?'SI':'NO'). '</label>';?>
                                    
                                </div>
                                <div class="col-xs-12 col-md-4">
                                <?php
                                    echo '<label  class="control-label" for="locador_telefono">Deposito Monto</label>';
                                    ?>
                             <?= '<label class="form-control" for="locador_telefono">'. $model->deposito_monto. '</label>';?>
                                    
                                    
                                </div>
                                <div class="col-xs-12 col-md-4">
                                <?php
                                    echo '<label  class="control-label" for="locador_telefono">Deposito Cuotas</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'. $model->deposito_cuotas. '</label>';?>                                   
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                        <?php
                                    echo '<label  class="control-label" for="locador_telefono">Deposito Contrato Monto</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'. $model->deposito_contrato_monto. '</label>';?>  
                            
                        </div>
                        <div class="col-xs-12 col-md-6">
                        <?php
                                    echo '<label  class="control-label" for="locador_telefono">Permite pagos pendientes</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'.(($model->permite_pagos_pendientes==1)?'SI':'NO'). '</label>';?>          
                            
                        </div>
                        <div class="col-xs-12 col-md-6">
                        <?php
                                    echo '<label  class="control-label" for="locador_telefono">Tiene Expensas</label>';
                                    ?>


                            <?= '<label class="form-control" for="locador_telefono">'.(($model->tiene_expensas==1)?'SI':'NO'). '</label>';?>
                            
                        </div>
                        <div id="div_expensas" class="col-xs-12 col-md-6">  
							<?php $tipo_expensas = ArrayHelper::map(\app\models\TipoExpensas::find()->where('id = 12 OR id=13')->orderBy('descripcion')->all(), 'id', 'descripcion'); ?>                  
							<?=  Html::checkboxList('expensas_id',$model->expensas,$tipo_expensas, 
                        ['separator' => '<br>','readonly'=>'readonly']); ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        
                        
                        <div class="col-xs-12 col-md-12">
                            <?php
                                    echo '<label  class="control-label" for="locador_telefono">Inmobiliarias</label>';
                                    ?>
							<?= '<label class="form-control" for="locador_telefono">'. $model->inmobiliaria->nombre_inmobiliaria. '</label>';?>                            
                        </div>
                        <div class="col-xs-12 col-md-6">
                        <?php
                                    echo '<label  class="control-label" for="locador_telefono">Destino Contrato</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'. $model->destino_contrato. '</label>';?>                            
                        </div>
                        <div class="col-xs-12 col-md-6">
                        <?php
                                    echo '<label  class="control-label" for="locador_telefono">Excento</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'.(($model->excento==1)?'SI':'NO'). '</label>';?>          
                            
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <!--<div class="row">
                                <div class="col-xs-12 col-md-4">   
                                <?php
                                    echo '<label  class="control-label" for="locador_telefono">Honorarios</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'.(($model->honorarios==1)?'SI':'NO'). '</label>';?>                         
                                    
                                </div>
                                <div class="col-xs-12 col-md-4">
                                <?php
                                    echo '<label  class="control-label" for="locador_telefono">Honorarios Monto</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'. $model->excento_monto. '</label>';?>                                    
                                </div>
                                <div class="col-xs-12 col-md-4">
                                <?php
                                    echo '<label  class="control-label" for="locador_telefono">Honorarios Cuotas</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'. $model->excento_monto. '</label>';?>                                     
                                </div>
                            </div>-->
                        </div>
                        <div class="col-xs-12 col-md-12">          
                        <?php
                                    echo '<label  class="control-label" for="locador_telefono">Contrato Firmado</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'.(($model->contrato_firmado==1)?'SI':'NO'). '</label>';?>                                     
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                <?php
                                    echo '<label  class="control-label" for="locador_telefono">Observaciones</label>';
                                    ?>
                                    <?= '<label class="form-control" for="locador_telefono">'. $model->observaciones. '</label>';?>                    
                </div>
                <div class="col-xs-12 col-md-3">
                <?php
                                    echo '<label  class="control-label" for="locador_telefono">Estado del Contrato</label>';
                                    ?>
                <?= '<label class="form-control" for="locador_telefono">'. $model->estado. '</label>';?>                    
                                        
                </div>
                <div class="col-xs-12 col-md-3">   
                <?php
                                    //echo '<label  class="control-label" for="locador_telefono">Estado de Renovacion</label>';
                                    ?>
                <?php //= '<label class="form-control" for="locador_telefono">'. $model->estado_renovacion. '</label>';?>                    
                </div>
            </div>



        </div>
    
   
        </div>

    </div>

</div>
