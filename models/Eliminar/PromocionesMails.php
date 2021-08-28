<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "promociones_mails".
 *
 * @property integer $id
 * @property string $asunto
 * @property string $fecha_creacion
 * @property string $fecha_envio
 * @property string $condiciones
 * @property string $estado
 *
 * @property PromocionIndividual[] $promocionIndividuals
 */
class PromocionesMails extends \yii\db\ActiveRecord {

    public $arreglo_localidades=NULL;
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'promociones_mails';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fecha_creacion', 'fecha_envio', 'edad_minima', 'edad_maxima', 'codigo_postal'], 'safe'],
            [['asunto', 'fecha_envio'], 'required'],
            [['fecha_envio'], 'validar_fecha'],
            [['condiciones', 'estado'], 'string'],
            [['asunto'], 'string', 'max' => 255],
            [['segmentacion'], 'validar_segmentacion'],
        ];
    }

    public function validar_segmentacion() {
        switch ($this->segmentacion) {
            case 0: {
                    
                }break;
            case 1: {
                    if (empty($this->edad_minima) || !is_numeric($this->edad_minima) ||
                            ($this->edad_minima < 0 || $this->edad_minima > 130)) {
                        $this->addError('edad_minima', utf8_encode('La edad mínima debe estar comprendida entre 0 y 130 años'));
                    }
                    if (empty($this->edad_maxima) || !is_numeric($this->edad_maxima) ||
                            ($this->edad_maxima < 0 || $this->edad_maxima > 130)) {
                        $this->addError('edad_maxima', utf8_encode('La edad máxima debe estar comprendida entre 0 y 130 años'));
                    }
                }break;
            case 2: {
                if(count($this->arreglo_localidades)==0){
                    $this->addError('codigo_postal', utf8_encode('Debe seleccionar al menos una localidad.'));
                }
                    /*if (!is_numeric($this->codigo_postal)) {
                        $this->addError('codigo_postal', utf8_encode('El codigo postal debe ser un número.'));
                    } else {
                        $localidad = Localidades::find()->where(['id' => $this->codigo_postal])->one();
                        if (!$localidad) {
                            $this->addError('codigo_postal', utf8_encode('No existe la localidad indicada.'));
                        }
                    }*/
                }break;
            case 3: {
                    if (empty($this->codigo_empleado)) {
                        $this->addError('codigo_empleado', utf8_encode('El codigo de empleo debe ser seleccionado.'));
                    }
                }break;
            case 4: {
                    if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $this->deuda_desde)) {
                        $this->addError('deuda_desde', utf8_encode('Debe ingresar la fecha deuda desde.'));
                    }
                    if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $this->deuda_hasta)) {
                        $this->addError('deuda_hasta', utf8_encode('Debe ingresar la fecha deuda hasta.'));
                    }
                }break;
            default : {
                    
                }break;
        }
    }

    public function validar_fecha() {
        if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $this->fecha_envio)) {
            $this->addError('fecha_envio', 'La fecha debe tener un formato correcto');
        }
    }

    public function beforeSave($insert) {
        $arreglo = explode('/', $this->fecha_envio);
        $this->fecha_envio = $arreglo[2] . "-" . $arreglo[1] . "-" . $arreglo[0];
        return TRUE;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'asunto' => 'Asunto',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_envio' => 'Fecha de Envio',
            'condiciones' => 'Condiciones',
            'estado' => 'Estado',
            'segmentacion' => 'Segmentacion',
            'edad_minima' => utf8_encode('Edad Mínima'),
            'edad_maxima' => utf8_encode('Edad Máxima'),
            'codigo_postal' => 'Codigo Postal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromocionIndividuals() {
        return $this->hasMany(PromocionIndividual::className(), ['promociones_mails_id' => 'id']);
    }

    public function total_promociones_por_cliente($dni) {
        $query = "SELECT COUNT(*) FROM promociones_mails INNER JOIN envio_mails ON promociones_mails.id=envio_mails.promocion_id WHERE envio_mails.dni='{$dni}'";
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand($query)->queryScalar();
        return $model;
    }

}
