<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Localidades;
use app\models\Provincias;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InmueblesPropietariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$obj_localidad=Localidades::find()->where(['id'=>$searchModel->inmueble->localidad_id])->one();
$localidad="";
if($obj_localidad){
	$localidad=$obj_localidad->nombre;
}
/*$obj_provincia=Provincias::find()->where(['id'=>$searchModel->inmueble->provincia_id])->one();
$provincia="";
if($obj_localidad){
	$provincia=$obj_localidad->nombre;
}*/
$this->title = $searchModel->inmueble->direccion." - ".$searchModel->inmueble->barrio." - ".$localidad;

//$this->title = 'Propietarios de '.$searchModel->inmueble->direccion;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inmuebles-propietarios-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Propietario', ['create','id'=>$searchModel->inmueble_id], ['class' => 'btn btn-success']) ?>
		<?= Html::a('Volver a la propiedad', ['a2-noticias/view','id'=>$searchModel->inmueble_id], ['class' => 'btn btn-primary']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'inmueble_id',
            //'propietario_id',
            //'estado',
            [
                'attribute' => 'propietario_id',
                //'filter' => Html::activeDropDownList($searchModel, 'seccion', $arreglo_secciones, ['class' => 'form-control',]),
                //},
                'value' => function ($data) {
                    if ($data->propietario) {
                        return $data->propietario->apellido . ", " . $data->propietario->nombre. " (" . $data->propietario->obtener_cuit().")";
                    } else {
                        return null;
                    }
                }
            ],
			[
                'attribute' => 'comision',                
                'value' => function ($data) {
                    return $data->comision."%";
                }
            ],			
            [
                'attribute' => 'porcentaje',                
                'value' => function ($data) {
                    return $data->porcentaje."%";
                }
            ],
            
            //'fecha_carga',
             'fecha_actualizacion',
            // 'usuario_carga',
            // 'usuario_actualizacion',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}   {delete}'],
        ],
    ]);
    ?>
</div>
