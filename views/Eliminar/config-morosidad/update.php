<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfigMorosidad */

$this->title = utf8_encode('Actualizar Config. de Notificación por Morosidad: ') . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Config Morosidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="config-morosidad-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
