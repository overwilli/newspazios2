<?php

use yii\db\Migration;

/**
 * Class m200514_013610_add_campos_to_operaciones_expensas_table
 */
class m200514_013610_add_campos_to_operaciones_expensas_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('operaciones_expensas', 'estado_reg', "ENUM('ACTIVO','PENDIENTE','ELIMINADO')");
        $this->addColumn('operaciones_expensas', 'grupo_expensas_id', $this->integer());
        $this->update('{{%operaciones_expensas}}', [
            'estado_reg' => 'ACTIVO',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('operaciones_expensas', 'estado_reg');
        $this->dropColumn('operaciones_expensas', 'grupo_expensas_id');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200514_013610_add_campos_to_operaciones_expensas_table cannot be reverted.\n";

        return false;
    }
    */
}
