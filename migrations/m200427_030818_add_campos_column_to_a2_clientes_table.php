<?php

use yii\db\Migration;

/**
 * Handles adding campos to table `a2_clientes`.
 */
class m200427_030818_add_campos_column_to_a2_clientes_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('a2_clientes', 'estado', "ENUM('ACTIVO','PENDIENTE','ELIMINADO')");
        $this->update('{{%a2_clientes}}', [
            'estado' => 'ACTIVO',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('a2_clientes', 'estado');
    }
}
