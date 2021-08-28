<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PromocionIndividual */

$this->title = 'Actualizar Promocion Individual: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Promociones por Mails', 'url' => ['promociones-mails/view','id'=>$model->promociones_mails_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="promocion-individual-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
