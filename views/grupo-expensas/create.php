<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GrupoExpensas */

$this->title = 'Nueva Expensa de Consorcio';
$this->params['breadcrumbs'][] = ['label' => 'Grupo Expensas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupo-expensas-create">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">
                <h1><?= Html::encode($this->title) ?></h1>
                </div>
        <div class="box-body">                  

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
