<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clientes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <? //= Html::a('Create Clientes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'dni',
            'nombre',
            'direccion',
            
            // 'empresa',
            // 'empresa_direccion',
            // 'empresa_puesto',
            // 'empresa_antiguedad',
            // 'telefono_pre',
            // 'telefono',
            // 'telefono_alternativo_pre',
            // 'telefono_alternativo',
            // 'celular_pre',
            // 'celular',
            // 'celular_alternativo_pre',
            // 'celular_alternativo',
            // 'email:email',
            // 'email_alternativo:email',
            // 'password',
            // 'password_temp',
            // 'hash',
            // 'estado',
            // 'cumpleanios',
            // 'fecha_actualizacion',
            // 'updated_date',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',],
        ],
        'rowOptions' => function ($model, $index, $widget, $grid) {
            if (empty($model->clientesUpdate->password)) {
                return ['class' => 'danger'];
            } else {
                return [];
            }
        },
    ]);
    ?>
</div>
