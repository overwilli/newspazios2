<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoExpensas */

$this->title = 'Actualizar Expensas de Consorcio: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Grupo Expensas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grupo-expensas-update">
<div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">
                <h1><?= Html::encode($this->title) ?></h1>
                </div>
        <div class="box-body">
            <div class="callout callout-warning">
                <h4>Importante!</h4>

                <p>Al momento de grabar o actualizar la expensa por grupo, el sistema buscará todos los inmuebles que pertenecen al grupo ingresado, 
                y tratará de crear una expensa para el inmueble dividiendo el importe ingresado sobre la porcion ingresada en el inmueble,
                de no existir el dato porcion cargado el sistema intentará dividir el importe ingresado entre el total de inmueble pertenecientes al grupo.</p>
              </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
