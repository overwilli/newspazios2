<?php

use yii\db\Migration;

/**
 * Class m200514_020102_add_campos_to_inmuebles_propietarios_table
 */
class m200514_020102_add_campos_to_propietarios_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('propietarios', 'localidad', $this->string());
        $this->addColumn('propietarios', 'provincia', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('propietarios', 'localidad');
        $this->dropColumn('propietarios', 'provincia');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200514_020102_add_campos_to_inmuebles_propietarios_table cannot be reverted.\n";

        return false;
    }
    */
}
