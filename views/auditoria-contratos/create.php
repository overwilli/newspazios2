<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AuditoriaContratos */

$this->title = 'Create Auditoria Contratos';
$this->params['breadcrumbs'][] = ['label' => 'Auditoria Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auditoria-contratos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
