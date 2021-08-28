<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\A2Inmobiliarias */

$this->title = 'Nueva Inmobiliaria';
$this->params['breadcrumbs'][] = ['label' => 'Inmobiliarias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-inmobiliarias-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
