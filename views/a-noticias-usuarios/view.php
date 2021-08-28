<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ANoticiasUsuarios */

$this->title = $model->id_usuario;
$this->params['breadcrumbs'][] = ['label' => 'Anoticias Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="anoticias-usuarios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-10">
            <?= Html::a('Actualizar', ['update', 'id' => $model->id_usuario], ['class' => 'btn btn-primary']) ?>
            <?=
            Html::a('Borrar', ['delete', 'id' => $model->id_usuario], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Esta seguro que desea borrar este registro?',
                    'method' => 'post',
                ],
            ])
            ?>
         </div>
        <div class="col-md-2 offset-md-10">
            <?= Html::a('<i class="fa fa-history"></i> Volver',NULL, ['class' => 'btn btn-app','onclick'=>'js:history.go(-1);returnFalse;']) ?>
        </div>
    </div>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_usuario',
            'nikname',
            //'password',
            'email:email',
            //'sitio',
            'permisos',            
            [
                'attribute' => 'timestamp',
                'value' => function ($data) {
                    return date('d/m/Y H:i:s',strtotime($data->timestamp));
                }
            ],
            'numero_caja',
        ],
    ])
    ?>

</div>
