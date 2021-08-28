<?php

use yii\db\Migration;

/**
 * Class m200514_015335_add_campos_to_inmuebles_propietarios_table
 */
class m200514_015335_add_campos_to_inmuebles_propietarios_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('inmuebles_propietarios', 'estado', "ENUM('ACTIVO','ELIMINADO') NULL DEFAULT 'ACTIVO'");
        $this->addColumn('inmuebles_propietarios', 'porcentaje', $this->float());
        
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('inmuebles_propietarios', 'estado');
        $this->dropColumn('inmuebles_propietarios', 'porcentaje');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200514_015335_add_campos_to_inmuebles_propietarios_table cannot be reverted.\n";

        return false;
    }
    */
}
