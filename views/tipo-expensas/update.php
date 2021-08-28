<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoExpensas */

$this->title = 'Actualizar Tipo de Expensas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tipo de Expensas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="tipo-expensas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
