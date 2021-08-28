<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\A2Caja */

$this->title = 'Create A2 Caja';
$this->params['breadcrumbs'][] = ['label' => 'A2 Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-caja-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
