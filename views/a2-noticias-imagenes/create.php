<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\A2NoticiasImagenes */

$this->title = 'Subir imagenes ';
$this->params['breadcrumbs'][] = ['label' => 'Imagenes del inmueble', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-noticias-imagenes-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title." para el Inmueble ") ?></h3>
        </div>
        <div class="box-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
        </div>
    </div>
</div>
