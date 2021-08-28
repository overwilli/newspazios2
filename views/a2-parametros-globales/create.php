<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\A2ParametrosGlobales */

$this->title = 'Create A2 Parametros Globales';
$this->params['breadcrumbs'][] = ['label' => 'A2 Parametros Globales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-parametros-globales-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
