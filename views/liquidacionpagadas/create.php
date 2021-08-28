<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Liquidacionpagadas */

$this->title = 'Nueva Orden de Pago';
$this->params['breadcrumbs'][] = ['label' => 'Ordenes de Pago', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidacionpagadas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
