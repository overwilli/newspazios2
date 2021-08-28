<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pagos_parciales`.
 */
class m200627_161410_create_pagos_parciales_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('pagos_parciales', [
            'id' => $this->primaryKey(),
            'liquidacion_id'=> $this->integer(),
            'monto'=>$this->decimal(10,2),
            'estado'=>"ENUM('anulado','pagado')",
            'data' => $this->text(),
            'movimiento_id'=> $this->integer(),
        ]);
        $this->insert('{{%a2_movimiento_tipo}}', [
            'id_tipo' => 16,
            'denominacion' => 'Pago Parcial',
            'tipo_movimiento' => 1,
        ]);
        $this->insert('{{%a2_movimiento_tipo}}', [
            'id_tipo' => 17,
            'denominacion' => 'Anular Pago Parcial',
            'tipo_movimiento' => 2,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('pagos_parciales');
        $this->delete('a2_movimiento_tipo', ['id_tipo' => 16]);
        $this->delete('a2_movimiento_tipo', ['id_tipo' => 17]);
    }
}
