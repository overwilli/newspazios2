<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\A2NoticiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expensas Pendientes de Aprobación';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="a2-noticias-index">

<div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <?php
            $arreglo_secciones = ArrayHelper::map(\app\models\A2Secciones::find()->orderBy('seccion')->all(), 'id_seccion', 'seccion');
            //$arreglo_secciones[] = 'Todos';
            ?>    
            <?php //echo $this->render('_search_expensas', ['model' => $searchModel]); ?>

            <?php
            if($expensas_pendientes || $expensas_pendientes_grupo){
                $form = ActiveForm::begin(['action'=>["a2-noticias/aprobar-expensas"]]);	?>
                <input type="hidden" name="operacion_id" value="<?php echo $searchModel->operacion_id;?>" />
                <input type="hidden" name="propiedad_id" value="<?php echo $searchModel->inmuebles_id;?>" />
                <table class="table"  border="0" cellspacing="4" cellpadding="0" align="center" >  
                    
                    <tr id="cabezera-tabla">
                        <td><input type="checkbox" value="" id="marcar_todos" /><label>Todos</label></td>
                        <td >INMUEBLE</td>
                        <td >GRUPO</td>
                        <td >CONCEPTO</td>	  
                        <td >PERIODO</td>

                        <td >IMPORTE</td>									                    
                    </tr>
                    <?php
                    foreach($expensas_pendientes as $row) {
                        echo "<tr>";
                        echo "<td><input class='expensas_pendientes' type='checkbox' name='expensas_pendientes[]' value='" . $row['id'] . "' /></td>";
                        $model_prop=\app\models\A2Noticias::find()->where(['id_noticia'=>$row['inmuebles_id']])->one();
                        echo "<td>" . $model_prop->direccion . "</td>";
                        echo "<td>" . $row['descripcion'] . "</td>";
                        echo "<td>" . $row['mes'] . "/" . $row['year'] . "</td>";

                        echo "<td><b>$ " . $row['importe'] . "</b></td>";									
                        echo "</tr>";
                    }
                    foreach($expensas_pendientes_grupo as $row) {
                        //$model_prop=\app\models\A2Noticias::find()->where(['id_noticia'=>$row['inmuebles_id']])->one();
                        

                        echo "<tr>";
                        echo "<td><input class='expensas_pendientes_grupo' type='checkbox' name='expensas_pendientes_grupo[]' value='" . $row['id'] . "' /></td>";
                        
                        //echo "<td>" . $model_prop->direccion . "</td>";
                        echo "<td></td>";
                        echo "<td>" . $row['descripcion_grupo'] . "</td>";
                        echo "<td>" . $row['descripcion'] . "</td>";
                        echo "<td>" . $row['mes'] . "/" . $row['year'] . "</td>";

                        echo "<td><b>$ " . $row['importe'] . "</b></td>";									
                        echo "</tr>";
                    }
                    ?>
                </table>							
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" name="Guardar" value="Confirmar Pendientes"/>
                    <input class="btn btn-danger" type="submit" name="Anular" value="Anular Seleccionados"/>
                </div>
                <?php
                ActiveForm::end();
            }else{
                ?>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="alert alert-info alert-dismissible">
                        <?php echo "No existen expensas pendientes de aprobar.";?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var base_url = '<?php echo Yii::getAlias('@web') ?>';
</script>
<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/a2-noticias/aprobar-expensas.js', ['depends' => [\yii\web\JqueryAsset::className(), \dmstr\web\AdminLteAsset::className()]]);
?>
