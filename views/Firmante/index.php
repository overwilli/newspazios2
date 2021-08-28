<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FirmanteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Firmantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firmante-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Firmante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nombre',
            'id_cliente',
            'id_prop',
            'id_ope',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
