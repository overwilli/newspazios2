<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mailing".
 *
 * @property integer $id
 * @property string $subject
 * @property string $archivo
 * @property integer $tipo
 * @property string $fecha_envio
 * @property string $ultimo_envio
 * @property boolean $estado
 */
class Mailing extends \yii\db\ActiveRecord {

    public $imageFile;
    public $bandera_scenario=FALSE;

    const SCENARIO_ACTUALIZAR_TEMPLATE = 'actualizar_template';
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'mailing';
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();        
        $scenarios[self::SCENARIO_ACTUALIZAR_TEMPLATE] = ['subject', 'imageFile','estado'];
        return $scenarios;      
        /*return [
            parent::scenarios(),
            self::SCENARIO_ACTUALIZAR_TEMPLATE => ['subject', 'imageFile','estado'],            
        ];*/
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['archivo', 'tipo'], 'required'],
            [['tipo'], 'integer'],
            [['fecha_envio', 'ultimo_envio'], 'safe'],
            [['fecha_envio'], 'validar_fecha', 'on' => ['insert', 'update']],
            [['estado'], 'boolean'],
            [['subject'], 'string', 'max' => 200],
            [['archivo'], 'string', 'max' => 50],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif', 'checkExtensionByMimeType' => false],
        ];
    }

    public function upload() {
        if ($this->validate()) {            
            $id_unico = uniqid();
            $nombre_file = $id_unico . '.' . $this->imageFile->extension;            
            $this->imageFile->saveAs('./../../assets/plantillas/' . $nombre_file);
            $this->url_image = $nombre_file;
            return true;
        } else {            
            return false;
        }
    }

    public function validar_fecha() {
        if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $this->fecha_envio)) {
            $this->addError('fecha_envio', 'La fecha debe tener un formato correcto');
        }
    }

    public function beforeSave($insert) {
         
        if ($this->bandera_scenario!=self::SCENARIO_ACTUALIZAR_TEMPLATE) {
            $arreglo = explode('/', $this->fecha_envio);
            $this->fecha_envio = $arreglo[2] . "-" . $arreglo[1] . "-" . $arreglo[0];
        }
        return TRUE;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'subject' => 'Asunto',
            'archivo' => 'Archivo',
            'tipo' => 'Tipo',
            'fecha_envio' => 'Fecha Envio',
            'ultimo_envio' => 'Ultimo Envio',
            'estado' => 'Estado',
            'imageFile'=>'Imagen'
        ];
    }

}
