<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="anoticias-usuarios-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-10">
            <?= Html::a('Nuevo Usuario', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver', NULL, ['class' => 'btn btn-app', 'onclick' => 'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_usuario',
            'nikname',
            //'password',
            'email:email',
            //'sitio',
            'permisos',
            // 'timestamp',
            'numero_caja',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ficha',
                'template' => '{view} {change_password} {update} {delete} ',
                'buttons' => [

                    'change_password' => function ($url, $model) {
                        $url = \yii\helpers\Url::toRoute(['a-noticias-usuarios/cambiar-password', 'id' => $model->id_usuario,]);
                        return Html::a('<span class="fa fa-key"></span>', $url, [
                            'title' => Yii::t('yii', 'Cambiar Password'), 'target' => '_blank'
                        ]);
                    },

                ],
            ],

        ],

    ]); ?>
</div>