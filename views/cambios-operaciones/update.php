<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CambiosOperaciones */

$this->title = 'Update Cambios Operaciones: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cambios Operaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cambios-operaciones-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
