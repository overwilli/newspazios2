<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PromocionesMails */

$this->title = 'Actualizar Promocion por Mail: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Promociones Mails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="promociones-mails-update">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">  
            <h1><?= Html::encode($this->title) ?></h1>

            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>
