<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Gastos`.
 */
class m200426_022844_create_Gastos_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Gastos', [
            'id' => $this->primaryKey(),
            'inmueble_id'=> $this->integer(),
            'operacion_id'=> $this->integer(),
            'fecha'=>$this->date() ,
            'importe'=>$this->decimal(10,2),
            'estado'=>"ENUM('PENDIENTE','PAGADO') NULL DEFAULT 'PENDIENTE'",
            'observacion'=>$this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('Gastos');
    }
}
