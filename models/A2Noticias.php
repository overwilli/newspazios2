<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "a2_noticias".
 *
 * @property integer $id_noticia
 * @property string $fecha
 * @property integer $seccion
 * @property integer $pesoseccion
 * @property string $antetitulo
 * @property string $titulo
 * @property string $bajada
 * @property string $cuerpocompleto
 * @property string $foto
 * @property string $fotopie
 * @property integer $clicks
 * @property string $autor
 * @property string $editor
 * @property string $timestamp
 * @property string $fechatimestamp
 * @property string $fechacarga
 * @property integer $operacion
 * @property integer $alq_vendida
 * @property integer $id_estado
 * @property integer $id_grupo
 * @property string $direccion
 * @property double $precio
 * @property integer $ambientes
 * @property integer $sup_cubierta
 * @property integer $sup_terreno
 * @property integer $habitaciones
 * @property integer $dormitorios
 * @property integer $banios
 * @property integer $conv_desocup
 * @property integer $frente
 * @property integer $fondo
 * @property string $padroniibb
 * @property string $padronaguas
 * @property string $padronmunicipal
 * @property integer $provincia_id
 * @property integer $localidad_id
 */
class A2Noticias extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_noticias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'fecha', 'timestamp', 'fechacarga', 'luz', 'gas', 'cloaca', 'agua', 'parrilla', 'salon_u_m', 'piscina', 'seguridad', 'amueblado', 'descripcion', 'apto_comercial', 'apto_profesional', 'portero_electrico', 'disposicion', 'ascensor', 'patio', 'balcon', 'barrio', 'porcion'
            ], 'safe'],
            [[
                'seccion', 'pesoseccion', 'clicks', 'operacion', 'alq_vendida', 'id_estado', 'id_grupo', 'ambientes',
                'habitaciones', 'dormitorios', 'banios', 'conv_desocup',
                'provincia_id', 'codigo_postal', 'cochera', 'antiguedad'
            ], 'integer'],
            [['localidad_id'], 'integer', 'message' => "Seleccione una localidad"],
            [['bajada', 'cuerpocompleto'], 'string'],
            [[
                'precio', 'operacion', 'direccion', 'barrio', 'id_estado', 'provincia_id', 'localidad_id',
                'seccion', 'estado_reg', 'id_grupo'
            ], 'required'],
            [['precio', 'sup_cubierta', 'sup_terreno', 'frente', 'fondo'], 'number', 'min' => 0],
            [['porcion', 'ambientes', 'dormitorios', 'banios'], 'integer', 'min' => 0, 'max' => 100],
            [['antetitulo', 'titulo', 'fotopie', 'direccion', 'padroniibb', 'padronaguas', 'padronmunicipal'], 'string', 'max' => 250],
            [['foto', 'autor', 'editor'], 'string', 'max' => 50],
            [['fechatimestamp'], 'string', 'max' => 14],
            [['porcion'], 'validar_porcion'],
        ];
    }

    public function validar_porcion()
    {

        if (isset($this->id_grupo)) {
            if (!isset($this->porcion)) {
                $this->addError("porcion", "Debe ingresar el porcentaje.");
            } else {
                $query = "SELECT SUM(porcion) FROM a2_noticias WHERE id_grupo=" . $this->id_grupo;

                $connection = Yii::$app->getDb();
                $sumar_porcentaje = $connection->createCommand($query)->queryScalar();
                if($sumar_porcentaje+$this->porcion>100){
                    $this->addError("porcion", "El porcentaje sumado al de otras piedades del grupo supera el 100%.");
                }

            }
        }
    }

    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_noticia' => 'Id Noticia',
            'fecha' => 'Fecha',
            'seccion' => 'Tipo de Propiedad',
            'pesoseccion' => 'Pesoseccion',
            'antetitulo' => 'Antetitulo',
            'titulo' => 'Titulo',
            'bajada' => 'Bajada',
            'cuerpocompleto' => 'Cuerpocompleto',
            'foto' => 'Foto',
            'fotopie' => 'Fotopie',
            'clicks' => 'Clicks',
            'autor' => 'Autor',
            'editor' => 'Editor',
            'timestamp' => 'Timestamp',
            'fechatimestamp' => 'Fechatimestamp',
            'fechacarga' => 'Fechacarga',
            'operacion' => 'Operacion',
            'alq_vendida' => 'Alq Vendida',
            'id_estado' => 'Estado del Inmueble',
            'id_grupo' => 'Grupo',
            'direccion' => 'Direccion',
            'precio' => 'Precio',
            'ambientes' => 'Total de Ambientes',
            'sup_cubierta' => 'Sup. Cubierta(m2)',
            'sup_terreno' => 'Sup. Total(m2)',
            'habitaciones' => 'Habitaciones',
            'dormitorios' => 'Dormitorios',
            'banios' => 'Baños',
            'conv_desocup' => 'Conv Desocup',
            'frente' => 'Frente',
            'fondo' => 'Fondo',
            'padroniibb' => 'Padron IIBB',
            'padronaguas' => 'Padron de Agua',
            'padronmunicipal' => 'Padron Municipal',
            'provincia_id' => 'Provincia',
            'localidad_id' => 'Localidad',
            'portero_electrico' => 'Portero Eléctrico',
            'salon_u_m' => 'Salón de usos múltiples',
            'estado_reg' => 'Estado',
            'codigo_postal' => 'Codigo Postal',
            'antiguedad' => 'Año de Construcción',
            'porcion' => 'Porcion Expensas'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecciones()
    {
        return $this->hasOne(A2Secciones::className(), ['id_seccion' => 'seccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjetoPropiedad()
    {
        return $this->hasOne(A2ObjetoDePropiedad::className(), ['id_operacion' => 'operacion']);
    }

    public function getInmueblePropietario()
    {
        return $this->hasOne(InmueblesPropietarios::className(), ['inmueble_id' => 'id_noticia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperacionInmobiliaria($activo = TRUE)
    {
        return $this->hasMany(A2OperacionesInmobiliarias::className(), ['cod_propiedad' => 'id_noticia']);

        /*if($activo){
            return $this->hasOne(A2OperacionesInmobiliarias::className(), ['cod_propiedad' => 'id_noticia'])
                ->where('a2_operaciones_inmobiliarias.estado = :estado', [':estado' => 'ACTIVO']);
        }else{*/
        //    return $this->hasOne(A2OperacionesInmobiliarias::className(), ['cod_propiedad' => 'id_noticia']);
        //}
    }

    public function getFecha()
    {
        return date('d/m/Y', $this->fecha);
    }

    public function getPrecio()
    {
        return "$" . $this->precio;
    }
}
