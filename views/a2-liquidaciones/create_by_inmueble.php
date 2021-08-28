<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\A2Liquidaciones */

$this->title = utf8_encode('Nueva Liquidación');
$this->params['breadcrumbs'][] = ['label' => 'Liquidaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-liquidaciones-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body"> 

    <?= $this->render('_form_by_inmueble', [
        'model' => $model,
        'mensaje'=>(!empty($mensaje))?$mensaje:"",
    ]) ?>
        </div>
    </div>
</div>
