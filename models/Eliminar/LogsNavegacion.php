<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs_navegacion".
 *
 * @property integer $id
 * @property string $dni
 * @property string $accion
 * @property string $fecha_hora
 */
class LogsNavegacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logs_navegacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accion'], 'string'],
            [['fecha_hora'], 'safe'],
            [['dni'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dni' => 'Dni',
            'accion' => 'Accion',
            'fecha_hora' => 'Fecha Hora',
        ];
    }
    
    public function cantidad_visitas_navegacion() {
        $primer_dia = new \DateTime(date('Y-m-d'));
        $ultimo_dia = new \DateTime(date('Y-m-d'));

        $primer_dia->modify('first day of this month');
        $ultimo_dia->modify('last day of this month');
        
        $query = "SELECT sum(cantidad) as cantidad,accion FROM (SELECT COUNT(*)as cantidad,accion,fecha_hora FROM "
                . "logs_navegacion GROUP BY accion,date(fecha_hora)) as t1 WHERE "
                . "fecha_hora>='{$primer_dia->format('Y-m-d')}' AND fecha_hora<='{$ultimo_dia->format('Y-m-d')}' GROUP BY accion ORDER BY cantidad DESC";
        
        
                $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
    
    public function cantidad_visitas_navegacion_entre_fechas($desde,$hasta) {
        $primer_dia = new \DateTime($desde);
        $ultimo_dia = new \DateTime($hasta);

        //$primer_dia->modify('first day of this month');
        //$ultimo_dia->modify('last day of this month');
        
        $query = "SELECT sum(cantidad) as cantidad,accion FROM (SELECT COUNT(*)as cantidad,accion,fecha_hora FROM "
                . "logs_navegacion GROUP BY accion,date(fecha_hora)) as t1 WHERE "
                . "fecha_hora>='{$primer_dia->format('Y-m-d')}' AND fecha_hora<='{$ultimo_dia->format('Y-m-d')}' GROUP BY accion ORDER BY cantidad DESC";
        
        
                $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryAll();
        return $model;
    }
    
}
