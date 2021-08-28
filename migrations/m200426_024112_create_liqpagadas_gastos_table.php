<?php

use yii\db\Migration;

/**
 * Handles the creation of table `liqpagadas_gastos`.
 */
class m200426_024112_create_liqpagadas_gastos_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('liqpagadas_gastos', [
            'id' => $this->primaryKey(),
            'liquidacionpagadas_id'=> $this->integer(),
            'gastos_id' => $this->string(),
            'importe'=>$this->decimal(10,2),
            'fecha_carga'=> $this->datetime(),
            'movimientos_id'=> $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('liqpagadas_gastos');
    }
}
