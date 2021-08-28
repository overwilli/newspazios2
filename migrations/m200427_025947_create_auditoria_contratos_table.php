<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auditoria_contratos`.
 */
class m200427_025947_create_auditoria_contratos_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('auditoria_contratos', [
            'id' => $this->primaryKey(),
            'operacion_id'=> $this->integer(),
            'fecha_contrato'=>$this->date() ,
            'fecha_procesamiento'=>$this->date() ,
            'estado_contrato'=>"ENUM('RENOVADO', 'ELIMINADO', 'PENDIENTE', 'NUEVO', 'FINALIZADO','ACTIVO')",
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('auditoria_contratos');
    }
}
