<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ContratosDocumentos */

$this->title = 'Nuevo Documento';
$this->params['breadcrumbs'][] = ['label' => 'Contratos Documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contratos-documentos-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($model->operacionInmobiliaria->inmueble->titulo) ?></h3>
        </div>
        <div class="box-body">   
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
    </div>
</div>

