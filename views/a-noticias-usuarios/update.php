<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ANoticiasUsuarios */

$this->title = 'Actualizar Usuario: ' . $model->id_usuario;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_usuario, 'url' => ['view', 'id' => $model->id_usuario]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="anoticias-usuarios-update">

    <div class="box box-success">
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
