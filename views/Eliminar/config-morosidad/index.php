<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configurar Notificaciones por Morosidad';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-morosidad-index">

    <h1><?= Html::encode($this->title) ?>
        <?= Html::a('Ejemplo', ['ver-mail-morosidad', 'config_morosidad_id' => 1], 
            ['class' => 'btn btn-primary', "target" => '_blank']) ?>
    </h1>
    
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'dias_deuda',
            'mensaje:ntext',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}',],
        ],
    ]); ?>
</div>
