<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inmueble_porcentaje_propietario".
 *
 * @property integer $id
 * @property integer $inmueble_id
 * @property double $porcentaje
 * @property string $propietario_id
 * @property string $estado
 */
class InmueblePorcentajePropietario extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'inmueble_porcentaje_propietario';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['inmueble_id'], 'integer'],
            [['porcentaje'], 'number'],
            [['estado'], 'string'],
            [['propietario_id'], 'string', 'max' => 45],
            [['propietario_id'], 'existe_propietario'],
            [['porcentaje'], 'supera_porcentaje_maximo'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'inmueble_id' => 'Inmueble ID',
            'porcentaje' => 'Porcentaje',
            'propietario_id' => 'Propietario ID',
            'estado' => 'Estado',
        ];
    }

    public function getInmueble() {
        return $this->hasOne(A2Noticias::className(), ['id_noticia' => 'inmueble_id']);
    }

    public function getPropietario() {
        return $this->hasOne(Propietarios::className(), ['id' => 'propietario_id']);
    }

    public function existe_propietario() {
        $model = InmueblePorcentajePropietario::find()->where(['inmueble_id' => $this->inmueble_id, 'propietario_id' => $this->propietario_id])->one();

        if ($this->isNewRecord) {
            if ($model) {
                $this->addError('propietario_id', utf8_encode("Ya se encuentra cargado el propietario"));
            }
        } else {
            if ($model->id != $this->id) {
                $this->addError('propietario_id', utf8_encode("Ya se encuentra cargado el propietario"));
            }
        }
    }

    public function supera_porcentaje_maximo() {
        $query = "SELECT SUM(porcentaje) as cantidad FROM inmueble_porcentaje_propietario WHERE inmueble_id='{$this->inmueble_id}' AND estado='ACTIVO'";

        $connection = Yii::$app->getDb();
        $cantidad = $connection->createCommand($query)->queryScalar();
        if ($this->isNewRecord) {
            if ($cantidad + $this->porcentaje > 100) {
                $this->addError('porcentaje', utf8_encode("El porcentaje ingresado supera al 100% del total entre los propietarios asignados al inmueble."));
            }
        } else {
            $model = InmueblePorcentajePropietario::find()->where(['inmueble_id' => $this->inmueble_id, 'propietario_id' => $this->propietario_id])->one();
            if (($cantidad - $model->porcentaje) + $this->porcentaje > 100) {
                $this->addError('porcentaje', utf8_encode("El porcentaje ingresado supera al 100% del total entre los propietarios asignados al inmueble."));
            }
        }
    }

}
