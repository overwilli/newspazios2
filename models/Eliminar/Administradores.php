<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "administradores".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $updated_date
 */
class Administradores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'administradores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_date'], 'safe'],
            [['username', 'password'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'updated_date' => 'Updated Date',
        ];
    }
}
