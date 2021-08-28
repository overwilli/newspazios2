<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvitacionesMasivasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invitaciones Masivas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invitaciones-masivas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Invitacion Masiva', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',            
            [
                'attribute'=>'fecha_creacion',
                'value'=>function ($data) {return  date('d/m/Y',strtotime($data->fecha_creacion));},
            ],            
            [
                'attribute'=>'fecha_envio',
                'value'=>function ($data) {return  date('d/m/Y',strtotime($data->fecha_envio));},
            ],
            'estado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
