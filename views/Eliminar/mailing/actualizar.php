<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mailing */

$this->title = 'Actualizar Plantilla: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mailings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="mailing-update">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            <?= Html::a('Ejemplo', ['ver-template-mail', 'mailing_id' => $model->id], ['class' => 'btn btn-primary', "target" => '_blank']) ?>
        </div>
        <div class="box-body">
    
    <?= $this->render('_form_actualizar', [
        'model' => $model,
    ]) ?>
        </div>
    </div>
</div>
