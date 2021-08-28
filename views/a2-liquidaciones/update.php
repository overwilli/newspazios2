<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */

$this->title = 'Update A2 Liquidaciones: ' . $model->id_liquidacion;
$this->params['breadcrumbs'][] = ['label' => 'A2 Liquidaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_liquidacion, 'url' => ['view', 'id' => $model->id_liquidacion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="a2-liquidaciones-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
