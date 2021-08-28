<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GestionCobranzas */

$this->title = 'Nueva Cobranza';
$this->params['breadcrumbs'][] = ['label' => 'Gestion Cobranzas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gestion-cobranzas-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($model->contrato->cliente->NOMBRE) ?></h3>
        </div>
        <div class="box-body"> 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
	</div>
	</div>

</div>
