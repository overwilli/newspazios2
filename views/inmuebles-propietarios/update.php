<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InmueblesPropietarios */

$this->title = 'Actualizar Propietario de inmueble: ' . $model->inmueble->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Inmuebles Propietarios', 'url' => ['index','id' => $model->inmueble_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="inmuebles-propietarios-update">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($model->inmueble->titulo) ?></h3>
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

