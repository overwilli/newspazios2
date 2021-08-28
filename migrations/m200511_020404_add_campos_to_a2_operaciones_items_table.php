<?php

use yii\db\Migration;

/**
 * Class m200511_020404_add_campos_to_a2_operaciones_items_table
 */
class m200511_020404_add_campos_to_a2_operaciones_items_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('a2_operaciones_items', 'estado', "ENUM('ACTIVO','PENDIENTE','ELIMINADO')");
        
        $this->update('{{%a2_operaciones_items}}', [
            'estado' => 'ACTIVO',
        ]);

        $this->addColumn('a2_operaciones_items_copy', 'estado', "ENUM('ACTIVO','PENDIENTE','RENOVADO','FINALIZADO','ELIMINADO')");
        
        $this->update('{{%a2_operaciones_items_copy}}', [
            'estado' => 'ACTIVO',
        ]);

        $sql="INSERT INTO a2_operaciones_items (`id_item`,`id_operacion`,`anio`,`mes`,`id_factura`,`monto`,estado)
        SELECT `id_item`,`id_operacion`,`anio`,`mes`,`id_factura`,`monto`,estado
        FROM a2_operaciones_items_copy WHERE id_item NOT IN 
        (SELECT id_item FROM a2_operaciones_items); ";
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('a2_operaciones_items', 'estado');
        $this->dropColumn('a2_operaciones_items_copy', 'estado');

        $sql="DELETE FROM a2_operaciones_items WHERE id_item IN 
        (SELECT id_item FROM a2_operaciones_items_copy) ";
        $this->execute($sql);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200511_020404_add_campos_to_a2_operaciones_items_table cannot be reverted.\n";

        return false;
    }
    */
}
