<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\A2Caja */

$this->title = 'Inicializar Caja';
$this->params['breadcrumbs'][] = ['label' => 'A2 Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-caja-create">

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body"> 

            <?=
            $this->render('_form_inicializar', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>
