<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlantillasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plantillas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plantillas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="col-md-10">
            <?= Html::a('Nueva Plantilla', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'titulo',
//            'texto:ntext',
            'estado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
