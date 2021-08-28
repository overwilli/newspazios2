<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listado de Clientes que no reciben promociones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clientes-index">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->

        <div class="box-body">
            <h1></h1>    


            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <?php echo Html::label('Desde', 'fecha_desde', ['class' => 'col-sm-1 control-label']); ?>
                <div class="col-xs-12 col-md-3">

                    <?php 
                    echo \yii\jui\DatePicker::widget([

                        'name' => 'fecha_desde',
                        'id' => 'fecha_desde',
                        'value'=>(!empty($fecha_desde))? $fecha_desde:'',
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
                        'value'=>(!empty($fecha_hasta))? $fecha_hasta:'',
                        //'language' => 'ru',
                        'dateFormat' => 'dd/MM/yyyy',
                        'options' => ["class" => "form-control"],
                    ]);
                    ?>
                </div>
                <div class="col-xs-12 col-md-2">
                    <?php
                    echo Html::submitButton('Buscar', ['class' => 'btn btn-block btn-primary', 'id' => 'buscar']);
                    ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
    if($dataProvider->getCount()>0){
        //echo Html::button('Incorporar a los envios de mail', ['create'], ['class' => 'btn btn-success']);
        echo Html::button('Incorporar a los envios de mail', ['class' => 'btn btn-block btn-primary', 'id' => 'reincorporar']);
        echo "<br/>";
        echo Html::a('<i class="fa fa-file-excel-o"></i>Descargar Excel ', 
            ['clientes/clientes-no-reciben-promociones-excel',array('fecha_desde'=>$fecha_desde,'fecha_hasta'=>$fecha_hasta,)], ['class' => 'btn btn-app','target'=>'_blank']);
        
    }
    ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

<!--    <p>
    <? //= Html::a('Create Clientes', ['create'], ['class' => 'btn btn-success'])  ?>
    </p>-->
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
             'class' => 'yii\grid\CheckboxColumn',
             'checkboxOptions'=>function ($model, $key, $index, $column){
                    return ['value' => $model['dni']];
             }
            ],
            'dni',
            'nombre',
            'email',
            [
                'label' => 'Fecha de Baja',
                'value' => function ($data) {
                    return date('d/m/Y H:i:s', strtotime($data['fecha_baja']));
                },
            ],
            //date('d/m/Y',strtotime($data->fecha_baja)
            // 'empresa',
            // 'empresa_direccion',
            // 'empresa_puesto',
            // 'empresa_antiguedad',
            // 'telefono_pre',
            // 'telefono',
            // 'telefono_alternativo_pre',
            // 'telefono_alternativo',
            // 'celular_pre',
            // 'celular',
            // 'celular_alternativo_pre',
            // 'celular_alternativo',
            // 'email:email',
            // 'email_alternativo:email',
            // 'password',
            // 'password_temp',
            // 'hash',
            // 'estado',
            // 'cumpleanios',
            // 'fecha_actualizacion',
            // 'updated_date',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        $url = \yii\helpers\Url::toRoute(['clientes/view', 'id' => $model['dni']]);
                        return Html::a('<span class="fa fa-thumbs-o-up"></span>', NULL, [
                                    'title' => Yii::t('yii', 'Volver a enviarle promociones'), 'onclick' => 'javascript:remover_cliente_backlist(' . $model['dni'] . ');',
                        ]);
                    },
                        ]
                    ],
                ],
                    /* 'rowOptions' => function ($model, $index, $widget, $grid) {
                      if (empty($model->clientesUpdate->password)) {
                      return ['class' => 'danger'];
                      } else {
                      return [];
                      }
                      }, */
            ]);
            ?>
    
    
        </div>
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
                    </div>     
                </div>
            </div>
        </div>


        <script type="text/javascript">
            var base_url = '<?php echo Yii::getAlias('@web') ?>';
        </script>
        <?php
        $this->registerJsFile(Yii::$app->request->baseUrl . '/js/clientes/listado_clientes_no_reciben_mail.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        ?>
