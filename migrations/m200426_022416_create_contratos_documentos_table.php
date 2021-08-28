<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contratos_documentos`.
 */
class m200426_022416_create_contratos_documentos_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('contratos_documentos', [
            'id' => $this->primaryKey(),
            'operacion_inmobiliaria_id'=> $this->integer(),
            'texto'=> $this->text(),
            'estado'=> " ENUM('ACTIVO','PENDIENTE','ELIMINADO')" ,
            'usuario_create'=> $this->string(),
            'time_create' => $this->datetime(),
            'usuario_update'=> $this->string(),
            'time_update'=> $this->datetime(),
            'plantilla_id'=> $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('contratos_documentos');
    }
}
