<?php

use yii\db\Migration;

/**
 * Class m200520_022139_add_campos_to_a2_operaciones_items_table
 */
class m200520_022139_add_campos_to_a2_operaciones_items_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // creates index for column `category_id`
        $this->createIndex(
            'idx-a2_operaciones_items-anio',
            'a2_operaciones_items',
            'anio'
        );
        // creates index for column `category_id`
        $this->createIndex(
            'idx-a2_operaciones_items-mes',
            'a2_operaciones_items',
            'mes'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // drops index for column `tag_id`
        $this->dropIndex(
            'idx-a2_operaciones_items-anio',
            'a2_operaciones_items'
        );
        // drops index for column `tag_id`
        $this->dropIndex(
            'idx-a2_operaciones_items-mes',
            'a2_operaciones_items'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200520_022139_add_campos_to_a2_operaciones_items_table cannot be reverted.\n";

        return false;
    }
    */
}
