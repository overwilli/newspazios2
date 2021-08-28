<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Invitaciones;
use app\models\PromocionesMails;
use app\models\BlackListPromo;

/* @var $this yii\web\View */
/* @var $model app\models\Clientes */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<div class="clientes-view">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>

        </div>
        <div class="box-body">    
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <a class="btn btn-app" data-toggle="modal" data-target="#myModal" >                
                        <i class="fa fa-envelope"></i> Enviar invitacion
                    </a>
              <!--    <p>
                    <? //= Html::a('Update', ['update', 'id' => $model->dni], ['class' => 'btn btn-primary']) ?>
                    <? /* = Html::a('Delete', ['delete', 'id' => $model->dni], [
                      'class' => 'btn btn-danger',
                      'data' => [
                      'confirm' => 'Are you sure you want to delete this item?',
                      'method' => 'post',
                      ],
                      ]) */ ?>
                  </p>-->

                    <?=
                    DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'nombre',
                            'dni',
                            'direccion',
                            'barrio',
                            [                      // the owner name of the model
                                'label' => 'Localidad',
                                'value' => (empty($model->localidades->localidad)) ? '' : $model->localidades->localidad,
                            ],
                            'empresa',
                            'empresa_direccion',
                            'empresa_puesto',
                            'empresa_antiguedad',
                            'telefono_pre',
                            'telefono',
                            'telefono_alternativo_pre',
                            'telefono_alternativo',
                            'celular_pre',
                            'celular',
                            'celular_alternativo_pre',
                            'celular_alternativo',
                            'email:email',
                            'email_alternativo:email',
                            [                      // the owner name of the model
                                'label' => 'Password',
                                'value' => (empty($model->clientesUpdate->password)) ? '' : $model->clientesUpdate->password,
                            ],
                            [                      
                                'attribute' => 'cumpleanios',
                                'value' => date('d/m/Y',strtotime($model->cumpleanios)),
                            ],
                            /*[                      
                                'attribute' => 'fecha_actualizacion',
                                'value' => date('d/m/Y',strtotime($model->fecha_actualizacion)),
                            ],*/
                            [                      
                                'attribute' => 'updated_date',
                                'value' => date('d/m/Y h:i:s',strtotime($model->updated_date)),
                            ],                            
                        ],
                    ])
                    ?>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3><?php echo Invitaciones::total_invitaciones_por_cliente($model->dni); ?></h3>

                                    <p>Invitaciones Rec&iacute;bidas</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-email"></i>
                                </div>
                                <a href="#" class="small-box-footer">Mas Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">                
                        <div class="col-xs-12 col-md-12">
                            <!-- small box -->
                            <div class="small-box bg-purple">
                                <div class="inner">
                                    <h3><?php echo PromocionesMails::total_promociones_por_cliente($model->dni) ?></h3>

                                    <p>Promociones rec&iacute;bidas</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-mail-forward"></i>
                                </div>
                                <a href="#" class="small-box-footer">Mas Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php if (BlackListPromo::total_promociones_recibidas($model->dni) > 0) { ?>
                        <div class="row">                
                            <div class="col-xs-12 col-md-12">
                                <!-- small box -->
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>1</h3>

                                        <p>No desea recibir Promociones por correo</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-mail-forward"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">Mas Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="<?php echo $model->dni?>" id="dni" name="dni "/>
<script type="text/javascript">
    var base_url='<?php echo Yii::getAlias('@web')?>';    
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/clientes/view.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>


<div id="myModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Informaci&oacute;n</h4>
            </div>
            <div class="modal-body">
                <p ><b><div id="resultado"></div></b></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>   
                <button type="button" onclick="enviar_invitacion_mail()" class="btn btn-primary">Confirmar env&iacute;o</button>
            </div>     
        </div>
    </div>
</div>