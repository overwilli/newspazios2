<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ANoticiasUsuarios */

$this->title = 'Nuevo Usuario';
$this->params['breadcrumbs'][] = ['label' => 'Anoticias Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="anoticias-usuarios-create">

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
