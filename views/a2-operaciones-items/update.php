<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2OperacionesItems */

$this->title = 'Actualizar Periodos: ' . $model->id_item;
$this->params['breadcrumbs'][] = ['label' => 'A2 Operaciones Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_item, 'url' => ['view', 'id' => $model->id_item]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="a2-operaciones-items-update">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">  

    <?= $this->render('_form', [
        'model' => $model,'estado'=>$estado
    ]) ?>

		</div>
    </div>
</div>