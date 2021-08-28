<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_clientes".
 *
 * @property integer $id_cliente
 * @property string $DNI
 * @property string $NOMBRE
 * @property string $NOMBRE_FANTASIA
 * @property string $DIRECCION
 * @property string $LOCALIDAD
 * @property string $BARRIO
 * @property string $PROVINCIA
 * @property string $TELEFONO
 * @property string $TELEF2
 * @property string $TELEF3
 * @property string $EMAIL
 * @property string $NRO_CUENTA
 * @property string $CUIL
 * @property integer $EST_CIVIL
 * @property string $FECNAC
 * @property integer $PRIM_NUPCIAS
 * @property string $DNI_CONYUGE
 * @property string $NOMBRE_CONYUGE
 * @property string $CUIL_CONYUGE
 * @property string $OBSERVACIONES
 */
class A2Clientes extends \yii\db\ActiveRecord {

	
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'a2_clientes';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['CUIL', 'DNI', 'NOMBRE', 'BARRIO','TELEFONO','provincia_id', 'localidad_id'], 'required'],
            [['EST_CIVIL', 'PRIM_NUPCIAS','provincia_id'], 'integer'],
            [['localidad_id'],'integer','message'=>"Seleccione una localidad"],
            [['FECNAC','estado','EMAIL'], 'safe'],
            [['OBSERVACIONES'], 'string'],
            [['DNI', 'DNI_CONYUGE'], 'string', 'max' => 8],
            [['NOMBRE', 'DIRECCION', 'EMAIL', 'NRO_CUENTA', 'NOMBRE_CONYUGE'], 'string', 'max' => 50],
            [['NOMBRE_FANTASIA', 'LOCALIDAD', 'BARRIO', 'PROVINCIA'], 'string', 'max' => 250],
            [['TELEFONO', 'TELEF2', 'TELEF3'], 'string', 'max' => 40],
            [['CUIL', 'CUIL_CONYUGE'], 'string', 'max' => 13],
            [['CUIL'], 'validar_cuil'],
        ];
    }

    public function validar_cuil() {
        if (strlen($this->CUIL) == 11) {
            $multiplos[0] = 5;
            $multiplos[1] = 4;
            $multiplos[2] = 3;
            $multiplos[3] = 2;
            $multiplos[4] = 7;
            $multiplos[5] = 6;
            $multiplos[6] = 5;
            $multiplos[7] = 4;
            $multiplos[8] = 3;
            $multiplos[9] = 2;

            $sumador = 0;
            for ($i = 0; $i < (strlen($this->CUIL) - 1); $i++) {
                $sumador = $sumador + ($this->CUIL[$i] * $multiplos[$i]);
            }
            $sumador = (11 - ($sumador % 11)) % 11;

            if ($this->CUIL[10] != $sumador) {
                $this->addError('CUIL', 'CUIT Inválido.');
            }
        } else {
            $this->addError('CUIL', 'CUIT Inválido.');
        }
    }
    
    /*public function beforeSave($insert) {
        //$arreglo = explode('/', $this->FECNAC);
        //$this->FECNAC = $arreglo[2] . "-" . $arreglo[1] . "-" . $arreglo[0];
        return TRUE;
    }*/

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_cliente' => 'Id Cliente',
            'DNI' => 'Dni',
            'NOMBRE' => 'Nombre',
            'NOMBRE_FANTASIA' => 'Nombre  Fantasia',
            'DIRECCION' => 'Direccion',
            'LOCALIDAD' => 'Localidad',
            'BARRIO' => 'Barrio',
            'PROVINCIA' => 'Provincia',
            'TELEFONO' => 'Telefono',
            'TELEF2' => 'Telef2',
            'TELEF3' => 'Telef3',
            'EMAIL' => 'Email',
            'NRO_CUENTA' => 'Nro  Cuenta',
            'CUIL' => 'Cuil',
            'EST_CIVIL' => 'Est  Civil',
            'FECNAC' => 'Fecnac',
            'PRIM_NUPCIAS' => 'Prim  Nupcias',
            'DNI_CONYUGE' => 'Dni  Conyuge',
            'NOMBRE_CONYUGE' => 'Nombre  Conyuge',
            'CUIL_CONYUGE' => 'Cuil  Conyuge',
            'OBSERVACIONES' => 'Observaciones',
            'estado' => 'Estado',
            'localidad_id' => 'Localidad ID',
            'provincia_id' => 'Provincia ID',
            
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstadoCivil() {
        return $this->hasOne(A2EstadoCivil::className(), ['id' => 'EST_CIVIL']);
    }
	
	public function getOperacionInmobiliaria() {
        return $this->hasMany(A2OperacionesInmobiliarias::className(), ['cod_cliente' => 'id_cliente']);
	}
	
	public function obtener_cuil(){
		try{
			$parte1=substr($this->CUIL,0,2);
			$parte2=substr($this->CUIL,2,8);
			$parte3=substr($this->CUIL,10,1);
			return $parte1."-".$parte2."-".$parte3;
		}catch(Exception $ex){
			return $this->CUIL;
		}
	}

}
