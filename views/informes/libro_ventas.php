<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\A2Clientes */

$this->title = 'Libros Ventas';
?>
<div class="a2-clientes-create">
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="box box-success">
                <?php $form = ActiveForm::begin(); ?>
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">


                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="">Fecha Desde</label>
                                <?php
                                echo \yii\jui\DatePicker::widget([
                                    'name' => 'fecha_desde',
                                    'value' => "",
                                    'dateFormat' => 'dd/MM/yyyy',
                                    'options' => ['class' => 'form-control'],
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label" for="">Fecha Hasta</label>
                            <?php
                            echo \yii\jui\DatePicker::widget([
                                'name' => 'fecha_hasta',
                                'value' => "",
                                //'language' => 'ru',
                                'dateFormat' => 'dd/MM/yyyy',
                                'options' => ['class' => 'form-control'],
                            ]);
                            ?>

                        </div>
                    </div>



                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Mostrar</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>