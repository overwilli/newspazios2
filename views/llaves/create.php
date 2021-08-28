<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Llaves */

$this->title = 'Prestamo de Llave';
$this->params['breadcrumbs'][] = ['label' => 'Llaves', 'url' => ['index','id'=>$model->inmueble_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="llaves-create">    
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $this->title."  ".Html::encode($model->inmueble->titulo) ?></h3>
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

