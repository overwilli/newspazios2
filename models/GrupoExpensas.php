<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grupo_expensas".
 *
 * @property integer $id
 * @property integer $grupo_id
 * @property integer $tipo_expensa_id
 * @property integer $mes
 * @property integer $year
 * @property string $importe
 */
class GrupoExpensas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grupo_expensas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grupo_id','tipo_expensa_id','mes','year','importe'],'required'],
            [['grupo_id', 'tipo_expensa_id', 'mes', 'year'], 'integer'],
            [['importe'], 'number'],
            [['expensas_por'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grupo_id' => 'Grupo o Consorcio',
            'tipo_expensa_id' => 'Tipo de Expensa',
            'mes' => 'Mes',
            'year' => 'Año',
            'importe' => 'Importe',
        ];
    }

    public function getTipoExpensas() {
        return $this->hasOne(TipoExpensas::className(), ['id' => 'tipo_expensa_id']);
    }

    public function getGrupos() {
        return $this->hasOne(A2Grupos::className(), ['id_grupo' => 'grupo_id']);
    }
}
