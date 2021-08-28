<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\A2Clientes */

$this->title = 'Expensas por consorcio';
?>
<div class="a2-clientes-create">
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="box box-success">
                <?php $form = ActiveForm::begin(['options'=>['target'=>'_blank']]); ?>
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">


                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="">Periodos</label>
                                <?php
                                $arreglo_periodos = ArrayHelper::map(\app\models\A2Liquidaciones::ObtenerPeriodosLiquidados(), 'periodos', 'periodos');                                
                                ?>
                                <?php
                                echo Html::dropDownList('periodo','', $arreglo_periodos, 
                                    ['prompt' => 'Seleccione un periodo','class' => 'form-control',])
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label" for="">Consorcio</label>
                            <?php
                                $arreglo_grupos = ArrayHelper::map(\app\models\A2Grupos::find()->orderBy(['descripcion'=>SORT_ASC])->all(), 'id_grupo', 'descripcion');                                
                                ?>
                                <?php
                                echo Html::dropDownList('consorcio','', $arreglo_grupos, 
                                    ['prompt' => 'Seleccione un consorcio','class' => 'form-control',])
                                ?>

                        </div>
                    </div>
                    <div class="row">
                    <div class="col-xs-12 col-md-12">
                            <label class="control-label" for="">Formato</label>
                            <?= Html::dropDownList('formato', '', ['pdf'=>'PDF','excel'=>'EXCEL'],['class' => 'form-control']) ?>

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