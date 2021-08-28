<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContratosDocumentosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documentos del inmueble ' . $searchModel->operacionInmobiliaria->inmueble->direccion;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contratos-documentos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="col-md-10">
            <?= Html::a('Nuevo Documento', ['create', 'id' => $searchModel->operacion_inmobiliaria_id], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);return False;']) ?>
        </div>
    </div>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'plantilla_id',
                'value' => function($data) {
                    return $data->plantillas->titulo;
                }
            ],
            'estado',
            // 'time_create',
            // 'usuario_update',
            // 'time_update',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
