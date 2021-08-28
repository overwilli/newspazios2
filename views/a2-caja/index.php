<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2CajaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'A2 Cajas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-caja-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create A2 Caja', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_caja',
            'inicio_fecha',
            'inicio_hora',
            'inicio_usuario',
            'inicio',
            // 'caja_final',
            // 'caja_dia_siguiente',
            // 'rendicion',
            // 'sobrante_faltante',
            // 'caja_cierre',
            // 'cierre_fecha',
            // 'cierre_hora',
            // 'cierre_usuario',
            // 'cobranzas_efectivo',
            // 'ingresos_varios',
            // 'cheques',
            // 'intereses_mora',
            // 'depositos',
            // 'retenciones',
            // 'gastos_varios',
            // 'supermercado',
            // 'timestamp',
            // 'numero_caja',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
