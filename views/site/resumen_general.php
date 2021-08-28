<?php

use yii\helpers\Html;
use app\models\Clientes;
use app\models\Invitaciones;
use app\models\BlackListPromo;

$this->title = 'Resumen General';
$this->params['breadcrumbs'][] = ['label' => 'Panel', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

<?php
$total_clientes_web = Clientes::total_clientes_web();
$total_clientes_con_acceso = Clientes::total_clientes_con_acceso();
$total_invitaciones_distintas = Invitaciones::total_invitaciones_distintas();
$total_clientes_sin_promo_mail = BlackListPromo::total_clientes_distintos();
$porc_clientes_acceso = number_format(($total_clientes_con_acceso * 100) / $total_clientes_web, 0);
$porc_invitaciones_distintas = number_format(($total_invitaciones_distintas * 100) / $total_clientes_web, 0);
$porc_clie_no_reciben_promo_mail = number_format(($total_clientes_sin_promo_mail * 100) / $total_clientes_web, 1);

$primer_dia = new \DateTime(date('Y-m-d'));
$ultimo_dia = new \DateTime(date('Y-m-d'));

$primer_dia->modify('first day of this month');
$ultimo_dia->modify('last day of this month');

//$cantidad_clientes_distintos_mes = Clientes::total_clientes_autenticados_en_periodo($primer_dia->format('Y-m-d'), $ultimo_dia->format('Y-m-d'));
$cantidad_clientes_distintos_mes = 0;
$resultado_clientes_mes = Clientes::cantidad_clientes_autenticados_por_dia($primer_dia->format('Y-m-d'), date('Y-m-d'));
foreach ($resultado_clientes_mes as $row) {    
    $cantidad_clientes_distintos_mes += $row['cantidad'];    
}


$primer_dia = new \DateTime(date('Y-m-d'));
$ultimo_dia = new \DateTime(date('Y-m-d'));

$primer_dia->modify('Monday this week');
$ultimo_dia->modify('Sunday this week');

//$cantidad_clientes_distintos_semana = Clientes::total_clientes_autenticados_en_periodo($primer_dia->format('Y-m-d'), $ultimo_dia->format('Y-m-d'));
$cantidad_clientes_distintos_semana = 0;
$resultado_clientes_semana = Clientes::cantidad_clientes_autenticados_por_dia($primer_dia->format('Y-m-d'), date('Y-m-d'));
foreach ($resultado_clientes_semana as $row) {    
    $cantidad_clientes_distintos_semana += $row['cantidad'];    
}
?>
<!-- Info boxes -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Clientes en la Web</span>
                <span class="info-box-number"><?php echo number_format($total_clientes_web, 0, '', '.') ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion-person-add"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Usuarios con acceso</span>
                <span class="info-box-number"><?php echo number_format($total_clientes_con_acceso, 0, '', '.') ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion-ios-email"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Invitaciones realizadas</span>
                <span class="info-box-number"><?php echo number_format($total_invitaciones_distintas, 0, '', '.') ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-alert-circled"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">No reciben mail promo</span>
                <span class="info-box-number"><?php echo number_format($total_clientes_sin_promo_mail, 0, '', '.') ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

</div>
<!-- /.row -->

<!-- row -->
<div class="row">
    <div class="col-xs-12">
        <!-- jQuery Knob -->
        <div class="box box-solid">
            <div class="box-header">
                <i class="fa fa-bar-chart-o"></i>

                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-6 col-md-3 text-center">
                        <input type="text" class="knob" value="<?php echo $porc_clientes_acceso ?>" data-width="90" data-height="90" data-fgColor="#3c8dbc">

                        <div class="knob-label">Clientes con Acceso</div>
                    </div>
                    <!-- ./col -->
                    <div class="col-xs-6 col-md-3 text-center">
                        <input type="text" class="knob" value="<?php echo $porc_invitaciones_distintas ?>" data-width="90" data-height="90" data-fgColor="#f56954">

                        <div class="knob-label">Invitaciones Realizadas</div>
                    </div>
                    <!-- ./col -->
                    <div class="col-xs-6 col-md-3 text-center">
                        <input type="text" class="knob" value="<?php echo $porc_clie_no_reciben_promo_mail ?>" data-width="90" data-height="90" data-fgColor="#00a65a">

                        <div class="knob-label">No reciben mail promo</div>
                    </div>                    
                </div>
                <!-- /.row -->               
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<!-- row -->
<div class="row">
    <div class="col-xs-8">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Uso de la Web en el mes</h3>                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="chart-responsive">
                            <canvas id="pieChart" height="150"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-2">
                        <ul class="chart-legend clearfix">
                            <!--<li><i class="fa fa-circle-o text-red"></i> Chrome</li>
                            <li><i class="fa fa-circle-o text-green"></i> IE</li>
                            <li><i class="fa fa-circle-o text-yellow"></i> FireFox</li>
                            <li><i class="fa fa-circle-o text-aqua"></i> Safari</li>
                            <li><i class="fa fa-circle-o text-light-blue"></i> Opera</li>
                            <li><i class="fa fa-circle-o text-gray"></i> Navigator</li>-->
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <div class="pad box-pane-right bg-green" style="min-height: 280px">
                            <div class="description-block margin-bottom">
                                <div class="sparkbar pad" data-color="#fff">90,70,90,70,75,80,70</div>
                                <h5 class="description-header"><?php echo $cantidad_clientes_distintos_mes; ?></h5>
                                <span class="description-text">Cantidad Clientes accedieron en el mes</span>
                            </div>
                            <!-- /.description-block -->
                            <div class="description-block margin-bottom">
                                <div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
                                <h5 class="description-header"><?php echo $cantidad_clientes_distintos_semana; ?></h5>
                                <span class="description-text">Cantidad Clientes accedieron en la semana</span>
                            </div>
                            <!-- /.description-block -->                            
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
                <ul id="porcentajes_visitas" class="nav nav-pills nav-stacked">

                </ul>
            </div>
            <!-- /.footer -->
        </div>
        <!-- /.box -->
    </div>
    <div class="col-xs-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Accesos de clientes en el d&iacute;a</h3>                
            </div>
            <!-- /.box-header -->
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <?php
                    $resultado = Clientes::clientes_autenticados();
                    if (count($resultado) == 0) {
                        echo "No existen accesos";
                    } else {
                        ?>
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Dni</th>
                                    <th>Clientes</th>                                
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($resultado as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['dni'] ?></td>
                                        <td><?php echo $row['nombre'] ?></td>                                    
                                    </tr>
                                    <?php
                                }
                                ?>                            
                            </tbody>
                        </table>
                        <?php
                    }
                    ?>
                </div>
                <!-- /.table-responsive -->
            </div>          
        </div>
        <!-- /.box -->
    </div>
</div>
<!-- row -->
<div class="row">
    <div class="col-xs-12">
        <!-- solid sales graph -->
        <div class="box box-solid bg-teal-gradient">
            <div class="box-header">
                <i class="fa fa-th"></i>

                <h3 class="box-title">Historial de accesos de clientes en los ultimos 6 meses</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
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
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/resumen_general.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>
