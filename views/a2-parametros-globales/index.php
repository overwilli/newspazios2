<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'A2 Parametros Globales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-parametros-globales-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_parametro',
            'empresa',
            'ultimo_recibo_x',
            'ultimo_recibo_c',
            'ultimo_recibo_d',
            // 'impresora_host',
            // 'impresora_puerto',
            // 'impresora_modelo',
            // 'impresora_firmware',
            // 'impresora_directorio',
            // 'comprobante_pago_unico',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
