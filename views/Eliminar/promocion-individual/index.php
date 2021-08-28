<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PromocionIndividualSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promocion Individual';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promocion-individual-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Promocion Individual', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'enlace',
            'url_image:url',
            'estado',
            'promociones_mails_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
