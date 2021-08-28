<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InmueblePorcentajePropietario */

$this->title = 'Asignar Porcentaje Propietario';
$this->params['breadcrumbs'][] = ['label' => 'Inmueble Porcentaje Propietarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inmueble-porcentaje-propietario-create">

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
