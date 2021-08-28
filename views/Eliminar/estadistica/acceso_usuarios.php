<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Acceso de Usuarios';
?>
<div class="estadistica-create">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <form class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">

                        <?php echo Html::label('Desde', 'fecha_desde', ['class' => 'col-sm-1 control-label']); ?>

                        <div class="col-xs-12 col-md-3"> 
                            <?php
                            echo \yii\jui\DatePicker::widget([

                                'name' => 'fecha_desde',
                                'id' => 'fecha_desde',
                                //'language' => 'ru',
                                'dateFormat' => 'dd/MM/yyyy',
                                'options' => ["class" => "form-control"],
                            ]);
                            ?>
                        </div>
                        <?php echo Html::label('Hasta', 'fecha_hasta', ['class' => 'col-sm-1 control-label']); ?>

                        <div class="col-xs-12 col-md-3">                            
                            <?php
                            echo \yii\jui\DatePicker::widget([

                                'name' => 'fecha_hasta',
                                'id' => 'fecha_hasta',
                                //'language' => 'ru',
                                'dateFormat' => 'dd/MM/yyyy',
                                'options' => ["class" => "form-control"],
                            ]);
                            ?>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <?php
                            echo Html::button('Buscar', ['class' => 'btn btn-block btn-primary', 'id' => 'buscar']);
                            ?>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
                <div>

                    <div class="btn-group">
                        <button rel="1" type="button" class="btn btn-default active btn_toggle">Dia</button>
                        <button rel="2" type="button" class="btn btn-default btn_toggle">Semana</button>
                        <button rel="3" type="button" class="btn btn-default btn_toggle">Mes</button>
                    </div>
                    <p>Esta funcionalidad muestra la cantidad de clientes distintos que accedieron por d&iacute;a filtrado por
                        fecha desde, y fecha hasta.</p>
                </div>

        </div>
    </div>

    <div id="resultado" class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">Resultado</h3>
            <div>
                <div class="col-md-3">
<!--                    <div class="pad box-pane-right bg-green" >
                        <div class="description-block margin-bottom">
                            <div class="sparkbar pad" data-color="#fff">90,70,90,70,75,80,70</div>
                            <h5 class="description-header" id="id_clientes_periodo"><?php //echo 0; ?></h5>
                            <span class="description-text">Cantidad Clientes accedieron en el periodo</span>
                        </div>                        
                    </div>-->
                </div>
            </div>
        </div>

        <div class="box-body">

            <!-- solid sales graph -->
            <div class="box box-solid bg-teal-gradient">
                <div class="box-header">
                    <i class="fa fa-th"></i>

                    <h3 class="box-title">Historial de accesos de clientes filtrado por fecha</h3>

                    <div class="box-tools pull-right">
      <!--                <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                      </button>-->
                    </div>
                </div>
                <div class="box-body border-radius-none">
                    <div class="chart" id="line-chart" style="height: 250px;"></div>
                </div>            
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/estadistica/estadistica.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>