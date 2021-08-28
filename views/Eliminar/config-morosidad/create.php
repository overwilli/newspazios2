<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ConfigMorosidad */

$this->title = 'Create Config Morosidad';
$this->params['breadcrumbs'][] = ['label' => 'Config Morosidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-morosidad-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
