<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GrupoExpensas */

$this->title = 'Nueva Expensa de Consorcio';
$this->params['breadcrumbs'][] = ['label' => 'Grupo Expensas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupo-expensas-create">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">
                <h1><?= Html::encode($this->title) ?></h1>
                </div>
        <div class="box-body">                  

            <?= $this->render('_form_multiple', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
<script type="text/javascript">
        var base_url = '<?php echo Yii::getAlias('@web') ?>';
    </script>
    <?php
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/grupo-expensas/create-multiple.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
    ?>
