<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2Clientes */

$this->title = 'Factura B';
$this->params['breadcrumbs'][] = ['label' => 'Inicio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nota-credito-create">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode('Factura B') ?></h3>
        </div>
        <div class="box-body">           
            <div class="callout callout-success">
                    <h4>Resultado!</h4>

                    <p>Factura B emitida correctamente. Ver impresión en Controlador Fiscal.</p>
                </div>
            </div>
        </div>
    </div>
</div>
