<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2GruposSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grupos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-grupos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="col-md-10">
            <?= Html::a('Nuevo Grupo', ['create'], ['class' => 'btn btn-success']) ?>
            <?php echo Html::a('<i class="fa fa-file-excel-o"></i>Excel', ['a2-grupos/informe-grupos',], 
                ['class' => 'btn btn-app','target'=>'_blank']);?>
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

            //'id_grupo',
            'descripcion',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
