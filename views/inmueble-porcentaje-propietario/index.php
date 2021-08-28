<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InmueblePorcentajePropietarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inmueble Porcentaje Propietarios ' . $searchModel->inmueble->direccion;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inmueble-porcentaje-propietario-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Asignar Propietario Inmueble', ['create', 'id' => $searchModel->inmueble_id], ['class' => 'btn btn-success']) ?>
		<?= Html::a('Volver a la propiedad', ['a2-noticias/view','id'=>$searchModel->inmueble_id], ['class' => 'btn btn-primary']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',                        
            [
                'attribute' => 'propietario_id',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    if ($data->propietario) {
                        return $data->propietario->apellido . ", " . $data->propietario->nombre;
                    } else {
                        return null;
                    }
                }
            ],
            'porcentaje',
            'estado',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
