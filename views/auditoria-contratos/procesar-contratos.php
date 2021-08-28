<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AuditoriaContratosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auditoria de Contratos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auditoria-contratos-index">

<div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="alert alert-info alert-dismissible">                            
                        <h4><i class="icon fa fa-check"></i> Mensaje</h4>
                        <?php echo utf8_encode($mensaje); ?>
                    </div>
                </div>
            </div>
        </div>
</div>
