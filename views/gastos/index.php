<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GastosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gastos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gastos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Gasto', ['create','id'=>$searchModel->inmueble_id,'operacion_id'=>$searchModel->operacion_id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'inmueble_id',
            'fecha',
            'importe',
            'estado',
            'observacion:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
