
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InmueblesPropietarios */

$this->title = 'Nuevo Propietario en inmueble';
$this->params['breadcrumbs'][] = ['label' => 'Propietarios', 'url' => ['index','id' => $model->inmueble_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inmuebles-propietarios-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($model->inmueble->titulo) ?></h3>
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
