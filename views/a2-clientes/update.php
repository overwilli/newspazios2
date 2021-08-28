<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2Clientes */

$this->title = 'Actualizar Cliente : ' . $model->id_cliente;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_cliente, 'url' => ['view', 'id' => $model->id_cliente]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="a2-clientes-update">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">   
            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>
