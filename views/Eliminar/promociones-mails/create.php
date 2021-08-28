<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PromocionesMails */

$this->title = 'Nueva Promocion por Mail';
$this->params['breadcrumbs'][] = ['label' => 'Promociones Mails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promociones-mails-create">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
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
