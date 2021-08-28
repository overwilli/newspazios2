<?php

use yii\db\Migration;

/**
 * Handles the creation of table `plantillas`.
 */
class m200426_015546_create_plantillas_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('plantillas', [
            'id' => $this->primaryKey(),
            'titulo' => $this->string(),
            'texto' => $this->text(),
            'estado' => "ENUM('ACTIVO','ELIMINADO')",
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('plantillas');
    }
}
