<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Plantillas */

$this->title = 'Nueva Plantilla';
$this->params['breadcrumbs'][] = ['label' => 'Plantillas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="plantillas-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
        </div>
    </div>
</div>
