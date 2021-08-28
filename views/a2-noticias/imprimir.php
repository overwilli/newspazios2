<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\A2Noticias */

$this->params['breadcrumbs'][] = ['label' => ' Inmuebles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$formatter = \Yii::$app->formatter;
?>
<style>
@media print{
	.main-header,.main-sidebar,.content-header,.main-footer{
		display:none;
	}
	.a2-noticias-view{
		display:block;
		width:100%;
	}
	
}
.content-header{
		display:none;
	}
</style>
<div class="a2-noticias-view">
    
    
    <section class="invoice">
        <div class="col-xs-12">
            <h3 class="page-header">
                <i class="fa fa-building-o"></i> <?php echo $model->direccion ?>
                <small class="pull-right"></small>
            </h3>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <center><b>DATOS GENERALES</b></center>
                            <hr/>
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
                            <?php echo $model->porcion; ?>             
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">    
                            <?php echo Html::activeLabel($model, "descripcion") ?>
                            <?php echo $model->descripcion; ?>               
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-md-6">
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
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-6">
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
                                <?php echo Html::activeLabel($model, "luz") ?>
                                <?php echo ($model->luz == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>                        

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <?php echo Html::activeLabel($model, "gas") ?>
                                <?php echo ($model->gas == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <?php echo Html::activeLabel($model, "cloaca") ?>
                                <?php echo ($model->cloaca == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <?php echo Html::activeLabel($model, "agua") ?>
                                <?php echo ($model->agua == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>                        
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <?php echo Html::activeLabel($model, "parrilla") ?>
                                <?php echo ($model->parrilla == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <?php echo Html::activeLabel($model, "salon_u_m") ?>
                                <?php echo ($model->salon_u_m == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <?php echo Html::activeLabel($model, "piscina") ?>
                                <?php echo ($model->piscina == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <?php echo Html::activeLabel($model, "seguridad") ?>
                                <?php echo ($model->seguridad == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <?php echo Html::activeLabel($model, "amueblado") ?>
                                <?php echo ($model->amueblado == 1) ? "<i class='icon fa fa-check'></i>" : ""; ?>

                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-xs-6 col-md-6">
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
                </div>
        </div>        
    </section>
</div>

<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
    window.print();
    //location.href=base_url+'/index.php?r=a2-noticias/view&id=<?php echo $model->id_noticia?>';
</script>