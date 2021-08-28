<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PromocionIndividual */

$this->title = 'Nueva Imagen de Promocion';
$this->params['breadcrumbs'][] = ['label' => 'Promociones por Mail', 'url' => ['promociones-mails/view','id' => $model->promociones_mails_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promocion-individual-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
