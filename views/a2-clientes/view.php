<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\A2Clientes */

$this->title = $model->NOMBRE;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-clientes-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row">
        <div class="col-md-10">
            <p>
                <?= Html::a('Actualizar', ['update', 'id' => $model->id_cliente], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Borrar', ['delete', 'id' => $model->id_cliente], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Esta seguro que desea borrar este registro?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver', NULL, ['class' => 'btn btn-app', 'onclick' => 'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'DNI',
            array(
                'attribute' => 'CUIL',
                'value' => $model->obtener_cuil(),
            ),
            'NOMBRE',
            //'NOMBRE_FANTASIA',
            'DIRECCION',
            //'LOCALIDAD',
            'BARRIO',
            //'PROVINCIA',
            [
                'attribute' => "provincia_id",
                'value' => function ($model) {
                    $model_provincia = \app\models\Provincias::find()->where(['id' => $model->provincia_id])->one();
                    if ($model_provincia) {
                        return $model_provincia->nombre;
                    }
                }
            ],
            [
                'attribute' => "localidad_id",
                'value' => function ($model) {
                    $model_localidad = \app\models\Localidades::find()->where(['id' => $model->localidad_id])->one();
                    if ($model_localidad) {
                        return $model_localidad->nombre;
                    }
                }
            ],

            'TELEFONO',
            'TELEF2',
            'TELEF3',
            'EMAIL:email',
            'id_cliente',
            /*'NRO_CUENTA',
            'CUIL',
            'EST_CIVIL',
            array(
                'attribute' => 'EST_CIVIL',
                'value' => $model->estadoCivil->denominacion,
            ),
            'FECNAC',
            'PRIM_NUPCIAS',
            'DNI_CONYUGE',
            'NOMBRE_CONYUGE',
            'CUIL_CONYUGE',*/
            'OBSERVACIONES:ntext',
        ],
    ]) ?>

</div>