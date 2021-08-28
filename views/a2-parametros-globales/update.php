<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2ParametrosGlobales */

$this->title = 'Configuracion de Impresora y Otros ';
//$this->params['breadcrumbs'][] = ['label' => 'A2 Parametros Globales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_parametro, 'url' => ['view', 'id' => $model->id_parametro]];

?>
<div class="a2-parametros-globales-update">

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
