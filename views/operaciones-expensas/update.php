<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OperacionesExpensas */

$this->title = 'Actualizar Expensa: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Expensas cargadas', 'url' => ['index','propiedad_id'=>$model->inmuebles_id,'operacion_id'=>$model->operacion_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="operaciones-expensas-update">

<div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($model->inmueble->titulo) ?></h3>
        </div>
        <div class="box-body"> 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
