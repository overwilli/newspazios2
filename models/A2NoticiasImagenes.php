<?php

namespace app\models;

use Yii;
use yii\imagine\Image;
/**
 * This is the model class for table "a2_noticias_imagenes".
 *
 * @property integer $id_noticia_imagen
 * @property integer $id_noticia
 * @property string $ImgPath
 * @property string $ImgDesc
 * @property string $ImgOrden
 * @property integer $ImgTipo
 */
class A2NoticiasImagenes extends \yii\db\ActiveRecord
{
    public $imageFile;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'a2_noticias_imagenes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_noticia', 'ImgOrden', 'ImgTipo'], 'integer'],
            [['id_noticia', 'imageFile',], 'required'],
            [['ImgPath'], 'string', 'max' => 255],
            [['ImgDesc'], 'string', 'max' => 250],
            [['imageFile'], 'safe'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif', 'checkExtensionByMimeType' => false,'maxFiles'=>4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_noticia_imagen' => 'Id Noticia Imagen',
            'id_noticia' => 'Id Noticia',
            'ImgPath' => 'Archivo',
            'ImgDesc' => 'Img Desc',
            'ImgOrden' => 'Img Orden',
            'ImgTipo' => 'Img Tipo',
			'imageFile'=>'Archivo de Imagen'
        ];
    }
    
    public function upload() {
        if ($this->validate()) {
            $id_unico = uniqid();
            $nombre_file = $id_unico . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs('./images/inmuebles/' . $nombre_file);
            $this->ImgPath = $nombre_file;
            
            //            Image::crop('./images/inmuebles/' . $nombre_file, 120, 120)
//                    ->save(Yii::getAlias('./images/inmuebles_crops/' . $nombre_file), ['quality' => 80]);

            Image::thumbnail('./images/inmuebles/' . $nombre_file, 120, 120)
                    ->save(Yii::getAlias('./images/inmuebles_thumbs/' . $nombre_file), ['quality' => 100]);
            
            return true;
        } else {
            return false;
        }
    }
    public function getImageurl() {
        return \Yii::$app->request->baseUrl . "/images/inmuebles_thumbs/" . $this->ImgPath;
    }
    
    public function getInmueble() {
        return $this->hasOne(A2Noticias::className(), ['id_noticia' => 'id_noticia']);
    }
}
