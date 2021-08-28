<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PromocionesMailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promociones por Mail';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promociones-mails-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Promocion por Mail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'asunto',                        
            [
                'attribute'=>'fecha_envio',
                'value'=>function ($data) {return  date('d/m/Y',strtotime($data->fecha_envio));},
            ],
            'estado',            
            // 'estado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>          
</div>
