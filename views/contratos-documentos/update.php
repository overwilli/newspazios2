<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ContratosDocumentos */

$this->title = 'Actualizar documentos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contratos Documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="contratos-documentos-update">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($model->operacionInmobiliaria->inmueble->titulo) ?></h3>
        </div>
        <div class="box-body">   
            <h1><?= Html::encode($model->operacionInmobiliaria->inmueble->titulo) ?></h1>

            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>
