<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Gastos */

$this->title = 'Nuevo Gasto';
$this->params['breadcrumbs'][] = ['label' => 'Gastos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gastos-create">

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
