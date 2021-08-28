<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\jui\Tabs;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2NoticiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inmuebles Disponibles y Por Vencer';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-noticias-index">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

            <?php
            $arreglo_secciones = ArrayHelper::map(\app\models\A2Secciones::find()->orderBy('seccion')->all(), 'id_seccion', 'seccion');
            //$arreglo_secciones[] = 'Todos';
            ?>    
            <?php //echo $this->render('_search', ['model' => $searchModel]); ?>


            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'Inmuebles Disponibles',
                        'content' => $this->render('_disponibles', array(
                            'dataProvider' => $dataProvider_disponibles,
                            'searchModel' => $searchModel,
                            'arreglo_secciones' => $arreglo_secciones
                        )),
                    ],
                    [
                        'label' => 'Inmuebles por Vencer',
                        'content' => $this->render('_por_vencer', array(
                            'dataProvider' => $dataProvider_por_vencer,
                            'searchModel' => $searchModel,
                            'arreglo_secciones' => $arreglo_secciones
                        )),
                    ],
                ],
                'options' => ['tag' => 'div'],
                'itemOptions' => ['tag' => 'div'],
                'headerOptions' => ['class' => 'my-class'],
                'clientOptions' => ['collapsible' => false],
            ]);
            ?>

        </div>
    </div>
</div>

