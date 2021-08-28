<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvitacionesMasivas */

$this->title = utf8_encode('Nueva Invitación Masivas');
$this->params['breadcrumbs'][] = ['label' => 'Invitaciones Masivas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invitaciones-masivas-create">

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
