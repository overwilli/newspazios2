<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2InmobiliariasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inmobiliarias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-inmobiliarias-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="col-md-10">
            <?= Html::a('Nueva Inmobiliaria', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',['a2-noticias/index'], ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_inmobiliaria',
            'nombre_inmobiliaria',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
