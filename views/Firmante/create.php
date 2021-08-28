<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Firmante */

$this->title = 'Create Firmante';
$this->params['breadcrumbs'][] = ['label' => 'Firmantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firmante-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
