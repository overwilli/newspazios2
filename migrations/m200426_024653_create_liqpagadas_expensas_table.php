<?php

use yii\db\Migration;

/**
 * Handles the creation of table `liqpagadas_expensas`.
 */
class m200426_024653_create_liqpagadas_expensas_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('liqpagadas_expensas', [
            'id' => $this->primaryKey(),
            'liquidacionpagadas_id'=> $this->integer(),
            'expensa_id'=> $this->integer(),
            'importe'=>$this->decimal(10,2),
            'fecha_carga'=> $this->datetime(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('liqpagadas_expensas');
    }
}
