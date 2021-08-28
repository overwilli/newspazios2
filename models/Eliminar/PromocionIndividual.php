<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "promocion_individual".
 *
 * @property integer $id
 * @property string $enlace
 * @property string $url_image
 * @property string $estado
 * @property integer $promociones_mails_id
 *
 * @property PromocionesMails $promocionesMails
 */
class PromocionIndividual extends \yii\db\ActiveRecord {

    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'promocion_individual';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['estado'], 'string'],
            [['promociones_mails_id', 'estado'], 'required'],
            [['promociones_mails_id', 'orden'], 'integer'],
            [['enlace', 'url_image','texto_imagen'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif', 'checkExtensionByMimeType' => false],
            [['promociones_mails_id'], 'exist', 'skipOnError' => true, 'targetClass' => PromocionesMails::className(), 'targetAttribute' => ['promociones_mails_id' => 'id']],
        ];
    }

    public function upload() {
        if ($this->validate()) {
            $id_unico = uniqid();
            $nombre_file = $id_unico . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs('./../../assets/promociones-mail/' . $nombre_file);
            $this->url_image = $nombre_file;
            return true;
        } else {
            return false;
        }
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->orden)) {
                    $connection = Yii::$app->getDb();
                    $query = 'SELECT (MAX(orden)+1) as maximo FROM promocion_individual WHERE promociones_mails_id=' . $this->promociones_mails_id;
                    $maximo = $connection->createCommand($query)->queryScalar();
                    
                    if ($maximo) {
                        $this->orden = $maximo;
                    } else {
                        $this->orden = 1;
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'enlace' => 'Link de la promocion',
            'url_image' => 'Url Image',
            'texto_imagen'=>'Texto de la imagen',
            'orden' => 'Orden',
            'estado' => 'Estado',
            'promociones_mails_id' => 'Promocion por Mail',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromocionesMails() {
        return $this->hasOne(PromocionesMails::className(), ['id' => 'promociones_mails_id']);
    }

}
