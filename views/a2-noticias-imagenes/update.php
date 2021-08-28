<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2NoticiasImagenes */

$this->title = 'Actualizar Imagenes: ' . $model->id_noticia_imagen;
$this->params['breadcrumbs'][] = ['label' => 'A2 Noticias Imagenes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_noticia_imagen, 'url' => ['view', 'id' => $model->id_noticia_imagen]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="a2-noticias-imagenes-update">

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
