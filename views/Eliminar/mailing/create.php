<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mailing */

$this->title = 'Nuevo Mail';
$this->params['breadcrumbs'][] = ['label' => 'Mailings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
