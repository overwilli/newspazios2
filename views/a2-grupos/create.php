<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\A2Grupos */

$this->title = 'Nuevo Grupo';
$this->params['breadcrumbs'][] = ['label' => 'Grupos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-grupos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
