<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\A2Grupos */

$this->title = $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'A2 Grupos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-grupos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-10">
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_grupo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Borrar', ['delete', 'id' => $model->id_grupo], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea borrar este registro?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Agregar Inmueble', ['a2-grupos/nuevo-inmueble-grupo', 'grupo_id' => $model->id_grupo], ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',null, ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_grupo',
            'descripcion',
        ],
    ]) ?>

    <h3>Inmuebles</h3>
    <?=

GridView::widget([
    'dataProvider' => $dataProvider_inmuebles,
    //'filterModel' => $searchModel,
    
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //'id_noticia',
                'direccion',
                'porcion',
				//'codigo_postal',
                
                //'seccion',
                //'pesoseccion',
                //'antetitulo',
                // 'bajada:ntext',
                // 'cuerpocompleto:ntext',
                // 'foto',
                // 'fotopie',
                // 'clicks',
                // 'autor',
                // 'editor',
                // 'timestamp',
                // 'fechatimestamp',
                // 'fechacarga',
                // 'operacion',
                // 'alq_vendida',
                // 'id_estado',
                // 'id_grupo',
                // 'direccion',
                //['attribute' => 'precio',
                // 'format' => 'Currency',],
                // 'ambientes',
                // 'sup_cubierta',
                // 'sup_terreno',
                // 'habitaciones',
                // 'dormitorios',
                // 'banios',
                // 'conv_desocup',
                // 'frente',
                // 'fondo',
                // 'padroniibb',
                // 'padronaguas',
                // 'padronmunicipal',
                // 'provincia_id',
                // 'localidad_id',
                ['class' => 'yii\grid\ActionColumn',
                    //'header'=>'Ficha',
                    'template' => '{editar}',
                    'buttons' => [
                        'editar' => function ($url, $model) {
                            $url = \yii\helpers\Url::toRoute(['a2-grupos/actualizar-porcion-grupo', 
                                'inmueble_id' => $model->id_noticia,'grupo_id'=>$model->id_grupo]);
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'Editar'), /*'target' => '_blank'*/]);
                            },
                        
                            ],
                        ],
                    ],
                ]);
?>

</div>
