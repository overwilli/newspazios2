<?php

namespace app\models;

use Yii;
use \yii\db\Expression;
/**
 * This is the model class for table "a2_caja".
 *
 * @property integer $id_caja
 * @property string $inicio_fecha
 * @property string $inicio_hora
 * @property string $inicio_usuario
 * @property string $inicio
 * @property string $caja_final
 * @property string $caja_dia_siguiente
 * @property string $rendicion
 * @property string $sobrante_faltante
 * @property string $caja_cierre
 * @property string $cierre_fecha
 * @property string $cierre_hora
 * @property string $cierre_usuario
 * @property string $cobranzas_efectivo
 * @property string $ingresos_varios
 * @property string $cheques
 * @property string $intereses_mora
 * @property string $depositos
 * @property string $retenciones
 * @property string $gastos_varios
 * @property string $supermercado
 * @property string $timestamp
 * @property integer $numero_caja
 */
class A2Caja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_caja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inicio_fecha', 'inicio_hora', 'inicio_usuario', 'numero_caja'], 'required'],
            [['inicio_fecha', 'inicio_hora', 'cierre_fecha', 'cierre_hora', 'timestamp'], 'safe'],
            [['inicio', 'caja_final', 'caja_dia_siguiente', 'rendicion', 'sobrante_faltante', 'caja_cierre', 'cobranzas_efectivo', 'ingresos_varios', 'cheques', 'intereses_mora', 'depositos', 'retenciones', 'gastos_varios', 'supermercado'], 'number'],
            [['numero_caja'], 'integer'],
            [['inicio_usuario', 'cierre_usuario'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_caja' => 'Id Caja',
            'inicio_fecha' => 'Inicio Fecha',
            'inicio_hora' => 'Inicio Hora',
            'inicio_usuario' => 'Inicio Usuario',
            'inicio' => 'Inicio',
            'caja_final' => 'Caja Final',
            'caja_dia_siguiente' => 'Caja Dia Siguiente',
            'rendicion' => 'Rendicion',
            'sobrante_faltante' => 'Sobrante Faltante',
            'caja_cierre' => 'Caja Cierre',
            'cierre_fecha' => 'Cierre Fecha',
            'cierre_hora' => 'Cierre Hora',
            'cierre_usuario' => 'Cierre Usuario',
            'cobranzas_efectivo' => 'Cobranzas Efectivo',
            'ingresos_varios' => 'Ingresos Varios',
            'cheques' => 'Cheques',
            'intereses_mora' => 'Intereses Mora',
            'depositos' => 'Depositos',
            'retenciones' => 'Retenciones',
            'gastos_varios' => 'Gastos Varios',
            'supermercado' => 'Supermercado',
            'timestamp' => 'Timestamp',
            'numero_caja' => 'Numero Caja',
        ];
    }
    
    public function obtener_caja_abierta($numero_caja){
        $model_caja = A2Caja::find()->where('numero_caja=:numero_caja AND cierre_fecha IS NULL', [':numero_caja' => $numero_caja,])->one();
        return $model_caja;
    }
    
    public function obtener_caja_cerrada($numero_caja,$cierre_fecha){
        $model_caja = A2Caja::find()->where('numero_caja=:numero_caja AND cierre_fecha=:cierre_fecha', 
                [':numero_caja' => $numero_caja,':cierre_fecha' => $cierre_fecha,])->one();
        return $model_caja;
    }
    
    public function obtener_cajas_abiertas(){
        $query = "SELECT a2_caja.id_caja,a2_caja.inicio_fecha,a2_caja.inicio,
            a2_caja.caja_final as aux_caja_final,a2_caja.sobrante_faltante as aux_sobrante_faltante,
            a2_caja.caja_cierre,a2_caja.rendicion,a2_caja.cierre_usuario,a2_caja.cierre_fecha,
            a2_caja.cierre_hora,a2_caja.numero_caja,nikname FROM a2_caja INNER JOIN a_noticias_usuarios ON 
            a2_caja.numero_caja=a_noticias_usuarios.numero_caja WHERE cierre_usuario IS NULL";

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
    
    public function obtener_ultima_caja($numero_caja){
        $query = "SELECT a2_caja.id_caja, a2_caja.inicio_fecha, a2_caja.inicio, a2_caja.caja_cierre,
            a2_caja.cierre_usuario FROM a2_caja WHERE numero_caja={$numero_caja} ORDER BY cierre_fecha DESC,a2_caja.id_caja DESC LIMIT 1";

        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryRow();
        return $model;
    }
}
