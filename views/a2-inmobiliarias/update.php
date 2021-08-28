<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2Inmobiliarias */

$this->title = 'Actualizar Inmobiliarias ' . $model->id_inmobiliaria;
$this->params['breadcrumbs'][] = ['label' => 'A2 Inmobiliarias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_inmobiliaria, 'url' => ['view', 'id' => $model->id_inmobiliaria]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="a2-inmobiliarias-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
