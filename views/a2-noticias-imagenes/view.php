<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\A2NoticiasImagenes */

$this->title = $model->id_noticia_imagen;
$this->params['breadcrumbs'][] = ['label' => 'A2 Noticias Imagenes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-noticias-imagenes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_noticia_imagen], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Borrar', ['delete', 'id' => $model->id_noticia_imagen], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea borrar este registro?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="fa fa-history"></i> Volver',null, ['class' => 'btn btn-info','onclick'=>'js:history.go(-1);returnFalse;']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_noticia_imagen',
            'id_noticia',
            'ImgPath',
            [
                'label' => 'Imagen',
                'format' => 'image',
                'value' => function ($data) {
                    return $data->imageurl;
                },
            ],
            'ImgDesc',
            'ImgOrden',
            'ImgTipo',
        ],
    ]) ?>

</div>
