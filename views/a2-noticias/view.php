<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\A2Noticias */

$this->title =$model->id_noticia;
$this->params['breadcrumbs'][] = ['label' => ' Inmuebles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$formatter = \Yii::$app->formatter;
?>
<div class="a2-noticias-view">
    
    
    <div class="row">
        <div class="col-md-10">
                <?php if (Yii::$app->user->identity->permisos != "operador") { ?>
                
                <p>
                    <?= Html::a('Actualizar', ['update', 'id' => $model->id_noticia], ['class' => 'btn btn-primary']) ?>
                    <?=
                    Html::a('Eliminar', ['delete', 'id' => $model->id_noticia], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Esta seguro que desea borrar este registro?',
                            'method' => 'post',
                        ],
                    ])
                    ?>
                    
                    <?= Html::a('<i class="fa fa-male"></i>Propietarios', ['inmuebles-propietarios/index', 'id' => $model->id_noticia], ['class' => 'btn btn-app']) ?>
                    <?= Html::a('<i class="fa fa-key"></i>Gestion de Llaves', ['llaves/index', 'id' => $model->id_noticia], ['class' => 'btn btn-app']) ?>
                    <?= Html::a('<i class="fa fa-file-image-o"></i>Galeria de Imagenes', ['a2-noticias-imagenes/index', 'id' => $model->id_noticia], ['class' => 'btn btn-app']) ?>
                    <!--<?= Html::a('<i class="fa fa-yelp"></i>Propietarios Porcentaje', ['inmueble-porcentaje-propietario/index', 'id' => $model->id_noticia], ['class' => 'btn btn-app']) ?>-->
                    <?= Html::a('<i class="fa fa-print"></i>Imprimir', ['imprimir', 'id' => $model->id_noticia], ['class' => 'btn btn-app']) ?>
                    <?php
                    $contrato_activo=$model->getOperacionInmobiliaria()->where("estado='ACTIVO'")->one();
                                
                    if($contrato_activo){ ?>
                        <?php //= Html::a('<i class="fa fa-ticket"></i>Gastos', ['gastos/index', 'id' => $model->id_noticia,'operacion_id'=>$contrato_activo->id_operacion_inmobiliaria], ['class' => 'btn btn-app']) ?>
                        <?php //= Html::a('<i class="fa fa-phone"></i>Gestión Cobranzas', ['gestion-cobranzas/index', 'id' => $model->id_noticia,'operacion_id'=>$contrato_activo->id_operacion_inmobiliaria], ['class' => 'btn btn-app']) ?>
                        
                        
                    <?php
                    }
                    ?>                   
                </p>
            <?php }else{ ?>
                <?= Html::a('<i class="fa fa-print"></i>Imprimir', ['imprimir', 'id' => $model->id_noticia], ['class' => 'btn btn-app']) ?>
            <?php } ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',['inmuebles-disponibles'], ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);return False;']) ?>
        </div>
    </div>
    
    <section class="invoice">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-building-o"></i> <?php 
				$model_localidad = \app\models\Localidades::find()->where(['id' => $model->localidad_id])->one();
                
				echo $model->direccion." - ".strtoupper($model->barrio);
				if ($model_localidad) {
                    echo " - ".$model_localidad->nombre;
                }
				?>
                <small class="pull-right"></small>
            </h2>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <center><b>DATOS GENERALES</b></center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <?php echo Html::activeLabel($model, "precio") ?>
                <?php echo $formatter->asCurrency($model->precio); ?>
            </div>
            <div class="col-xs-12 col-md-6">
                <?php echo Html::activeLabel($model, "operacion") ?>
                <?php echo $model->objetoPropiedad->operacion; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <?php echo Html::activeLabel($model, "direccion") ?>
                <?php echo $model->direccion; ?>
            </div>
            <div class="col-xs-12 col-md-4">
                <?php echo Html::activeLabel($model, "barrio") ?>
                <?php echo $model->barrio; ?>            
            </div>
            <div class="col-xs-12 col-md-4">  
                <?php echo Html::activeLabel($model, "id_grupo") ?>
                <?php
                $model_grupo = \app\models\A2Grupos::find()->where(['id_grupo' => $model->id_grupo])->one();
                if ($model_grupo) {
                    echo $model_grupo->descripcion;
                }
                ?>            
            </div>
        </div>
        <div class="row">
            
            <div class="col-xs-12 col-md-4">  
                <?php echo Html::activeLabel($model, "localidad_id") ?>

                <?php
                $model_localidad = \app\models\Localidades::find()->where(['id' => $model->localidad_id])->one();
                if ($model_localidad) {
                    echo $model_localidad->nombre;
                }
                ?>           
            </div>
            <div class="col-xs-12 col-md-4">           
                <?php echo Html::activeLabel($model, "provincia_id") ?>

                <?php
                $model_provincia = \app\models\Provincias::find()->where(['id' => $model->provincia_id])->one();
                if ($model_provincia) {
                    echo $model_provincia->nombre;
                }
                ?>            

            </div>
            <div class="col-xs-12 col-md-4">
                <?php echo Html::activeLabel($model, "porcion") ?>
                <?php echo $model->porcion."%"; ?>             
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">    
                <?php echo Html::activeLabel($model, "descripcion") ?>
                <?php echo $model->descripcion; ?>               
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <center><b>CARACTERISITICAS</b></center>
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">    
                <?php echo Html::activeLabel($model, "seccion") ?>
                <?php echo $model->secciones->seccion; ?>            
            </div>
            <div class="col-xs-12 col-md-4">
                <?php echo Html::activeLabel($model, "sup_terreno") ?>
                <?php echo $model->sup_terreno; ?>            
            </div>
            <div class="col-xs-12 col-md-4">     
                <?php echo Html::activeLabel($model, "apto_comercial") ?>
                <?php echo ($model->apto_comercial == 1) ? "SI" : "NO"; ?>            
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/ambiente.png'?>"  />    
                <?php echo Html::activeLabel($model, "ambientes") ?>
                <?php echo $model->ambientes; ?>

            </div>
            <div class="col-xs-12 col-md-4">
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/superficie_cubierta.png'?>"  />
                <?php echo Html::activeLabel($model, "sup_cubierta") ?>
                <?php echo $model->sup_cubierta; ?>                
            </div>
            <div class="col-xs-12 col-md-4">
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/apto_profesional.png'?>"  />          
                <?php echo Html::activeLabel($model, "apto_profesional") ?>
                <?php echo ($model->apto_profesional == 1) ? "SI" : "NO"; ?>                
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/dormitorio.png'?>"  />
                <?php echo Html::activeLabel($model, "dormitorios") ?>
                <?php echo $model->dormitorios; ?>                       
            </div>
            <div class="col-xs-12 col-md-4">
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/frente_y_fondo.png'?>"  />
                <?php echo Html::activeLabel($model, "frente") ?> y 
                <?php echo Html::activeLabel($model, "fondo") ?>(m)
                <?php echo $model->frente; ?> x  <?php echo $model->fondo; ?>                     
            </div>            
            <div class="col-xs-12 col-md-4">           
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/portero.png'?>"  />
                <?php echo Html::activeLabel($model, "portero_electrico") ?>
                <?php echo ($model->portero_electrico == 1) ? "SI" : "NO"; ?>                
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/banio.png'?>"  />
                <?php echo Html::activeLabel($model, "banios") ?>
                <?php echo $model->banios; ?>                  
            </div>
            <div class="col-xs-12 col-md-4">
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/ascensor.png'?>"  />
                <?php echo Html::activeLabel($model, "ascensor") ?>
                <?php echo $model->ascensor; ?>                   
            </div>
            <div class="col-xs-12 col-md-4"> 
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/disposicion.png'?>"  />
                <?php echo Html::activeLabel($model, "disposicion") ?>
                <?php echo ($model->disposicion == 'FRENTE') ? "FRENTE" : "CONTRAFRENTE"; ?>                 
            </div>        
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">      
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/balcon.png'?>"  />
                <?php echo Html::activeLabel($model, "balcon") ?>
                <?php echo ($model->balcon == 1) ? "SI" : "NO"; ?>               
            </div>
            <div class="col-xs-12 col-md-4">  
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/cochera.png'?>"  />
                <?php echo Html::activeLabel($model, "cochera") ?>
                <?php echo $model->cochera; ?>                 
            </div>
            <div class="col-xs-12 col-md-4">           
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/antiguedad.png'?>"  />
                <?php echo Html::activeLabel($model, "antiguedad") ?>
                <?php echo $model->antiguedad; ?>                 
            </div>        
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">           

            </div>
            <div class="col-xs-12 col-md-4">
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/patio.png'?>"  />
                <?php echo Html::activeLabel($model, "patio") ?>
                <?php echo ($model->patio == 1) ? "SI" : "NO"; ?>                     
            </div>
            <div class="col-xs-12 col-md-4">   
                <img  src="<?php  echo Yii::$app->request->baseUrl . '/images/iconos/estado_inmueble.png'?>"  />         
                <?php echo Html::activeLabel($model, "id_estado") ?>                

                <?php
                $model_estado = \app\models\A2EstadosInmuebles::find()->where(['id_estado' => $model->id_estado])->one();
                if ($model_estado) {
                    echo $model_estado->descripcion;
                }
                ?>
            </div>        
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <center><b>SERVICIOS</b></center>
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
						<?php if($model->luz == 1){?>
							<?php echo Html::activeLabel($model, "luz") ?>
							<?php echo ($model->luz == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>                        
						<?php } ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
						<?php if($model->gas == 1){?>
							<?php echo Html::activeLabel($model, "gas") ?>
							<?php echo ($model->gas == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>
						<?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
						<?php if($model->cloaca == 1){?>
								<?php echo Html::activeLabel($model, "cloaca") ?>
                        <?php echo ($model->cloaca == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>
						<?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
						<?php if($model->agua == 1){?>
                        <?php echo Html::activeLabel($model, "agua") ?>
                        <?php echo ($model->agua == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>                        
						<?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
						<?php if($model->parrilla == 1){?>
                        <?php echo Html::activeLabel($model, "parrilla") ?>
                        <?php echo ($model->parrilla == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>
						<?php } ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
						<?php if($model->salon_u_m == 1){?>
                        <?php echo Html::activeLabel($model, "salon_u_m") ?>
                        <?php echo ($model->salon_u_m == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>
						<?php } ?>

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
						<?php if($model->piscina == 1){?>
                        <?php echo Html::activeLabel($model, "piscina") ?>
                        <?php echo ($model->piscina == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>
						<?php } ?>

                    </div>
                </div>
                <div class="row">					
                    <div class="col-xs-12 col-md-12">
						<?php if($model->seguridad == 1){?>
                        <?php echo Html::activeLabel($model, "seguridad") ?>
                        <?php echo ($model->seguridad == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>
						<?php } ?>		
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
						<?php if($model->amueblado == 1){?>
                        <?php echo Html::activeLabel($model, "amueblado") ?>
                        <?php echo ($model->amueblado == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>
						<?php } ?>

                    </div>
                </div>
            </div>
        </div>
        <?php if (Yii::$app->user->identity->permisos != "operador") { ?>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <center><b>PADRONES</b></center>
                <hr/>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <?php echo Html::activeLabel($model, "padroniibb") ?>
                <?php echo $model->padroniibb; ?>
                                
            </div>
            <div class="col-xs-12 col-md-4">
                <?php echo Html::activeLabel($model, "padronaguas") ?>
                <?php echo $model->padronaguas; ?>
                                
            </div>
            <div class="col-xs-12 col-md-4">
                <?php echo Html::activeLabel($model, "padronmunicipal") ?>
                <?php echo $model->padronmunicipal; ?>
                
            </div>
        </div>
        <?php } ?>
    </section>    
	<hr/>	  
	
	
    <?php
	
    /*foreach ($model_imagenes_inmuebles as $row) {
        ?>		
        <a target="_blank" href="<?php echo Yii::$app->request->baseUrl . "/images/inmuebles/" . $row['ImgPath'] ?>"><img  src="<?php  echo Yii::$app->request->baseUrl . "/images/inmuebles_thumbs/" . $row['ImgPath'] ?>" /></a>
        <?php
    }*/	
    ?>
</div>
