<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TipoExpensas */

$this->title = 'Nueva Expensa';
$this->params['breadcrumbs'][] = ['label' => 'Tipo de Expensas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-expensas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
