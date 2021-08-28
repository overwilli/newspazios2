<?php

use yii\db\Migration;

/**
 * Class m200511_020016_add_campos_to_a2_movimientos_table
 */
class m200511_020016_add_campos_to_a2_movimientos_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('a2_movimientos', 'propiedad_id', $this->integer());
        $this->addColumn('a2_movimientos', 'operacion_id', $this->integer());
        $this->addColumn('a2_movimientos', 'data', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('a2_movimientos', 'propiedad_id');
        $this->dropColumn('a2_movimientos', 'operacion_id');
        $this->dropColumn('a2_movimientos', 'data');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200511_020016_add_campos_to_a2_movimientos_table cannot be reverted.\n";

        return false;
    }
    */
}
