<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "promomail_localidades".
 *
 * @property integer $id
 * @property integer $promomail_id
 * @property integer $localidad_id
 */
class PromomailLocalidades extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promomail_localidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promomail_id', 'localidad_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promomail_id' => 'Promomail ID',
            'localidad_id' => 'Localidad ID',
        ];
    }
}
