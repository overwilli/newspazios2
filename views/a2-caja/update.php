<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2Caja */

$this->title = 'Update A2 Caja: ' . $model->id_caja;
$this->params['breadcrumbs'][] = ['label' => 'A2 Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_caja, 'url' => ['view', 'id' => $model->id_caja]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="a2-caja-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
