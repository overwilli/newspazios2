<?php

use yii\db\Migration;

/**
 * Handles the creation of table `gestion_cobranzas`.
 */
class m200426_025007_create_gestion_cobranzas_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('gestion_cobranzas', [
            'id' => $this->primaryKey(),
            'fecha'=>$this->date() ,
            'fecha_notificacion'=>$this->date() ,
            'hora'=>$this->time(),
            'cliente_id'=> $this->integer(),
            'inmueble_id'=> $this->integer(),
            'operacion_id'=> $this->integer(),
            'nivel' => "TINYINT NULL",//smallint --tinyint --tinyInteger
            'observaciones'=>$this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('gestion_cobranzas');
    }
}
