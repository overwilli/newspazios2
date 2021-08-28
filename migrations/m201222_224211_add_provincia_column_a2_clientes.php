<?php

use yii\db\Migration;

/**
 * Class m201222_224211_add_provincia_column_a2_clientes
 */
class m201222_224211_add_provincia_column_a2_clientes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('a2_clientes', 'localidad_id', $this->integer());
        $this->addColumn('a2_clientes', 'provincia_id', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('a2_clientes', 'localidad_id');
        $this->dropColumn('a2_clientes', 'provincia_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201222_224211_add_provincia_column_a2_clientes cannot be reverted.\n";

        return false;
    }
    */
}
