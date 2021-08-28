<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidacionpagadas */

$this->title = 'Actualizar Orden de Pago: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ordenes de Pago', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="liquidacionpagadas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
