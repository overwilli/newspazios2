<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MailingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plantillas de correo';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailing-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            ['label'=>'Asunto','attribute'=>'subject'],
            //'archivo',
            //'tipo',
            //'fecha_envio',
            // 'ultimo_envio',
            // 'estado:boolean',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        $url = \yii\helpers\Url::toRoute(['mailing/actualizar-template', 'id' => $model['id']]);
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('yii', 'Actualizar'),
                        ]);
                    },
                        ],
                    ],
                ],
            ]);
            ?>
</div>
