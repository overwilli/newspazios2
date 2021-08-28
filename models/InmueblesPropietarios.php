<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inmuebles_propietarios".
 *
 * @property integer $id
 * @property integer $inmueble_id
 * @property integer $propietario_id
 * @property double $comision
 * @property string $fecha_carga
 * @property string $fecha_actualizacion
 * @property string $usuario_carga
 * @property string $usuario_actualizacion
 */
class InmueblesPropietarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inmuebles_propietarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inmueble_id','propietario_id','comision', ], 'required'],
            [['inmueble_id', 'propietario_id'], 'integer'],
            [['comision','porcentaje'], 'number'],
            //[['propietario_id'], 'validar_existencia_propietario'],
            [['fecha_carga', 'fecha_actualizacion'], 'safe'],
            [['usuario_carga', 'usuario_actualizacion'], 'string', 'max' => 100],
            [['porcentaje'], 'supera_porcentaje_maximo'],
        ];
    }
     public function validar_existencia_propietario() {         
         $model=InmueblesPropietarios::find()->
                 where(['inmueble_id'=>$this->inmueble_id,'propietario_id'=>$this->propietario_id,])->one();
         if($this->isNewRecord){
            if($model){
                $this->addError('propietario_id', utf8_encode('El propietario ya se encuentra cargado en la propiedad.'));
            }
         }else{
            if($model->id!=$this->id){
                $this->addError('propietario_id', utf8_encode('El propietario ya se encuentra cargado en la propiedad.'));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inmueble_id' => 'Inmueble ID',
            'propietario_id' => 'Propietario',
            'comision' => 'Comision',
            'fecha_carga' => 'Fecha Carga',
            'fecha_actualizacion' => 'Fecha Actualizacion',
            'usuario_carga' => 'Usuario Carga',
            'usuario_actualizacion' => 'Usuario Actualizacion',
        ];
    }
    
    public function getPropietario() {
        return $this->hasOne(Propietarios::className(), ['id' => 'propietario_id']);
    }
    
    public function getInmueble() {
        return $this->hasOne(A2Noticias::className(), ['id_noticia' => 'inmueble_id']);
    }

    public function supera_porcentaje_maximo() {
        $query = "SELECT SUM(porcentaje) as cantidad FROM inmuebles_propietarios WHERE inmueble_id='{$this->inmueble_id}' AND estado='ACTIVO'";

        $connection = Yii::$app->getDb();
        $cantidad = $connection->createCommand($query)->queryScalar();
        if ($this->isNewRecord) {
            if ($cantidad + $this->porcentaje > 100) {
                $this->addError('porcentaje', utf8_encode("El porcentaje ingresado supera al 100% del total entre los propietarios asignados al inmueble."));
            }
        } else {
            $model = InmueblesPropietarios::find()->where(['inmueble_id' => $this->inmueble_id, 'propietario_id' => $this->propietario_id])->one();
            if (($cantidad - $model->porcentaje) + $this->porcentaje > 100) {
                $this->addError('porcentaje', utf8_encode("El porcentaje ingresado supera al 100% del total entre los propietarios asignados al inmueble."));
            }
        }
    }
}
