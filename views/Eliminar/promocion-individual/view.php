<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PromocionIndividual */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Promocion por Mail', 'url' => ['promociones-mails/view','id'=>$model->promociones_mails_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promocion-individual-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Borrar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea borrar este registro',
                'method' => 'post',
            ],
        ]) ?>
    </p>    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'enlace',
            'url_image:url',
            'orden',
            'texto_imagen',
            'estado',            
            [                      // the owner name of the model
            'attribute' => 'promociones_mails_id',
            'value' => $model->promocionesMails->asunto,
        ],
        ],
    ]) ?>
    <?php
    if(!empty($model->url_image)){
        echo Html::img(\Yii::$app->params['directorio_promocion'].$model->url_image, ['alt' => '','width'=>'600px']);
    }
    ?>

</div>
