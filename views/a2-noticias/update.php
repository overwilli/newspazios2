<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2Noticias */

$this->title = 'Actualizar Inmueble: ' . $model->id_noticia;
$this->params['breadcrumbs'][] = ['label' => 'Inmuebles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_noticia, 'url' => ['view', 'id' => $model->id_noticia]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="a2-noticias-update">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
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
