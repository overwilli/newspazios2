<?php

use yii\db\Migration;

/**
 * Class m200514_014540_add_campos_to_a2_liquidaciones_table
 */
class m200514_014540_add_campos_to_a2_liquidaciones_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('a2_liquidaciones', 'estado', "ENUM('ACTIVO','PENDIENTE','PREIMPRESO','PAGADO','ELIMINADO')");
        
        $sql="UPDATE `a2_liquidaciones` SET estado='ACTIVO' WHERE fecha_pago IS NULL OR fecha_pago='0000-00-00 00:00:00';";
        $this->execute($sql);
        $sql="UPDATE `a2_liquidaciones` SET estado='PAGADO' WHERE fecha_pago IS NOT NULL AND fecha_pago<>'0000-00-00 00:00:00'";
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('a2_liquidaciones', 'estado');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200514_014540_add_campos_to_a2_liquidaciones_table cannot be reverted.\n";

        return false;
    }
    */
}
