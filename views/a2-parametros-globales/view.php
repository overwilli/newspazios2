<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\A2ParametrosGlobales */

$this->title = $model->id_parametro;
//$this->params['breadcrumbs'][] = ['label' => 'A2 Parametros Globales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-parametros-globales-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_parametro], ['class' => 'btn btn-primary']) ?>       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_parametro',
            'empresa',
            'ultimo_recibo_x',
            'ultimo_recibo_c',
            'ultimo_recibo_d',
            'impresora_host',
            'impresora_puerto',
            'impresora_modelo',
            'impresora_firmware',
            'impresora_directorio',
            'comprobante_pago_unico',
        ],
    ]) ?>

</div>
