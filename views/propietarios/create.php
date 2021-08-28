<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Propietarios */

$this->title = 'Nuevo Propietario';
$this->params['breadcrumbs'][] = ['label' => 'Propietarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="propietarios-create">

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

