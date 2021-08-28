<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Localidades;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LlavesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Llaves de '.$searchModel->inmueble->direccion;
$this->params['breadcrumbs'][] = $this->title;


$obj_localidad=Localidades::find()->where(['id'=>$searchModel->inmueble->localidad_id])->one();
$localidad="";
if($obj_localidad){
    $localidad=$obj_localidad->nombre;
}

$barrio=$searchModel->inmueble->barrio;

?>
<div class="llaves-index">

<div class="row">
        <div class="col-md-10">
    <h3><?= Html::encode($this->title." - ".$barrio." - ".$localidad) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Registro de Llaves', ['create','id'=>$searchModel->inmueble_id], ['class' => 'btn btn-success']) ?>
		<?php //= Html::a('Volver a la propiedad', ['a2-noticias/view','id'=>$searchModel->inmueble_id], ['class' => 'btn btn-primary']) ?>
    </p>
    </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-noticias/inmuebles-disponibles'], ['class' => 'btn btn-app',]) ?>
        </div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'id',
//            'inmueble_id',
            'numero_llave',            
            [
                'attribute' => 'inmobiliaria_id',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    if ($data->inmobiliaras) {
                        return $data->inmobiliaras->nombre_inmobiliaria;
                    } else {
                        return null;
                    }
                }
            ],
            
            [
                'attribute'=>'fecha_solicitud',
                'value'=>function($data){
                    if($data->fecha_solicitud){
                        return date('d/m/Y',strtotime($data->fecha_solicitud));
                    }else{
                        return null;
                    }
                }
            ],
            //'tipo_solicitud',
            'persona',
            [
                'attribute'=>'fecha_devolucion',
                'value'=>function($data){
                    if($data->fecha_devolucion){
                        return date('d/m/Y',strtotime($data->fecha_devolucion));
                    }else{
                        return null;
                    }
                }
            ],
            'usuario_devolucion',
            'observacion:ntext',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} ',
            'buttons' => [
                'update' => function ($url, $model) {
                    if(!isset($model->fecha_devolucion)){
                        $url = \yii\helpers\Url::toRoute(['llaves/update', 'id' => $model->id,
                                    ]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => Yii::t('yii', 'Devolver'), ]);
                    }else{
                        return null;
                    }
                },
                'delete' => function ($url, $model) {
                    if((isset($model->fecha_devolucion)) && date('Y-m-d',strtotime($model->fecha_devolucion))==date('Y-m-d')){
                        $url = \yii\helpers\Url::toRoute(['llaves/delete', 'id' => $model->id,
                                    ]);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => Yii::t('yii', 'Borrar'),'data-confirm' => Yii::t('yii', 'Esta seguro que desea borrar este registro?'),
                                    'data-method' => 'post', 'data-pjax' => '0', ]);
                    }else{
                        return null;
                    }
                },
                
                    ]
            ],
        ],
    ]); ?>
</div>
