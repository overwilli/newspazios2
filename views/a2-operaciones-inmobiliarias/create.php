<?php

use yii\helpers\Html;
//use app\yii\models\A2noticias;
use app\models\A2noticias;
/* @var $this yii\web\View */
/* @var $model app\models\A2OperacionesInmobiliarias */


//$aux = Yii::$app->request->get('propiedad_id');

$model2 = new A2noticias();
//$aux = Yii::$app->request->get('propiedad_id');
$aux2 = $model->cod_propiedad;
$model2 = A2noticias::find()->where(['id_noticia' => $aux2])->one();

//print_r($model2);

//$this->title = 'Contrato para '.$model2->direccion;
$this->params['breadcrumbs'][] = ['label' => 'Contrato', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-operaciones-inmobiliarias-create">

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
