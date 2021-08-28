<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Imagenes de '.$model->inmueble->direccion;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-noticias-imagenes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Subir nueva imagen', ['create','id'=>$id], ['class' => 'btn btn-success']) ?>
		<?= Html::a('Volver a la propiedad', ['a2-noticias/view','id'=>$model->inmueble->id_noticia], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
            //'ImgDesc',
            //'ImgOrden',
            // 'ImgTipo',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>
