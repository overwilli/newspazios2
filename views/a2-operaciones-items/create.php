<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2OperacionesItems */

$this->title = 'Nuevo Periodo de Cobro';
$this->params['breadcrumbs'][] = ['label' => 'Nuevo Periodo de Cobro', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-operaciones-items-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">              
            <?=
            $this->render('_form', [
                'model' => $model,'errores'=>$errores,'estado'=>$estado
            ])
            ?>

        </div>
    </div>
</div>
