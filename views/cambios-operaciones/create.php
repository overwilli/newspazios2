<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CambiosOperaciones */

$this->title = 'Renovar Contrato';
$this->params['breadcrumbs'][] = ['label' => 'Cambios Operaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cambios-operaciones-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
		</div>
	</div>
</div>
