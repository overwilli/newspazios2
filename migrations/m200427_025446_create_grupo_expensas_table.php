<?php

use yii\db\Migration;

/**
 * Handles the creation of table `grupo_expensas`.
 */
class m200427_025446_create_grupo_expensas_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('grupo_expensas', [
            'id' => $this->primaryKey(),
            'grupo_id' => $this->integer(),
            'tipo_expensa_id' => $this->integer(),
            'mes' => $this->integer(),
            'year' => $this->integer(),
            'importe' => $this->decimal(10, 2),
            'expensas_por' => "ENUM('GRUPO','RUBRO','MULTIPLE')",
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('grupo_expensas');
    }
}
